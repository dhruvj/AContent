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
require_once(TR_INCLUDE_PATH.'classes/DAO/LTIusersDAO.class.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/ToolProviderDAO.class.php');

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
$user = new LTIusersDAO();
$toolprovider = new ToolProviderDAO();
if (isset($_REQUEST['tool_id'])) {
    $tool_id = mysql_real_escape_string($_REQUEST['tool_id']);
    $user_id = mysql_real_escape_string($_GET['user_id']);
    if ($tool_id > 0 && $toolprovider->isToolByUser($_SESSION['user_id'], $tool_id)) {
        if (isset($_GET['disable'])) {
            if (isset($user_id) && $user->isLTIuser($user_id)) {
                $user->Disable($user_id);
                $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
            } else {
                $msg->addError('INVALID_USER');
                header('Location: ../index.php');
                exit;
            }
        } else if (isset($_GET['enable'])) {    
            if (isset($user_id) && $user->isLTIuser($user_id)) {
                $user->Enable($user_id);
                $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
            } else {
                $msg->addError('INVALID_USER');
                header('Location: ../index.php');
                exit;
            }
        } else if (isset($_GET['delete'])) {
            if (isset($user_id) && $user->isLTIuser($user_id)) {
                $user->Delete($user_id);
                $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
            } else {
                $msg->addError('INVALID_USER');
                header('Location: ../index.php');
                exit;
            }
        }
        $users = $user->getUserByToolId($tool_id);
        $savant->assign('users', $users);
        $savant->assign('tool_id', $tool_id);
    } else {
        $msg->addError('INVALID_TOOL');
        header('Location: ../index.php');
        exit;
    }
} else {
    $msg->addError('INVALID_TOOL');
    header('Location: ../index.php');
    exit;
}

require(TR_INCLUDE_PATH.'header.inc.php');
$savant->display('oauth/lti_users.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php'); 
?>