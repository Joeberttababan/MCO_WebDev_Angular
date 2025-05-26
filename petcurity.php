<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer path (adjust if needed)

// Handle OTP send
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_otp'])) {
    $email = $_POST['email'] ?? '';
    if (!empty($email)) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // replace
            $mail->SMTPAuth = true;
            $mail->Username = ''; // replace
            $mail->Password = ''; // replace
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('joeberttababan19@gmail.com', 'OTP Verification');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<h2>Your OTP is:</h2><h3>$otp</h3>";

            $mail->send();
            $message = "✅ OTP sent to $email.";
            $messageClass = "success";
        } catch (Exception $e) {
            $message = "❌ Failed to send OTP.";
            $messageClass = "error";
        }
    }
}

// Handle OTP verification logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $enteredOtp = $_POST['otp'] ?? '';
    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
        $message = "✅ OTP verified successfully!";
        $messageClass = "success";
    } else {
        $message = "❌ Invalid OTP. Please try again.";
        $messageClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url(assets/petp.jpg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .otp-card {
            background: #D37506;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .otp-card h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #E3E8E9;
        }

        .otp-card input[type="text"],
        .otp-card input[type="email"] {
            width: 50%;
            padding: 14px;
            font-size: 18px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #ddd;
            outline: none;
            letter-spacing: 2px;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .otp-card input[type="submit"] {
            width: 100%;
            margin-top: 10px;
            background: #9F2B00;
            color: white;
            padding: 14px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .otp-card input[type="submit"]:hover {
            background: #B7AC44;
            opacity: 1;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="otp-card">
    <h2>OTP-AWS</h2>

    <!-- Form to request OTP -->
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter email" required>
        <input type="submit" name="send_otp" value="Send OTP">
    </form>

    <!-- Form to verify OTP -->
    <form method="POST" action="">
        <input type="text" name="otp" maxlength="6" placeholder="******" required>
        <input type="submit" name="verify_otp" value="One Time Paw">
    </form>

    <?php if (isset($message)): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
