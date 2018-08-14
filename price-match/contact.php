<?php
if($_SERVER['REQUEST_METHOD']!='POST') {  // REQUIRE POST OR DIE
	die('The request sent was invalid. Please try again, or contact the webmaster immediately! Error Code: 0001');
}
else {
	if (isset($_POST['fname'])) {$fname = clean($_POST['fname']);}
	if (isset($_POST['lname'])) {$lname = clean($_POST['lname']);}
	if (isset($_POST['inquiry'])) {$inquiry = clean($_POST['inquiry']);}
	if (isset($_POST['email'])) {$email = clean($_POST['email']);}
	if (isset($_POST['comments'])) {$comments = clean($_POST['comments']);}

	$to = $_POST['tomail'];
	$from = "From: contact_request@pospaper.com";
	$subject = "Contact form submission";
	$ip = $_SERVER['REMOTE_ADDR'];
	$hostaddress = gethostbyaddr($ip);
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$referred = $_SERVER['HTTP_REFERER']; // a quirky spelling mistake that stuck in php
	$email_message = "First Name: $fname\n" .
									"Last Name: $lname\n" .
									"Related to: $inquiry\n" .
									"Email: $email\n" .
									"Comments: $comments\n";

	mail($to, $subject, $email_message, $from);

	if (isset($_POST['returnurl'])) {
		$header = $_POST['returnurl'];
		header("Location: $header");
	}
}

function clean($string) {
  $string = stripslashes($string);
  $string = htmlentities($string);
  $string = strip_tags($string);
  return $string;
}

?>