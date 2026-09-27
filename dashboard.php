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
   <title>Dashboard | Cuisine Cloud Admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --accent-color: #f39c12;
         --dark-bg: #1e272e;
         --glass-bg: rgba(255, 255, 255, 0.95);
      }

      body {
         background: linear-gradient(rgba(30, 39, 46, 0.8), rgba(30, 39, 46, 0.8)), url('../images/admin-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
      }

      .dashboard .heading {
         color: #fff;
         text-transform: uppercase;
         font-size: 4rem;
         margin-bottom: 3rem;
         text-align: center;
         text-shadow: 0 5px 10px rgba(0,0,0,0.3);
      }

      .dashboard .box-container {
         display: grid;
         /* Forces exactly 3 columns on desktop */
         grid-template-columns: repeat(3, 1fr); 
         gap: 2rem;
         max-width: 1200px;
         margin: 0 auto;
      }

      .dashboard .box-container .box {
         background: var(--glass-bg);
         backdrop-filter: blur(10px);
         padding: 3rem 2rem;
         text-align: center;
         border-radius: 2rem;
         border: 1px solid rgba(255,255,255,0.2);
         box-shadow: 0 10px 20px rgba(0,0,0,0.2);
         transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
         
         /* Ensures vertical alignment inside the box */
         display: flex;
         flex-direction: column;
         justify-content: space-between;
         height: 100%;
      }

      .dashboard .box-container .box:hover {
         transform: translateY(-10px);
         box-shadow: 0 15px 30px rgba(231, 76, 60, 0.2);
         background: #fff;
      }

      .dashboard .box-container .box h3 {
         font-size: 3.5rem;
         color: var(--black);
         margin-bottom: 1rem;
      }

      .dashboard .box-container .box h3 span {
         font-size: 2rem;
      }

      .dashboard .box-container .box p {
         font-size: 1.8rem;
         color: #666;
         padding: 1rem 0;
         text-transform: capitalize;
         font-weight: 500;
         margin-bottom: 1.5rem;
      }

      .dashboard .box-container .box .btn {
         display: block;
         width: 100%;
         border-radius: 1rem;
         background-color: var(--primary-color);
         color: #fff;
         font-size: 1.7rem;
         padding: 1.2rem;
         transition: 0.3s;
         margin-top: auto; /* Pushes button to bottom */
      }

      .dashboard .box-container .box .btn:hover {
         background-color: var(--black);
      }

      /* Responsive Adjustments */
      @media (max-width: 991px) {
         .dashboard .box-container {
            grid-template-columns: repeat(2, 1fr); /* 2 per row on tablets */
         }
      }

      @media (max-width: 768px) {
         .dashboard .box-container {
            grid-template-columns: 1fr; /* 1 per row on mobile */
         }
      }

      /* Decorative accents */
      .dashboard .box-container .box:first-child { border-left: .8rem solid var(--primary-color); }
      .dashboard .box-container .box:nth-child(2) h3 { color: var(--accent-color); }
   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="dashboard">

   <h1 class="heading">Admin Dashboard</h1>

   <div class="box-container">

   <div class="box">
      <h3>Welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">Update Profile</a>
   </div>

   <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
         }
      ?>
      <h3><span>Rs </span><?= $total_pendings; ?><span>/-</span></h3>
      <p>Total Pendings</p>
      <a href="placed_orders.php" class="btn">See Orders</a>
   </div>

   <div class="box">
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completes->execute(['completed']);
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes += $fetch_completes['total_price'];
         }
      ?>
      <h3><span>Rs </span><?= $total_completes; ?><span>/-</span></h3>
      <p>Total Completes</p>
      <a href="placed_orders.php" class="btn">See Orders</a>
   </div>

   <div class="box">
      <?php
         $select_catering = $conn->prepare("SELECT * FROM `catering` WHERE status = ?");
         $select_catering->execute(['pending']);
         $numbers_of_catering = $select_catering->rowCount();
      ?>
      <h3><?= $numbers_of_catering; ?></h3>
      <p>Pending Catering</p>
      <a href="catering_orders.php" class="btn">See Catering</a>
   </div>

   <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $numbers_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>Total Orders</p>
      <a href="placed_orders.php" class="btn">See Orders</a>
   </div>

   <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>Products Added</p>
      <a href="products.php" class="btn">See Products</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>User Accounts</p>
      <a href="users_accounts.php" class="btn">See Users</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admin`");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>Admins</p>
      <a href="admin_accounts.php" class="btn">See Admins</a>
   </div>

   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $numbers_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>New Review</p>
      <a href="messages.php" class="btn">See Review</a>
   </div>

   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>