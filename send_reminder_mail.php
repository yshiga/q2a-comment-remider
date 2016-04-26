<?php
if (!defined('QA_VERSION')) { 
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
   require_once QA_INCLUDE_DIR.'app/emails.php';
}

$tmp = getNoCommentAnswerQuestion(null, 6);

// TODO bad performance
$noCommentQuestions = array();
foreach($tmp as $question){
	$userid = $question['questioner_id'];

	if(empty($userid) != true) {
		$noCommentQuestions[$userid][] = $question;
	} else {

	}
}

foreach($noCommentQuestions as $userid => $items) {

		if(count($items) < 2) {
                	continue;
                }

		$params = array();
	 	$body = '';
		$user = getUserInfo($userid);

		$body = $user[0]['handle'] . 'さん、いつも' . qa_opt('site_title') . 'に質問を投稿してくださりありがとうございます。';
		$body .= '次の質問は回答が寄せられていますが、コメントが返されていません。'; 
		$body .= '回答してくれた方のためにも、コメントでお礼をしてください。' . "\n";
		$body .= "\n";
		for($i=0;$i<count($items);$i++){
			$item = $items[$i];
  
			$body .= "・" .  $item['question'] . "\n";
			$body .= qa_opt('site_url') . $item['question_id'] . '#'. $item['answer_id'];
			$body .= "\n";
			$answer = strip_tags($item['answer']);
			$answer = mb_strimwidth($answer, 0, 100, "...");;
			$answer = str_replace(array("\r", "\n"), '', $answer);
			$body .= $answer . "\n";
			$body .= "\n";
		}

		$body .= 'これからもご質問お待ちしております。';

		$params['fromemail'] = qa_opt('from_email');
		$params['fromname'] = qa_opt('site_title');

		$params['subject'] = '【' . qa_opt('site_title') . '】回答にコメントを返してください';
		$params['body'] = $body;
		$params['toname'] = $user[0]['handle'];
		$params['toemail'] = $user[0]['email'];
		$params['html'] = false;

		sendEmail($params);
}

function getUserInfo($userid) {
	$sql = 'select email,handle from qa_users where userid=' . $userid;
	$result = qa_db_query_sub($sql); 
	return qa_db_read_all_assoc($result);
}

function sendEmail($params){
	qa_send_email($params);
}
