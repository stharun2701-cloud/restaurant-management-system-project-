<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users Accounts | Admin</title>

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

      .accounts .heading {
         color: #fff;
         text-transform: uppercase;
         font-size: 4rem;
         margin-bottom: 3rem;
         text-align: center;
         text-shadow: 0 5px 10px rgba(0,0,0,0.3);
      }

      .accounts .box-container {
         display: grid;
         grid-template-columns: repeat(3, 1fr); /* Matching dashboard 3-column layout */
         gap: 2rem;
         max-width: 1200px;
         margin: 0 auto;
         padding: 0 2rem;
      }

      .accounts .box-container .box {
         background: var(--glass-bg);
         backdrop-filter: blur(10px);
         padding: 3rem 2rem;
         text-align: center;
         border-radius: 2rem;
         border: 1px solid rgba(255,255,255,0.3);
         box-shadow: 0 10px 25px rgba(0,0,0,0.2);
         transition: 0.3s ease;
         display: flex;
         flex-direction: column;
         justify-content: center;
      }

      .accounts .box-container .box:hover {
         transform: translateY(-5px);
         background: #fff;
      }

      /* Fixed Text visibility - High Contrast */
      .accounts .box-container .box p {
         font-size: 1.8rem;
         color: #333 !important; /* Dark text for readability */
         margin-bottom: 1rem;
         line-height: 1.5;
      }

      .accounts .box-container .box p span {
         color: var(--primary-color) !important; /* Red for the actual values */
         font-weight: 700;
      }

      .delete-btn {
         display: block;
         width: 100%;
         margin-top: 1.5rem;
         padding: 1.2rem;
         font-size: 1.7rem;
         background-color: #c0392b;
         color: #fff;
         border-radius: 1rem;
         font-weight: 600;
         transition: 0.3s;
         text-transform: capitalize;
      }

      .delete-btn:hover {
         background-color: var(--dark-bg);
         transform: scale(1.02);
      }

      /* Responsive Adjustment */
      @media (max-width: 991px) {
         .accounts .box-container { grid-template-columns: repeat(2, 1fr); }
      }
      @media (max-width: 768px) {
         .accounts .box-container { grid-template-columns: 1fr; }
      }
   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="accounts">

   <h1 class="heading">User Accounts</h1>

   <div class="box-container">

   <?php
      $select_account = $conn->prepare("SELECT * FROM `users` ORDER BY id DESC");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <div class="box">
      <p> <i class="fas fa-id-badge"></i> User ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> <i class="fas fa-user"></i> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> <i class="fas fa-envelope"></i> Email : <span><?= $fetch_accounts['email'] ?? 'No email set'; ?></span> </p>
      
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('delete this account?');">delete account</a>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty" style="width:100%; color:#fff; text-align:center; font-size:2rem;">no accounts available</p>';
   }
   ?>

   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>