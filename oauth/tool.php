<?php 
// Load up the Basic LTI Support code
define('TR_INCLUDE_PATH', '../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/ToolProviderDAO.class.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/LTIusersDAO.class.php');
require_once(TR_INCLUDE_PATH.'classes/DAO/UserCoursesDAO.class.php');
require_once 'ims-blti/blti.php';

ini_set("display_errors", 1);

header('Content-Type: text/html; charset=utf-8'); 

$parm	= array('table'			=> TABLE_PREFIX.'tool_provider',
				'key_column'	=> 'consumer_key',
				'secret_column'	=> 'shared_secret',
				);

// Initialize, all secrets are 'secret', do not set session, and do not redirect
//$context = new BLTI("secret", false, false);
$context = new BLTI($parm, false, false);

?>
<?php
//print_r($_POST)."<br>";
if ( $context->valid ) {
    //get tool by consumer key
    $toolprovider = new ToolProviderDAO();
    $ltiuser = new LTIusersDAO();
    $tool = $toolprovider->getToolByConsumerKey($context->getConsumerKey());
    // check if tool is enabled
    if (!$tool[0]['enabled']) {
        $msg->addError('TOOL_NOT_ENABLED');
        header("Location: ../index.php");
        exit;
    }
    $usercourse = new UserCoursesDAO();
    $course = $usercourse->getCourseByToolId($tool[0]['tool_id']);
    $authorid = $course[0]['user_id'];
    $author = $ltiuser->getUserByID($authorid);
    if ($context->isInstructor() && $context->getUserEmail() == $author['email']) {
        $_SESSION['user_id'] = $author['user_id'];
    } else {
        $login = 'ltiprovider:'.$tool[0]['tool_id'].':'.$context->getUserLKey();               //todo:  may have to change
        $pwd = 'ltiprovider:'.$tool[0]['tool_id'].':'.$context->getUserLKey();
        if (!$ltiuser->Validate($login, $pwd)) {
            if ($ltiuser->enrollments($tool[0]['tool_id']) == $tool[0]['max_enrollments']) {
                $msg->addError('MAX_ENROLLMENTS_CROSSED');
                header("Location: ../index.php");
                exit;
            }
            $user_group_id = 4;
            $email = lti.":".($context->getUserEmail() ? $context->getUserEmail(): $login);
            $first_name = 'LTI';
            $last_name = 'user';
            $role = 0;
            $organization = 'None';
            $phone = 'None';
            $address = 'None';
            $city = $tool[0]['default_city'] ? $tool[0]['default_city'] : 'None';
            $province = 'None';
            $country = $tool[0]['default_country'] ? $tool[0]['default_country'] : 'None';
            $postal_code = 'None';
            $status = 1;
            $ltiuser->Create($user_group_id, 
                             $login, 
                             $pwd,
                             $email,
                             $first_name, 
                             $last_name, 
                             $role, 
                             $organization,
                             $phone,
                             $address,
                             $city,
                             $province,
                             $country,
                             $postal_code,
                             $status
                            );
        } else {
            if (!$ltiuser->isEnabled($login)) {
                $msg->addError('ACCOUNT_DISABLED');
                header("Location: ../index.php");
                exit;
            }
        }
        $user = $ltiuser->Validate($login, $pwd);                  //Check if the user created!
        //check if the user is assigned to any tool
        if (!$ltiuser->istoolAssignedToUser($user[0]['user_id'], $tool[0]['tool_id'])) {
            $ltiuser->assignToolToUser($user[0]['user_id'], $tool[0]['tool_id'], mysql_real_escape_string($context->getUserName()));
        }
        //enroll the user in course
        if (!$ltiuser->isEnrolled($user[0]['user_id'])) {
            $ltiuser->enroll($user[0]['user_id'], $tool[0]['course_id'], 2);
        }
        //login!
        $ltiuser->setLastLogin($user[0]['user_id']);
        $_SESSION['user_id'] = $user[0]['user_id'];
    }
    header("Location: ../home/course/outline.php?_course_id=".$tool[0]['course_id']);
    exit;
} else {
    print "<p style=\"color:red\">Could not establish context: ".$context->message."<p>\n";
}

?>
