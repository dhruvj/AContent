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
if (!defined('TR_INCLUDE_PATH')) exit;

require_once(TR_INCLUDE_PATH. 'classes/DAO/DAO.class.php');
require_once(TR_INCLUDE_PATH. 'classes/DAO/LTIusersDAO.class.php');

class ToolProviderDAO extends DAO {
    
    /**
     * Create a new tool for consumer
     * @access  public
     * @param   array with value of following key 
     *          user_id
     *          shared_secret
     *          consumer_key
     *          course_id
     *          max_enrollments
     *          default_city
     *          default_country
     *          enabled
     * @return  return true if successfully created a new row else false
     * @author  Dhruv Jagetiya
     */
    
    public function Create($tool) {
        $sql = "INSERT INTO ".TABLE_PREFIX."tool_provider(
                user_id,
                shared_secret,
                consumer_key,
                course_id,
                max_enrollments,
                default_city,
                default_country,
                enabled
                )
                VALUES (
                ".$tool['user_id'].",
                '".$tool['shared_secret']."',
                '".$tool['consumer_key']."',
                ".$tool['course_id'].",
                ".$tool['max_enrollments'].",
                '".$tool['default_city']."',
                '".$tool['default_country']."',
                ".$tool['enabled'].
                ")";
        return $this->execute($sql);
    }
    /**
     * Update a tool for consumer
     * @access  public
     * @param   array with value of following key 
     *          user_id
     *          shared_secret
     *          consumer_key
     *          course_id
     *          max_enrollments
     *          default_city
     *          default_country
     *          enabled
     * @return  return true if successfully updated a row else false
     * @author  Dhruv Jagetiya
     */
    
    public function Update($tool, $tool_id) {
        $sql = "UPDATE ".TABLE_PREFIX."tool_provider
                SET
                shared_secret='".$tool['shared_secret']."',
                consumer_key='".$tool['consumer_key']."',
                course_id=".$tool['course_id'].",
                max_enrollments=".$tool['max_enrollments'].",
                default_city='".$tool['default_city']."',
                default_country='".$tool['default_country']."',
                enabled=".$tool['enabled'].
                " WHERE
                tool_id=".$tool_id;
        return $this->execute($sql);
    }
     /**
     * Delete a tool
     * @access  public
     * @param   tool_id
     * @return  true if successful else false
     * @author  Dhruv Jagetiya
     */
    public function Delete($tool_id) {
        $ltiuser = new LTIusersDAO();
        $sql = "SELECT user_id from ".TABLE_PREFIX."lti_users WHERE tool_id = ".$tool_id;
        $users = $this->execute($sql);
        if(!empty($users)) {
            foreach ($users as $user) {
                $ltiuser->Delete($user['user_id']);
            }
        }
        //Remove the entry of the tool
        $sql = "DELETE FROM ".TABLE_PREFIX."tool_provider
                WHERE tool_id=".$tool_id;
        return $this->execute($sql);
    }
     /**
     * Return tools created by a particular user with user_id as $user_id
     * @access  public
     * @param   user_id
     * @return  rows of tool created by a user
     * @author  Dhruv Jagetiya
     */
    public function getToolByUserId($user_id) {
        $sql = "SELECT * FROM ".TABLE_PREFIX."tool_provider t
                WHERE t.user_id=".$user_id;      
        return $this->execute($sql);
    }
     /**
     * checks whether a tool is authored by user or not
     * @access  public
     * @param   tool_id
     * @return  row of a tool with id as $tool_id
     * @author  Dhruv Jagetiya
     */
    public function getToolByToolId($tool_id) {
        $sql = "SELECT * FROM ".TABLE_PREFIX."tool_provider t
                WHERE t.tool_id=".$tool_id;      
        return $this->execute($sql);
    }
     /**
     * checks whether a tool is authored by user or not
     * @access  public
     * @param   user_id
     * @return  true if tool is authored by $user_id else false
     * @author  Dhruv Jagetiya
     */
    public function isToolByUser($user_id, $tool_id) {
        $tools = $this->getToolByUserId($user_id);
        foreach ($tools as $tool) {
            if ($tool['tool_id'] == $tool_id)
                return true;
        }
        return false;
    }
     /**
     * Return tool by consumer key
     * @access  public
     * @param   consumer key
     * @return  row of a tool with consumer key as $consumer_key
     * @author  Dhruv Jagetiya
     */
    public function getToolByConsumerKey($consumer_key) {
        $sql = "SELECT * FROM ".TABLE_PREFIX."tool_provider t
                WHERE t.consumer_key='".$consumer_key."'";      
        return $this->execute($sql);
    }
}

?>