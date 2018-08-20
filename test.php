<?php
$to      = 'bliss.bhavar@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: bliss.akash@gmail.com' . "\r\n" .
    'Reply-To: bliss.jaimin@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?> 