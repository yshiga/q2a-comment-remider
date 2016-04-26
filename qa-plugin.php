<?php

/*
	Plugin Name: Comment Reminder
	Plugin URI: 
	Plugin Description: remind comment
	Plugin Version: 0.1
	Plugin Date: 2016-03-28
	Plugin Author:
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_module(
	'widget',
	'qa-comment-reminder-widget.php',
	'qa_comment_reminder_widget',
	'Comment Reminder' 
);

function getNoCommentAnswerQuestion($userid=null, $month=null){
	$sql = 'select t1.userid as questioner_id, t1.title as question, t2.parentid as question_id, t2.content as answer, t2.postid as answer_id, count(t3.postid) as c_count from qa_posts t2  left join qa_posts t3 on t2.postid = t3.parentid join qa_posts t1 on t2.parentid = t1.postid where t1.type="Q" AND t2.type="A" ';

	if(isset($month)) {
		$sql .= ' AND t2.created > DATE_SUB(NOW(), INTERVAL ' . $month  . ' MONTH)';
 	} 

	$sql .= ' group by t2.postid having c_count = 0';

	if(isset($userid)) {
		$sql .= ' AND questioner_id =' . $userid . ' order by question_id';
 	} else {
		$sql .= ' order by questioner_id';
	}

	$result = qa_db_query_sub($sql); 
	return qa_db_read_all_assoc($result);
}

