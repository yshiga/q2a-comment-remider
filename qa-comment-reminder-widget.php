<?php
class qa_comment_reminder_widget {

	function allow_template($template) {
		return true;
	}

	function allow_region($region) {
		return true;
	}
	
	function output_widget($region, $place, $themeobject, $template, $request, $qa_content) {
		$userid = qa_get_logged_in_userid();
		if(!isset($userid)){
			return;
		}
	
		$list = getNoCommentAnswerQuestion($userid);

		echo '<h2>回答へコメントを返しましょう</h2>';
		echo '<p>あなたの質問に回答してくれた方へ、コメントでお礼を投稿しましょう。以下の質問に寄せられた回答には、まだコメントが返されていないものがあります。</p>';

		echo '<ul>';
		foreach($list as $item) {
			echo '<li><a href="' . qa_opt('site_url') . $item['question_id'] . '#'. $item['answer_id'] . '" >'. $item['question'] . '</a></li>';
		}
		echo '</ul>';
		
	}
}
