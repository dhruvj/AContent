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
require_once(TR_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');

class LTIusersDAO extends UsersDAO {

    /**
     * Validate a user of consumer
     * @access  public
     * @param   Login and password 
     * @return  return row of user if successfully else false
     * @author  Dhruv Jagetiya
     */
    
    public function Validate($login, $pass) {
        $sql = "SELECT * from ".TABLE_PREFIX."users where login='".$login."' AND password='".$pass."'";
        return $this->execute($sql);
    }
	/**
	 * Create new user
	 * @access  public
	 * @param   user_group_id: user group ID (1 [admin] or 2 [user])
	 *          login: login name
	 *          pwd: password
	 *          email: email
	 *          first_name: first name
	 *          last_name: last name
	 * @return  user id, if successful
	 *          false , if unsuccessful
	 * @author  Dhruv Jagetiya
	 */
    public function Create($user_group_id, $login, $pwd, $email, $first_name, $last_name, 
                           $is_author, $organization, $phone, $address, $city,
                           $province, $country, $postal_code, $status)
    {
        global $addslashes;

        /* email check */
        $login = $addslashes(strtolower(trim($login)));
        $email = $addslashes(trim($email));
        $first_name = $addslashes(str_replace('<', '', trim($first_name)));
        $last_name = $addslashes(str_replace('<', '', trim($last_name)));
        $organization = $addslashes(trim($organization));
        $phone = $addslashes(trim($phone));
        $address = $addslashes(trim($address));
        $city = $addslashes(trim($city));
        $province = $addslashes(trim($province));
        $country = $addslashes(trim($country));
        $postal_code = $addslashes(trim($postal_code));
        $sql = "INSERT INTO ".TABLE_PREFIX."users
                          (login,
                           password,
                           user_group_id,
                           first_name,
                           last_name,
                           email,
                           is_author,
                           organization,
                           phone,
                           address,
                           city,
                           province,
                           country,
                           postal_code,
                           web_service_id,
                           status,
                           create_date
                           )
                   VALUES ('".$login."',
                           '".$pwd."',
                           ".$user_group_id.",
                           '".$first_name."',
                           '".$last_name."', 
                           '".$email."',
                           ".$is_author.",
                           '".$organization."',
                           '".$phone."',
                           '".$address."',
                           '".$city."',
                           '".$province."',
                           '".$country."',
                           '".$postal_code."',
                           '".Utility::getRandomStr(32)."',
                           ".$status.", 
                           now())";

        if (!$this->execute($sql)) {
            $msg->addError('DB_NOT_UPDATED');
            return false;
        } else {
            return mysql_insert_id();
        }
    }

    /**
     * Delete a particular user.
     * @access  public
     * @param   user_id
     * @author  Dhruv Jagetiya
     */
    public function Delete($user_id) {
        parent::Delete($user_id);
        $this->unroll($user_id);
        $sql = "DELETE FROM ".TABLE_PREFIX."lti_users WHERE user_id = ".$user_id;
        $this->execute($sql);
    }
    
     /**
     * Check if a tool is assigned to user.
     * @access  public
     * @param   user_id, tool_id
     * @return  true if successful else false
     * @author  Dhruv Jagetiya
     */
    public function istoolAssignedToUser($user_id, $tool_id) {
        $sql = "SELECT * from ".TABLE_PREFIX."lti_users where user_id=".$user_id." AND tool_id=".$tool_id;
        return $this->execute($sql);
    }
     /**
     * Assign a tool to lti user.
     * @access  public
     * @param   user_id, tool_id, user_name
     * @return  true if successful else false
     * @author  Dhruv Jagetiya
     */
    public function assignToolToUser($user_id, $tool_id, $user_name) {
        $sql = "INSERT INTO ".TABLE_PREFIX."lti_users VALUES(".$user_id.",".$tool_id.",'".$user_name."')";
        return $this->execute($sql);
    }
     /**
     * Number of enrolled users in a tool.
     * @access  public
     * @param   tool_id
     * @return  Number of enrolled users in a tool
     * @author  Dhruv Jagetiya
     */
    public function enrollments($tool_id) {
        $sql = "SELECT count(*) from ".TABLE_PREFIX."lti_users where tool_id = ".$tool_id;
        $array = ($this->execute($sql));
        return $array[0]['count(*)'];
    }
    
    /**
     * Enroll a user to course.
     * @access  public
     * @param   user_id, course_id, role
     * @return  True if successful else false
     * @author  Dhruv Jagetiya
     */
    public function enroll($user_id, $course_id, $role) {
        $sql = "INSERT into ".TABLE_PREFIX."user_courses VALUES(".$user_id.", ".$course_id.", ".$role.", '')";
        return ($this->execute($sql));
    }
    /**
     * Unroll a user from a course.
     * @access  public
     * @param   user_id
     * @return  True if successful else false
     * @author  Dhruv Jagetiya
     */
    public function unroll($user_id) {
        $sql = "Delete from ".TABLE_PREFIX."user_courses where user_id = ".$user_id;
        return ($this->execute($sql));
    }
    /**
     * Check if a user is enrolled to any course.
     * @access  public
     * @param   user_id
     * @return  course id if user is enrolled else false
     * @author  Dhruv Jagetiya
     */
    public function isEnrolled($user_id) {
        $sql = "SELECT course_id from ".TABLE_PREFIX."user_courses where user_id = ".$user_id;
        $array = ($this->execute($sql));
        return $array ? $array[0]['course_id'] : false;
    }
    /**
     * Get all users assigned to a tool.
     * @access  public
     * @param   tool_id
     * @return  All the users assigned to that tool
     * @author  Dhruv Jagetiya
     */
    public function getUserByToolId($tool_id) {
        $sql = "SELECT * from ".TABLE_PREFIX."lti_users l, ".TABLE_PREFIX."users u where l.tool_id = ".$tool_id. " AND l.user_id = u.user_id";
        return ($this->execute($sql));
    }
    /**
     * Check if the user is LTI user or not.
     * @access  public
     * @param   user_id
     * @return  True if user_id belongs to a LTI user
     * @author  Dhruv Jagetiya
     */
    public function isLTIuser($user_id) {
        $sql = "SELECT * from ".TABLE_PREFIX."lti_users l, ".TABLE_PREFIX."users u where l.user_id = u.user_id  AND l.user_id = ".$user_id;
        return ($this->execute($sql));
    }
    /**
     * Disable a LTI user
     * @access  public
     * @param   user_id
     * @author  Dhruv Jagetiya
     */
    public function Disable($user_id) {
        parent::setStatus($user_id, 0);
    }
    /**
     * Enable a LTI user
     * @access  public
     * @param   user_id
     * @author  Dhruv Jagetiya
     */
    public function Enable($user_id) {
        parent::setStatus($user_id, 1);
    }
    /**
     * Check if a LTI user is enabled
     * @access  public
     * @param   login
     * @author  Dhruv Jagetiya
     */
    public function isEnabled($login) {
        $sql = "Select status from ".TABLE_PREFIX."users where login = '".$login."'";
        $isEnabled = $this->execute($sql);
        return ($isEnabled[0]['status']);
    }

}

?>