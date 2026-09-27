<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
};

/* Handle Payment Status Update */
if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = filter_var($_POST['payment_status'], FILTER_SANITIZE_STRING);
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

/* Handle Order Deletion */
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders | Yummy Land Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
          --primary-color: #e74c3c;
          --accent-color: #f39c12;
          --completed-color: #27ae60;
          --dark-bg: #1e272e;
          --glass-bg: rgba(255, 255, 255, 0.95);
      }

      body {
          background: linear-gradient(rgba(30, 39, 46, 0.85), rgba(30, 39, 46, 0.85)), url('../images/admin-bg.jpg') no-repeat;
          background-size: cover;
          background-position: center;
          background-attachment: fixed;
      }

      .placed-orders .heading {
          color: #fff;
          text-transform: uppercase;
          font-size: 4rem;
          margin-bottom: 3rem;
          text-align: center;
          text-shadow: 0 5px 10px rgba(0,0,0,0.3);
      }

      .placed-orders .box-container {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 2rem;
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 2rem;
      }

      .placed-orders .box-container .box {
          background: var(--glass-bg);
          backdrop-filter: blur(10px);
          padding: 2.5rem;
          border-radius: 2rem;
          border: 1px solid rgba(255,255,255,0.3);
          box-shadow: 0 10px 25px rgba(0,0,0,0.2);
          transition: 0.3s ease;
      }

      .placed-orders .box-container .box:hover {
          transform: translateY(-5px);
          box-shadow: 0 15px 30px rgba(0,0,0,0.3);
      }

      .placed-orders .box-container .box p {
          font-size: 1.6rem;
          color: #555;
          line-height: 1.8;
          margin-bottom: 0.5rem;
          border-bottom: 1px solid rgba(0,0,0,0.05);
          padding-bottom: 0.5rem;
      }

      .placed-orders .box-container .box p span {
          color: var(--dark-bg);
          font-weight: 600;
      }

      .placed-orders .box-container .box .drop-down {
          width: 100%;
          margin: 1.5rem 0;
          background: #f1f2f6;
          padding: 1.2rem;
          font-size: 1.7rem;
          border-radius: 1rem;
          border: 2px solid transparent;
          transition: 0.3s;
      }

      .status-pending { color: var(--accent-color) !important; font-weight: 800; }
      .status-completed { color: var(--completed-color) !important; font-weight: 800; }

      .flex-btn { display: flex; gap: 1rem; margin-top: 1rem; }

      .btn, .delete-btn {
          flex: 1;
          text-align: center;
          padding: 1.2rem;
          font-size: 1.6rem;
          border-radius: 1rem;
          font-weight: 600;
          text-transform: capitalize;
          color: #fff;
          cursor: pointer;
      }

      .btn { background: var(--dark-bg); border:none; }
      .btn:hover { background: var(--primary-color); }
      .delete-btn { background: #c0392b; }

      @media (max-width: 991px) { .placed-orders .box-container { grid-template-columns: repeat(2, 1fr); } }
      @media (max-width: 768px) { .placed-orders .box-container { grid-template-columns: 1fr; } }
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="placed-orders">
   <h1 class="heading">Orders Dashboard</h1>

   <div class="box-container">
   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY id DESC");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $status_class = ($fetch_orders['payment_status'] == 'pending') ? 'status-pending' : 'status-completed';
   ?>
   <div class="box">
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total Products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
      <p> Order Date : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Status : <span class="<?= $status_class; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>

      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled>Update Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="update" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </div>
      </form>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty" style="color:#fff; text-align:center; font-size:2rem; width:100%;">No orders processed yet!</p>';
      }
   ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>