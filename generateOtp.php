<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php'; // Path to PHPMailer

session_start();
$otp = rand(100000, 999999);
$email = $_POST['email'];
$_SESSION['otp'] = $otp;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Use your SMTP host
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';
    $mail->Password = 'your_email_password'; // or app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'YourApp');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is $otp";

    $mail->send();
    echo 'OTP sent';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
