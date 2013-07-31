<?php
/************************************************************************/
/* AContent                                                             */
/************************************************************************/
/* Copyright (c) 2013                                                   */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('TR_INCLUDE_PATH', '../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/UserCoursesDAO.class.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/ToolProviderDAO.class.php');

global $_current_user;

if (!isset($_current_user)) {
    require(TR_INCLUDE_PATH.'header.inc.php');
    $msg->printInfos('INVALID_USER');
    require(TR_INCLUDE_PATH.'footer.inc.php');
    exit;
}
if (isset($_POST['cancel'])) {
    $msg->addFeedback('CANCELLED');
    header('Location: ../index.php');
    exit;
}
$toolprovider = new ToolProviderDAO();
$userCoursesDAO = new UserCoursesDAO();

if (isset($_POST['submit'])) {
    $tool = array();
    $tool['user_id'] = $_SESSION['user_id'];
    $tool['shared_secret'] = mysql_real_escape_string($_POST['shared_secret']);
    $tool['consumer_key'] = mysql_real_escape_string($_POST['consumer_key']);
    $tool['course_id'] = intval($_POST['course_id']);
    $tool['max_enrollments'] = mysql_real_escape_string($_POST['max_enrollments']);
    $tool['default_city'] = mysql_real_escape_string($_POST['default_city']);
    $tool['default_country'] = mysql_real_escape_string($_POST['default_country']);
    $tool['enabled'] = (strcmp($_POST['enabled'], 'on') == 0) ? 1 : 0;
    //check on consumer key
    $consumer_keys = $toolprovider->getToolByConsumerKey($tool['consumer_key']);
    if ($consumer_keys) {
        $msg->addError('CONSUMER_KEY_EXISTS');
    }
    if(!(preg_match("/[A-Za-z0-9\.]{10,32}/", $tool['consumer_key']))) { //minimum length of key must be 10 and max 32
        $msg->addError('INVALID_CONSUMER_KEY');
    }
    if(!(preg_match("/[A-Za-z0-9\.]{10,32}/", $tool['shared_secret']))) { //minimum length of key must be 10 and max 32
        $msg->addError('INVALID_SHARED_SECRET');
    }
    //check on posted course ID
    if (!$userCoursesDAO->get($_SESSION['user_id'], strval($tool['course_id']))) {
        $msg->addError('INVALID_COURSE');
    }
    //check on max enrollments: it must be >=0 and an integer
    if (!(intval($tool['max_enrollments']) >= 0)) {
        $msg->addError('INVALID_ENROLLMENTS');
    }
    //checks done!
    if ($msg->containsErrors()) {
        header('Location: ./ltiprovider_form.php');
        exit;
    }
    //check if we are editing
    if ($_GET['edit'] == "Edit") {
        if (isset ($_GET['id'])) {
            if ($toolprovider->isToolByUser($_SESSION['user_id'], intval($_GET['id']))) {
                if ($toolprovider->Update($tool, $_GET['id'])) {           // update the tool
                    $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
                } else {
                    $msg->addError('ACTION_FAILED');
                }
                header("Location: ./lti_providers.php");
                exit;
            } else {
                $msg->addError('INVALID_TOOL');
            }
        } else {
            $msg->addError('INVALID_TOOL');
        }
        header('Location: ./lti_providers.php');
        exit;
    }
    //we are not editing so lets create a new tool
    if ($toolprovider->Create($tool)) {
        $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
    } else {
        $msg->addError('ACTION_FAILED');
    }
    header('Location: ./lti_providers.php');
    exit;
}

if ($_GET['edit'] == "Edit") {
    if (isset ($_GET['id'])) {
        if ($toolprovider->isToolByUser($_SESSION['user_id'], intval($_GET['id']))) {
            $tool = $toolprovider->getToolByToolId(intval($_GET['id']));
            $isedit = 1;
        } else {
            $msg->addError('INVALID_TOOL');
        }
    } else {
        $msg->addError('INVALID_TOOL');
    }
    if ($msg->containsErrors()) {
        header('Location: ./lti_providers.php');
        exit;
    }
}

//Generating fields for the form if the page is not for editing
if ($_SESSION['user_id'] > 0) {
    $my_courses = $userCoursesDAO->getAuthoredCoursesByUserId($_SESSION['user_id']); 
}
if ($isedit != 1) {
    if (isset($_GET['_course_id']) && $userCoursesDAO->get($_SESSION['user_id'], mysql_real_escape_string(strval($_GET['_course_id'])))) {
        $savant->assign('course_id', mysql_real_escape_string($_GET['_course_id']));
    }
}   

$savant->assign('tool', $tool[0]);
$savant->assign('my_courses', $my_courses);
$savant->assign('isedit', $isedit);

require(TR_INCLUDE_PATH.'header.inc.php');
$savant->display('oauth/ltiprovider_form.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>