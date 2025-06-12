<?php

// Include database connection
include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']); // Hash the password for security
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Query to check admin credentials
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
    $select_admin->execute([$name, $pass]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);
    if ($select_admin->rowCount() > 0) {
        // Admin login successful - store admin ID in session
        $_SESSION['admin_id'] = $row['id'];
        header('location:dashboard.php'); // Redirect to dashboard
    } else {
        // Incorrect credentials
        $message[] = 'Incorrect username or password!';
    }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Orbitron", sans-serif;
    }
    body {
      height: 100vh;
      background: linear-gradient(-45deg, #1e1e2f, #162447, #1f4068, #1b1b2f);
      background-size: 600% 600%;
      animation: gradientShift 30s ease infinite;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      perspective: 1500px;
      position: relative;
    }
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .form-container {
      position: relative;
      background: rgba(255, 255, 255, 0.05);
      border: 2px solid rgba(255, 255, 255, 0.15);
      padding: 3rem 2.5rem;
      border-radius: 20px;
      backdrop-filter: blur(20px);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.8);
      transform-style: preserve-3d;
      transform: rotateY(15deg) rotateX(5deg);
      animation: float 6s ease-in-out infinite alternate, spinRotate 40s infinite linear, glowPulse 6s ease-in-out infinite;
      z-index: 2;
    }
    .form-container::before,
    .form-container::after {
      content: '';
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      border-radius: 25px;
      background: linear-gradient(45deg, #00ffc3, #0077ff, #00ffc3);
      background-size: 400% 400%;
      z-index: -1;
      animation: rotateBorder 10s linear infinite;
      filter: blur(6px);
      opacity: 0.8;
    }
    .form-container::after {
      filter: blur(20px);
      opacity: 0.3;
    }
    @keyframes rotateBorder {
      0% { background-position: 0% 50%; transform: rotate(0deg); }
      50% { background-position: 100% 50%; transform: rotate(180deg); }
      100% { background-position: 0% 50%; transform: rotate(360deg); }
    }
    @keyframes float {
      from { transform: translateY(0px) rotateY(15deg) rotateX(5deg); }
      to { transform: translateY(-20px) rotateY(17deg) rotateX(8deg); }
    }
    @keyframes spinRotate {
      0% { transform: rotateY(15deg) rotateX(5deg); }
      50% { transform: rotateY(-15deg) rotateX(-5deg); }
      100% { transform: rotateY(15deg) rotateX(5deg); }
    }
    @keyframes glowPulse {
      0% { box-shadow: 0 0 20px #00ffc3, 0 0 30px #0077ff; }
      50% { box-shadow: 0 0 30px #00ffcc, 0 0 50px #0066ff; }
      100% { box-shadow: 0 0 20px #00ffc3, 0 0 30px #0077ff; }
    }
    .form-container h3 {
      color: #fff;
      font-size: 2.5rem;
      text-shadow: 0 0 10px #00ffc3, 0 0 20px #00ffc3;
      margin-bottom: 2rem;
      text-align: center;
      letter-spacing: 2px;
    }
    .box {
      width: 100%;
      padding: 0.9rem;
      margin-bottom: 1.2rem;
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      background: rgba(255, 255, 255, 0.07);
      color: #fff;
      transition: all 0.3s ease;
      outline: none;
      box-shadow: inset 0 0 5px #00ffc3;
    }
    .box:focus {
      border-color: #00ffc3;
      box-shadow: 0 0 10px #00ffc3, inset 0 0 10px #00ffc3;
      transform: scale(1.05);
    }
    .box::placeholder {
      color: #ccc;
    }
    .btn {
      width: 100%;
      padding: 0.9rem;
      border-radius: 12px;
      border: none;
      background: linear-gradient(45deg, #00ffc3, #0077ff);
      color: #000;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 0 15px #00ffc3;
      font-size: 1.2rem;
    }
    .btn:hover {
      transform: scale(1.08) translateY(-3px);
      box-shadow: 0 0 25px #00ffc3, 0 0 40px #0077ff;
    }
  </style>
</head>
<body>

<?php
if (isset($message)) {
  foreach ($message as $msg) {
    echo '
    <div class="message">
        <span>' . $msg . '</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>';
  }
}
?>

<section class="form-container">
  <form action="" method="post">
    <h3>Admin Login</h3>
    <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" />
    <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" />
    <input type="submit" value="Login Now" class="btn" name="submit" />
  </form>
</section>

</body>
</html> -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
  <link rel="stylesheet" href="./admin_login.css" />
</head>
<body>

<!-- Optional glowing particle background -->
<div class="particles">
  <span style="left:10%; animation-delay: 0s;"></span>
  <span style="left:25%; animation-delay: 2s;"></span>
  <span style="left:50%; animation-delay: 4s;"></span>
  <span style="left:75%; animation-delay: 1s;"></span>
  <span style="left:90%; animation-delay: 3s;"></span>
</div>

<section class="form-container">
  <form action="" method="post">
    <h3>Admin Login</h3>
    <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" />
    <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" />
    <input type="submit" value="Login Now" class="btn" name="submit" />
  </form>
</section>

</body>
</html>

