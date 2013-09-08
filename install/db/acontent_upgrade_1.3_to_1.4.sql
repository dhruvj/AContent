# Create all new tables

# --------------------------------------------------------
# Table structure for table `tool_provider`
CREATE TABLE `tool_provider` (
    `tool_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `shared_secret` varchar(32) NOT NULL,
    `consumer_key` varchar(32) NOT NULL,
    `course_id` int(11) NOT NULL,
    `max_enrollments` int(11) NOT NULL,
    `default_city` varchar(100) DEFAULT NULL,
    `default_country` varchar(100) DEFAULT NULL,
    `enabled` tinyint(1) NOT NULL,
    PRIMARY KEY (`tool_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

# --------------------------------------------------------
# Table structure for table `lti_users`

CREATE TABLE `lti_users` (
    `user_id` int(11) NOT NULL,
    `tool_id` int(11) NOT NULL,
    `user_name` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
# Creating new user group for LTI users

INSERT INTO `user_groups` (`user_group_id`, `title`, `description`, `create_date`) VALUES
(4, 'LTI_user', 'LTI-Provider', now());

# --------------------------------------------------------
# Adding priveleges for the new user group(LTI_user)

INSERT INTO `ac_user_group_privilege` (`user_group_id`, `privilege_id`, `user_requirement`) VALUES (4, 1, 0);



