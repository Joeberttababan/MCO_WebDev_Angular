<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'petpals');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first']);
    $last = trim($_POST['last']);
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];

    if ($pass !== $pass2) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $password_hash = password_hash($pass, PASSWORD_DEFAULT);
            $profile_pic_path = '';
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
              $targetDir = "uploads/profiles/";
                if (!file_exists($targetDir)) {
                  mkdir($targetDir, 0777, true);
                }

                $uniqueName = uniqid() . '_' . basename($_FILES["profile_pic"]["name"]);
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
                  $profile_pic_path = $targetFile;
                } else {
                  $error = "Failed to upload profile picture.";
                }
            } else {
                $error = "Profile picture is required.";
            }

            if (!isset($error)) {
                $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash, profile_pic) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('sssss', $first, $last, $email, $password_hash, $profile_pic_path);
            }

            if ($stmt->execute()) {
                $_SESSION['user_email'] = $email;
                header('Location: petlog.php');
                exit;
            } else {
                $error = "Database error: Could not register.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PetPals - Sign Up</title>
  <link rel="icon" href="assets/logo.png">
  <link rel="stylesheet" href="css/petpals.css" />
</head>
<body>
  <div class="ppContainer">
    <h1>
      <img src="assets/logo.png" alt="PetPals Logo" style="height: 40px;">
      PetPals
    </h1>
    <?php if (!empty($error)) : ?>
      <p style="color:red; font-weight:bold;"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" id="ppSignupForm" autocomplete="off">
      <label for="profile_pic">Upload Profile Picture:</label>
      <input type="file" name="profile_pic" accept="image/*" required/>
      <input type="text" name="first" placeholder="Pet Lover First Name" required />
      <input type="text" name="last" placeholder="Pet Lover Last Name" required />
      <input type="text" name="email" placeholder="Pet Lover Email" required />
      <input type="password" name="pass" placeholder="Secret Paw-word" required />
      <input type="password" name="pass2" placeholder="Re-enter Secret Paw-word" required />
      <button type="submit">Sign Up</button>
    </form>
    <div class="ppOptions">
      <a href="homepage.php">Home Page</a>
      <a href="petlog.php">Log In Instead</a>
    </div>
  </div>
</body>
</html>
