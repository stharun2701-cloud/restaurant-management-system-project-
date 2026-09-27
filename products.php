<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
};

if(isset($_POST['add_product'])){

   $name = htmlspecialchars($_POST['name']);
   $price = htmlspecialchars($_POST['price']);
   $category = htmlspecialchars($_POST['category']);
   $calories = htmlspecialchars($_POST['calories']); 

   $image = $_FILES['image']['name'];
   $image = htmlspecialchars($image);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exists!';
   }else{
      if($image_size > 2000000){
         $message[] = 'image size is too large';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image, calories) VALUES(?,?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image, $calories]);
         $message[] = 'new product added!';
      }
   }
}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   
   if($fetch_delete_image){
      unlink('../uploaded_img/'.$fetch_delete_image['image']);
      $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
      $delete_product->execute([$delete_id]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
      $delete_cart->execute([$delete_id]);
   }
   header('location:products.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products | Cuisine Cloud</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --dark-bg: #1e272e;
         --glass-bg: rgba(255, 255, 255, 0.95);
      }

      body {
         background: linear-gradient(rgba(30, 39, 46, 0.85), rgba(30, 39, 46, 0.85)), url('../images/admin-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
      }

      /* --- Form Styling --- */
      .add-products form {
         max-width: 50rem;
         margin: 0 auto;
         background: var(--glass-bg);
         backdrop-filter: blur(10px);
         padding: 3rem;
         border-radius: 2rem;
         box-shadow: 0 15px 35px rgba(0,0,0,0.3);
         border: 1px solid rgba(255,255,255,0.3);
         text-align: center;
      }

      .add-products form h3 {
         font-size: 2.5rem;
         color: var(--dark-bg);
         margin-bottom: 2rem;
         text-transform: uppercase;
         font-weight: 800;
      }

      .add-products form .box {
         width: 100%;
         background-color: #f5f6fa;
         border-radius: 1rem;
         padding: 1.4rem;
         font-size: 1.7rem;
         color: var(--dark-bg);
         margin: 1rem 0;
         border: 2px solid transparent;
         transition: 0.3s;
      }

      .add-products form .box:focus {
         border-color: var(--primary-color);
         background-color: #fff;
      }

      /* --- Product Grid Styling --- */
      .show-products .box-container {
         display: grid;
         grid-template-columns: repeat(3, 1fr); /* Matching dashboard 3-column layout */
         gap: 2.5rem;
         max-width: 1200px;
         margin: 3rem auto;
         padding: 0 2rem;
      }

      .show-products .box-container .box {
         background: var(--glass-bg);
         backdrop-filter: blur(10px);
         padding: 2rem;
         border-radius: 2rem;
         text-align: center;
         border: 1px solid rgba(255,255,255,0.3);
         box-shadow: 0 10px 25px rgba(0,0,0,0.2);
         transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }

      .show-products .box-container .box:hover {
         transform: translateY(-10px);
         background: #fff;
      }

      .show-products .box-container .box img {
         width: 100%;
         height: 20rem;
         object-fit: cover;
         border-radius: 1.5rem;
         margin-bottom: 1.5rem;
      }

      .show-products .box-container .box .name {
         font-size: 2.2rem;
         color: var(--dark-bg);
         font-weight: 700;
         margin: 1rem 0;
      }

      .show-products .box-container .box .flex {
         display: flex;
         justify-content: space-between;
         align-items: center;
         background: #f8f9fa;
         padding: 1rem;
         border-radius: 1rem;
         margin: 1rem 0;
      }

      .show-products .box-container .box .price {
         font-size: 2rem;
         color: var(--primary-color);
         font-weight: 800;
      }

      .show-products .box-container .box .category {
         font-size: 1.5rem;
         color: #777;
         font-style: italic;
      }

      .show-products .box-container .box .calories {
         font-size: 1.6rem;
         color: #e67e22;
         font-weight: 600;
         margin-bottom: 1.5rem;
      }

      .show-products .box-container .box .flex-btn {
         display: grid;
         grid-template-columns: 1fr 1fr;
         gap: 1rem;
      }

      .btn, .option-btn, .delete-btn {
         padding: 1.2rem;
         font-size: 1.6rem;
         border-radius: 0.8rem;
         cursor: pointer;
         text-align: center;
         transition: 0.3s;
         color: #fff;
         text-transform: capitalize;
         font-weight: 600;
      }

      .btn { background: var(--primary-color); }
      .option-btn { background: var(--dark-bg); }
      .delete-btn { background: #c0392b; }

      .btn:hover, .option-btn:hover, .delete-btn:hover {
         filter: brightness(1.2);
         transform: scale(1.02);
      }

      /* Responsive Adjustment */
      @media (max-width: 991px) {
         .show-products .box-container { grid-template-columns: repeat(2, 1fr); }
      }
      @media (max-width: 768px) {
         .show-products .box-container { grid-template-columns: 1fr; }
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="add-products">
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add New Product</h3>
      <input type="text" required placeholder="Product Name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Price (Rs)" name="price" class="box">
      <input type="number" min="0" max="9999" required placeholder="Calories (kcal)" name="calories" class="box">

      <select name="category" class="box" required>
         <option value="" disabled selected>Select Category --</option>
         <option value="main dish">Main Dish</option>
         <option value="fast food">Fast Food</option>
         <option value="drinks">Drinks</option>
         <option value="desserts">Desserts</option>
      </select>
      <input type="file" name="image" class="box" accept="image/*" required>
      <input type="submit" value="Add Product" name="add_product" class="btn">
   </form>
</section>

<section class="show-products" style="padding-top: 0;">
   <div class="box-container">
   <?php
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="flex">
         <div class="price">Rs <?= $fetch_products['price']; ?></div>
         <div class="category"><?= $fetch_products['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_products['name']; ?></div>
      
      <div class="calories">
         <i class="fas fa-fire"></i> <?= $fetch_products['calories']; ?> kcal
      </div>

      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty" style="color:#fff; text-align:center; font-size:2rem; width:100%;">No products added yet!</p>';
      }
   ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>