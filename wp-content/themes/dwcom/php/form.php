<?php
	
	require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
	
	$url = $_SERVER['SERVER_NAME'];
	
	$fromMail = 'res@'. $url;
	$fromName = 'Информатор';
	
	$emailTo = get_field('email','option');
	$subject = 'Новая Заявка с сайта';
	$subject = '=?utf-8?b?'. base64_encode($subject) .'?=';
	$headers = "Content-type: text/html; charset=\"utf-8\"\r\n";
	$headers .= "From: ". $fromName ." <". $fromMail ."> \r\n";
	
	$result = [];
	
	foreach ( $_POST as $key => $item ) {
		
		if ( is_array($item) ) {
			$result[] = '<strong>'. $key .':</strong> ' .implode('; ', $item) .'<br/>';
		} else {
			$result[] = '<strong>'. $key .':</strong> '. $item .'<br/>';
		}
	}
	
	$body = implode('', $result);
	
	$mail = mail($emailTo, $subject, $body, $headers, '-f'. $fromMail );
	
	echo 'ok';