<?php
require 'config.php';

// Pre-fill email if coming from sent_otp.php
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-image: url(bg.jpg);
      background-size: cover;
      background-position: center;
      margin: 0;
    }

    .container {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input {
      padding: 8px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[readonly] {
      background-color: #f9f9f9;
      color: #666;
    }

    button {
      margin-top: 15px;
      padding: 10px;
      background-color: black;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: orange;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify Your OTP</h2>
        <form action="verify_otp.php" method="POST">
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required readonly>
            <input type="text" name="otp" placeholder="Enter 6-digit OTP" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>