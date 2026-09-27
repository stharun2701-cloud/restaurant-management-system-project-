<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

// Handle Delete
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
   exit();
}

// Handle Approval (Moves to Foodie Review / Approved status)
if(isset($_GET['approve'])){
   $approve_id = $_GET['approve'];
   // Note: Ensure your 'messages' table has a 'status' column
   $update_status = $conn->prepare("UPDATE `messages` SET status = ? WHERE id = ?");
   $update_status->execute(['approved', $approve_id]);
   header('location:messages.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages & Reviews | Admin Panel</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --success-color: #27ae60;
         --dark-bg: #1e272e;
         --glass-bg: rgba(255, 255, 255, 0.95);
         --rating-color: #f1c40f;
      }

      body {
         background: linear-gradient(rgba(30, 39, 46, 0.85), rgba(30, 39, 46, 0.85)), url('../images/admin-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
      }

      .messages .heading { 
         color: #fff; 
         text-transform: uppercase; 
         font-size: 3.5rem; 
         margin: 3rem 0; 
         text-align: center; 
         text-shadow: 0 5px 10px rgba(0,0,0,0.3);
      }

      .messages .box-container { 
         display: grid; 
         grid-template-columns: repeat(3, 1fr); 
         gap: 2rem; 
         max-width: 1200px; 
         margin: 0 auto; 
         padding: 0 2rem; 
      }
      
      .messages .box-container .box {
         background: var(--glass-bg); 
         backdrop-filter: blur(10px);
         padding: 2.5rem; 
         border-radius: 2rem; 
         border: 1px solid rgba(255,255,255,0.3);
         box-shadow: 0 10px 25px rgba(0,0,0,0.2); 
         transition: 0.3s ease;
         display: flex; 
         flex-direction: column;
      }

      .messages .box-container .box:hover {
         transform: translateY(-5px);
         background: #fff;
      }

      .messages .box-container .box p { 
         font-size: 1.6rem; 
         color: #333 !important; 
         margin-bottom: 1rem; 
         border-bottom: 1px solid rgba(0,0,0,0.05); 
         padding-bottom: 0.8rem; 
      }

      .messages .box-container .box p span { 
         color: var(--dark-bg) !important; 
         font-weight: 700; 
      }

      .messages .box-container .box p i { 
         margin-right: 1rem; 
         color: var(--primary-color); 
         width: 2rem;
         text-align: center;
      }

      /* Star Ratings Display */
      .stars { 
         margin-bottom: 1.5rem; 
         font-size: 1.8rem; 
         color: var(--rating-color); 
      }

      .message-body { 
         background: #f8f9fa; 
         padding: 1.5rem; 
         border-radius: 1rem; 
         margin: 1rem 0; 
         font-style: italic; 
         color: #555 !important; 
         border-left: 4px solid var(--primary-color); 
         font-size: 1.5rem; 
         line-height: 1.6;
      }
      
      .flex-btn { 
         display: flex; 
         gap: 1rem; 
         margin-top: auto; 
         padding-top: 1.5rem;
      }
      
      .approve-btn, .delete-btn { 
         flex: 1; 
         padding: 1.2rem; 
         font-size: 1.6rem; 
         border-radius: 1rem; 
         font-weight: 600; 
         text-align: center; 
         color: #fff; 
         text-transform: capitalize; 
         transition: 0.3s; 
         text-decoration: none;
      }

      .approve-btn { background-color: var(--success-color); }
      .delete-btn { background-color: #c0392b; }

      .approve-btn:hover { background-color: #219150; transform: scale(1.02); }
      .delete-btn:hover { background-color: var(--dark-bg); transform: scale(1.02); }

      .sub-heading { 
         color: var(--primary-color); 
         text-align: center; 
         font-size: 2.5rem; 
         margin-bottom: 2rem; 
         background: #fff; 
         display: inline-block; 
         padding: 1rem 3rem; 
         border-radius: 5rem; 
         width: auto; 
         left: 50%; 
         position: relative; 
         transform: translateX(-50%); 
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
         font-weight: 700;
      }

      @media (max-width: 991px) {
         .messages .box-container { grid-template-columns: repeat(2, 1fr); }
      }
      @media (max-width: 768px) {
         .messages .box-container { grid-template-columns: 1fr; }
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="messages">

   <h1 class="heading">Customer Feedback Center</h1>

   <div class="sub-heading">New Messages</div>
   <div class="box-container" style="margin-bottom: 6rem;">
   <?php
      // Fetches messages that haven't been approved yet
      $select_messages = $conn->prepare("SELECT * FROM `messages` WHERE status = 'pending' ORDER BY id DESC");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <div class="stars">
         <?php 
            $rating = isset($fetch_messages['rating']) ? $fetch_messages['rating'] : 5;
            for($i=1; $i<=5; $i++){
               echo ($i <= $rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            } 
         ?>
      </div>
      <p><i class="fas fa-user"></i> Name : <span><?= $fetch_messages['name']; ?></span></p>
      
      <div class="message-body">"<?= $fetch_messages['message']; ?>"</div>
      
      <div class="flex-btn">
         <a href="messages.php?approve=<?= $fetch_messages['id']; ?>" class="approve-btn" onclick="return confirm('Approve this for Foodie Reviews?');">Approve</a>
         <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message permanently?');">Delete</a>
      </div>
   </div>
   <?php
         }
      }else{ 
         echo '<p class="empty" style="grid-column: 1 / -1; text-align:center; color:#fff; font-size:2rem; background:rgba(0,0,0,0.3); padding:2rem; border-radius:1rem;">No new messages available.</p>'; 
      }
   ?>
   </div>

   <div class="sub-heading" style="background: var(--success-color); color: #fff;">Foodie Reviews (Live)</div>
   <div class="box-container">
   <?php
      // Fetches messages that were approved
      $select_reviews = $conn->prepare("SELECT * FROM `messages` WHERE status = 'approved' ORDER BY id DESC");
      $select_reviews->execute();
      if($select_reviews->rowCount() > 0){
         while($fetch_reviews = $select_reviews->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" style="border: 2px solid var(--success-color);">
      <div class="stars">
         <?php 
            $rating = isset($fetch_reviews['rating']) ? $fetch_reviews['rating'] : 5;
            for($i=1; $i<=5; $i++){
               echo ($i <= $rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            } 
         ?>
      </div>
      <p><i class="fas fa-check-circle" style="color: var(--success-color);"></i> Status : <span style="color: var(--success-color) !important;">Approved Review</span></p>
      <p><i class="fas fa-user"></i> Name : <span><?= $fetch_reviews['name']; ?></span></p>
      
      
      <div class="message-body" style="border-left-color: var(--success-color);">"<?= $fetch_reviews['message']; ?>"</div>
      
      <a href="messages.php?delete=<?= $fetch_reviews['id']; ?>" class="delete-btn" onclick="return confirm('Remove this from approved reviews?');">Remove Review</a>
   </div>
   <?php
         }
      }else{ 
         echo '<p class="empty" style="grid-column: 1 / -1; text-align:center; color:#fff; font-size:2rem; background:rgba(0,0,0,0.3); padding:2rem; border-radius:1rem;">No reviews have been approved yet.</p>'; 
      }
   ?>
   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>