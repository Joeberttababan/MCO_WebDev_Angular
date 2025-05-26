<?php
require 'config.php';

// Check if user is verified
if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    $check_sql = "SELECT is_verified FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0 && $result->fetch_assoc()['is_verified'] == 1) {
        // User is verified - show login form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login</title>
            <style>
                body {
      font-family: Arial, sans-serif;
      height: 100vh;
      margin: 0;
      background-image: url(bg.jpg);
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      display: flex;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 800px;
      width: 100%;
    }

    .sidebar {
      background-color: #f4f4f4;
      padding: 30px;
      width: 40%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border-right: 1px solid #ddd;
    }

    .sidebar h2 {
      margin: 10px 0;
    }

    .sidebar p {
      text-align: center;
      color: #333;
    }

    .form-container {
      padding: 30px;
      width: 60%;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-top: 10px;
    }

    input {
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
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

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .sidebar,
      .form-container {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #ddd;
      }
    }
            </style>
        </head>
        <body>
        <div class="container">
        <div class="sidebar">
            <h2>Welcome!</h2>
            <p>Your email <?php echo htmlspecialchars($email); ?> has been verified.</p>
        </div>

        <div class="form-container">
    <form>
      <h2>Login</h2>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Submit</button>
    </form>
  </div>
        </body>
        </html>
        <?php
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>