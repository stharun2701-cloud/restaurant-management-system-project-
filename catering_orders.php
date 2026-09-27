<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){ header('location:admin_login.php'); exit(); }

if(isset($_GET['complete'])){
   $complete_id = $_GET['complete'];
   $update_status = $conn->prepare("UPDATE `catering` SET status = ? WHERE id = ?");
   $update_status->execute(['completed', $complete_id]);
   header('location:catering_orders.php');
   exit();
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `catering` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:catering_orders.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Catering Management</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --dark-bg: #1e272e;
      }

      body {
         background: linear-gradient(rgba(30, 39, 46, 0.85), rgba(30, 39, 46, 0.85)), url('../images/admin-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
      }

      .catering-container {
         padding: 2rem;
         max-width: 1200px;
         margin: 0 auto;
      }

      .catering-container .heading {
         text-align: center;
         margin-bottom: 3rem;
         font-size: 4rem;
         color: #fff;
         text-transform: uppercase;
      }

      .catering-grid {
         display: grid;
         grid-template-columns: repeat(3, 1fr); /* Exactly 3 per line */
         gap: 2rem;
      }

      /* Using !important to override any external CSS making text white */
      .catering-card {
         background: rgba(255, 255, 255, 0.95) !important;
         padding: 2rem !important;
         border-radius: 1.5rem !important;
         border: 1px solid #ddd !important;
         box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
      }

      .catering-card p {
         font-size: 1.6rem !important;
         color: #222 !important; /* Force Dark Color */
         margin-bottom: 1rem !important;
         line-height: 1.5 !important;
         text-align: left !important;
      }

      .catering-card p span {
         color: #e74c3c !important; /* Force Red for data values */
         font-weight: 600 !important;
      }

      .msg-content {
         background: #f1f2f6 !important;
         padding: 1.5rem !important;
         border-radius: 1rem !important;
         margin: 1.5rem 0 !important;
         border-left: 5px solid #e74c3c !important;
         font-size: 1.4rem !important;
         color: #444 !important; /* Dark text for the message */
         text-align: left !important;
      }

      .status-label {
         font-weight: 800;
         text-transform: uppercase;
         font-size: 1.2rem;
         display: inline-block;
         margin-bottom: 1rem;
      }

      .flex-btn {
         display: flex;
         gap: 1rem;
      }

      .flex-btn a {
         flex: 1;
         text-align: center;
         padding: 1rem;
         border-radius: .5rem;
         font-size: 1.5rem;
         color: #fff !important;
         text-decoration: none;
      }

      .complete-btn { background-color: #27ae60; }
      .delete-btn { background-color: #c0392b; }

      /* Responsive */
      @media (max-width: 991px) { .catering-grid { grid-template-columns: repeat(2, 1fr); } }
      @media (max-width: 768px) { .catering-grid { grid-template-columns: 1fr; } }
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="catering-container">
   <h1 class="heading">Catering Requests</h1>

   <div class="catering-grid">
   <?php
      $select_orders = $conn->prepare("SELECT * FROM `catering` ORDER BY id DESC");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $color = ($fetch_orders['status'] == 'pending') ? 'orange' : 'green';
   ?>
   <div class="catering-card">
      <div class="status-label" style="color: <?= $color ?>;">● <?= $fetch_orders['status']; ?></div>
      <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
      <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
      
      <div class="msg-content">
         <strong style="color:#000;">Request Details:</strong><br>
         <?= $fetch_orders['message']; ?>
      </div>

      <div class="flex-btn">
         <?php if($fetch_orders['status'] == 'pending'){ ?>
            <a href="catering_orders.php?complete=<?= $fetch_orders['id']; ?>" class="complete-btn" onclick="return confirm('Mark as completed?');">Complete</a>
         <?php } ?>
         <a href="catering_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this request?');">Delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p style="color:white; font-size:2rem; text-align:center; width:100%;">No catering requests found.</p>';
      }
   ?>
   </div>
</section>

</body>
</html>