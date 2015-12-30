<?php
$subjectPrefix = '[Pugbike message recieved]';
$emailTo = '<kaydwithers@gmail.com>';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name     = stripslashes(trim($_POST['name']));
  $email    = stripslashes(trim($_POST['email']));
  $message  = stripslashes(trim($_POST['message']));
  $pattern  = '/[\r\n]|Content-Type:|Bcc:|Cc:/i';

  if (preg_match($pattern, $name) || preg_match($pattern, $email) || preg_match($pattern, $subject)) {
    die("Header injection detected");
  }

  $emailIsValid = preg_match('/^[^0-9][A-z0-9._%+-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email);

   // Honeypot verify email
  if(strlen($_POST['verifyEmail']) > 0){
    header('location: {google.com}');
    die();
  }
  if((strlen($name) > 0) && (strlen($email) > 0) && $emailIsValid && (strlen($message) > 0)) {

    $subject = "$subjectPrefix $subject";
    $body = "Name: $name <br /> Email: $email <br /> Message: $message";

    $headers  = 'MIME-Version: 1.1' . PHP_EOL;
    $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;
    $headers .= "From: $name <$email>" . PHP_EOL;
    $headers .= "Return-Path: $emailTo" . PHP_EOL;
    $headers .= "Reply-To: $email" . PHP_EOL;
    $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;

    mail($emailTo, $subject, $body, $headers);
    

  } else {
    http_response_code(400);
  }
}
?>