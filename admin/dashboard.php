<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <style>
      :root {
  --main-color: #16a085;
  --secondary-color: #1abc9c;
  --orange: #f39c12;
  --red: #e74c3c;
  --black: #2c3e50;
  --white: #ffffff;
  --light-color: #bdc3c7;
  --light-bg: #ecf0f3;
  --dark-bg: #1e272e;
  --border: 0.2rem solid rgba(0, 0, 0, 0.1);
  --box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
}

body {
  background: linear-gradient(135deg, #1e272e, #2f3640);
  color: var(--white);
  perspective: 1500px;
}

.heading {
  font-size: 4rem;
  text-align: center;
  margin-bottom: 2rem;
  text-transform: uppercase;
  color: var(--white);
  letter-spacing: 0.15rem;
  transform: rotateX(5deg);
  animation: fadeInUp 0.8s ease-in-out;
}

.btn,
.option-btn,
.delete-btn {
  padding: 1.2rem 3rem;
  font-size: 1.6rem;
  border-radius: 1rem;
  color: var(--white);
  background: var(--main-color);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  box-shadow: 0 1.5rem 2rem rgba(22, 160, 133, 0.3);
  transform-style: preserve-3d;
}

.btn:hover,
.option-btn:hover,
.delete-btn:hover {
  transform: scale(1.08) rotateX(8deg) rotateY(5deg);
  box-shadow: 0 2rem 4rem rgba(26, 188, 156, 0.5);
}

.option-btn {
  background: var(--orange);
}

.delete-btn {
  background: var(--red);
}

.box {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 1rem;
  padding: 2rem;
  transition: var(--transition);
  box-shadow: 0 1.5rem 2.5rem rgba(0, 0, 0, 0.3);
  transform-style: preserve-3d;
  backdrop-filter: blur(10px);
}

.box:hover {
  transform: rotateY(8deg) rotateX(4deg) scale(1.02);
  box-shadow: 0 2rem 4rem rgba(0, 0, 0, 0.4);
}

.sidebar {
  background: #2c3e50;
  color: var(--white);
  padding: 2rem 1rem;
  border-right: 2px solid rgba(255, 255, 255, 0.1);
}

.sidebar a {
  color: var(--orange);
  font-weight: bold;
  margin: 1rem 0;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: transform 0.3s ease;
}

.sidebar a:hover {
  transform: translateX(10px) scale(1.05);
  color: var(--main-color);
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(28rem, 1fr));
  gap: 2rem;
}

.profile-card {
  transform: rotateY(8deg);
  transition: var(--transition);
  box-shadow: 0 1.5rem 2.5rem rgba(0, 0, 0, 0.2);
  background: linear-gradient(145deg, #1f2c35, #2b3d4b);
  border-radius: 1rem;
  padding: 2rem;
}

.profile-card:hover {
  transform: rotateY(0deg) scale(1.07);
  box-shadow: 0 2.5rem 3.5rem rgba(0, 0, 0, 0.35);
}

@keyframes fadeInUp {
  0% {
    transform: translateY(30px);
    opacity: 0;
  }
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}

@media (max-width: 768px) {
  html {
    font-size: 55%;
  }

  .btn {
    width: 100%;
  }

  .grid {
    grid-template-columns: 1fr;
  }
}

   </style>
   <link rel="stylesheet" href="../css/admin_style.css">

   
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

<div class="main-content">
        <section class="dashboard">
            <h1 class="heading">Dashboard</h1>
            <div class="box-container">
                <div class="box">

   <div class="box-container">

      <div class="box">
         <h3>welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
      </div>

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if($select_pendings->rowCount() > 0){
               while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
         ?>
         <h3><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
         <p>total pendings</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
         ?>
         <h3><span>$</span><?= $total_completes; ?><span>/-</span></h3>
         <p>completed orders</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
         ?>
         <h3><?= $number_of_orders; ?></h3>
         <p>orders placed</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>products added</p>
         <a href="products.php" class="btn">see products</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>normal users</p>
         <a href="users_accounts.php" class="btn">see users</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>admin users</p>
         <a href="admin_accounts.php" class="btn">see admins</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>new messages</p>
         <a href="messages.php" class="btn">see messages</a>
      </div>

   </div>

</section>

              </div>
            </div>
        </section>
    </div>
</body>
</html>