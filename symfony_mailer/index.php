<?php

require 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$fromDsn = "smtp://developmentsmtp2017@gmail.com:efqyndqlhzlhfubm@smtp.gmail.com:587";

$transport = Transport::fromDsn($fromDsn);
$mailer = new Mailer($transport);

$fromEmail = "developmentsmtp2017@gmail.com";
$toEmail = "keyur.r.bb@gmail.com";
$subject = "Time for Symfony Mailer!";
$text = "Sending emails is fun again!";
$html = "<p>See Twig integration for better HTML integration!</p>";

try{
	$email = (new Email())
	    ->from($fromEmail)
	    ->to($toEmail)
	    ->subject($subject)
	    ->text($text)
	    ->html($html);

	$r = $mailer->send($email);

	var_dump($r);
}
catch(Exception $e){
	var_dump($e->getMessage());
}

exit;
?>