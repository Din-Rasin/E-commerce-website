<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new product added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <style>
   /* Global Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  background: linear-gradient(145deg,rgb(204, 59, 218),rgb(48, 224, 201));
  min-height: 100vh;
  padding: 2rem;
}

/* Headings */
.heading {
  text-align: center;
  font-size: 2.5rem;
  color: #222;
  margin-bottom: 2rem;
  text-transform: uppercase;
  text-shadow: 1px 1px 2px #bbb;
}

/* Forms */
form {
  background: #fff;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  transform: perspective(1000px) rotateX(0deg);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
form:hover {
  transform: perspective(1000px) rotateX(3deg);
  box-shadow: 0 25px 50px rgba(0,0,0,0.2);
}

.inputBox {
  margin-bottom: 1.5rem;
}
.inputBox span {
  display: block;
  margin-bottom: 0.5rem;
  color: #444;
  font-weight: 600;
}
.inputBox input,
.inputBox textarea {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  border: 1px solid #ccc;
  background:rgb(13, 64, 206);
  box-shadow: inset 2px 2px 5px rgba(0,0,0,0.05);
  transition: all 0.2s ease;
}
.inputBox input:focus,
.inputBox textarea:focus {
  outline: none;
  border-color: #6c63ff;
  box-shadow: 0 0 5px #6c63ff70;
}

/* Submit Button */
.btn {
  display: inline-block;
  padding: 0.75rem 2rem;
  background: #6c63ff;
  color: #fff;
  font-weight: 600;
  border: none;
  border-radius: 0.75rem;
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
  transition: all 0.3s ease;
  cursor: pointer;
}
.btn:hover {
  background: #4e45d5;
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 12px 24px rgba(0,0,0,0.25);
}

/* Product Cards */
.show-products .box-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}

.show-products .box {
  background: #fff;
  border-radius: 1.5rem;
  overflow: hidden;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transform-style: preserve-3d;
  transition: all 0.3s ease;
}
.show-products .box:hover {
  transform: perspective(1000px) rotateY(3deg);
  box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.show-products .box img {
  max-width: 100%;
  height: auto;
  border-radius: 1rem;
  margin-bottom: 1rem;
}

.show-products .name {
  font-size: 1.4rem;
  color: #333;
  font-weight: 600;
  margin-bottom: 0.5rem;
}
.show-products .price {
  color: #6c63ff;
  font-weight: bold;
  margin-bottom: 0.5rem;
}
.show-products .details {
  font-size: 0.9rem;
  color: #555;
  margin-bottom: 1rem;
}

.flex-btn {
  display: flex;
  justify-content: center;
  gap: 1rem;
}
.option-btn,
.delete-btn {
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
  text-decoration: none;
  color: white;
  transition: transform 0.2s;
}
.option-btn {
  background: #28a745;
}
.delete-btn {
  background: #dc3545;
}
.option-btn:hover,
.delete-btn:hover {
  transform: scale(1.05) translateY(-2px);
}

  </style>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

                  
     
<section class="add-products">

<div class="main-content">
        <section class="dashboard">
            <h1 class="heading">add product</h1>
            <div class="box-container">
                <div class="box">

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>product name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>product price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
        <div class="inputBox">
            <span>image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>product details (required)</span>
            <textarea name="details" placeholder="enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">Products Lists</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
      <div class="details"><span><?= $fetch_products['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>
   
   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</div>
            </div>
        </section>
    </div>
</body>
</html>