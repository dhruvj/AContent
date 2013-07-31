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
require_once(TR_INCLUDE_PATH.'classes/DAO/CoursesDAO.class.php');

global $_current_user;

if (!isset($_current_user)) {
    require(TR_INCLUDE_PATH.'header.inc.php');
    $msg->printInfos('INVALID_USER');
    require(TR_INCLUDE_PATH.'footer.inc.php');
    exit;
}
if ($_GET['edit'] == "Edit") {
    if (isset($_GET['id'])) {
        header("Location: ./ltiprovider_form.php?edit=Edit&id=".$_GET['id']);
    } else {
        $msg->addError('INVALID_TOOL');
    }
}
$toolprovider = new ToolProviderDAO();
if ($_GET['delete'] == "Delete") {
    if (isset ($_GET['id'])) {
        if ($toolprovider->isToolByUser($_SESSION['user_id'], intval($_GET['id']))) {
            if ($toolprovider->Delete(intval($_GET['id']))) {
                $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
            } else {
                $msg->addError('UNABLE_TO_DELETE');
            }
        } else {
            $msg->addError('INVALID_TOOL');
        }
    } else {
        $msg->addError('INVALID_TOOL');
    }
    header("Location: ./lti_providers.php");
}

$course = new UserCoursesDAO();
$tools = $toolprovider->getToolByUserId($_SESSION['user_id']);
$mytools = array();
if (is_array($tools)) {
    foreach ($tools as $tool) {
        $mytool['consumer_key'] = $tool['consumer_key'];
        $mytool['tool_id'] = $tool['tool_id'];
        $mytool['shared_secret'] = $tool['shared_secret'];
        $mycourse = $course->getCourseByToolId($tool['tool_id']);
        $mytool['course_title'] = $mycourse[0]['title'];
        $mytool['enabled'] = $tool['enabled'];
        $mytool['url'] = $_SERVER['HTTP_HOST'].str_replace('lti_providers.php', '', $_SERVER['REQUEST_URI'])."/tool.php?id=".$mytool['tool_id'];
        // Tool Info to be displayed in $mytools
        array_push($mytools, $mytool);
    }
}
/* template starts here */
$savant->assign('mytools', $mytools);

require(TR_INCLUDE_PATH.'header.inc.php');
$savant->display('oauth/lti_providers.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>