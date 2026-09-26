<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
   exit();
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Your Orders | Restaurant Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* --- EMERGENCY LOADER FIX --- */
      #loader, .loader, .preloader { display: none !important; }
      body { display: block !important; opacity: 1 !important; overflow: auto !important; }

      /* --- MODERN DASHBOARD STYLING --- */
      .orders-container {
         max-width: 1200px;
         margin: 0 auto;
         padding: 2rem;
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
         gap: 2.5rem;
      }

      .order-card {
         background: #fff;
         border-radius: 15px;
         border: 1px solid #eee;
         box-shadow: 0 10px 25px rgba(0,0,0,0.05);
         padding: 2.5rem;
         position: relative;
         transition: transform 0.3s ease;
         overflow: hidden;
      }

      .order-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      }

      .status-badge {
         position: absolute;
         top: 20px;
         right: 20px;
         padding: 0.5rem 1.2rem;
         border-radius: 50px;
         font-size: 1.2rem;
         font-weight: 700;
         text-transform: uppercase;
      }

      .status-pending { background: #fff5f5; color: #e74c3c; border: 1px solid #feb2b2; }
      .status-completed { background: #f0fff4; color: #27ae60; border: 1px solid #9ae6b4; }

      .order-date {
         font-size: 1.3rem;
         color: #888;
         margin-bottom: 1.5rem;
         display: block;
      }

      .order-card p {
         font-size: 1.5rem;
         margin-bottom: 1rem;
         color: #444;
         line-height: 1.5;
      }

      .order-card i {
         width: 25px;
         color: var(--red);
         margin-right: 10px;
      }

      .order-products {
         background: #f9f9f9;
         padding: 1.5rem;
         border-radius: 8px;
         margin: 1.5rem 0;
         border-left: 4px solid var(--red);
         font-style: italic;
      }

      .order-total {
         font-size: 2.2rem !important;
         font-weight: 800;
         color: #333 !important;
         border-top: 1px solid #eee;
         padding-top: 1.5rem;
         margin-top: 1.5rem;
         display: flex;
         justify-content: space-between;
      }

      .view-invoice-btn {
         display: block;
         text-align: center;
         background: #333;
         color: #fff;
         padding: 1.2rem;
         border-radius: 8px;
         margin-top: 1.5rem;
         font-size: 1.4rem;
         font-weight: 600;
         transition: 0.3s;
      }

      .view-invoice-btn:hover { background: var(--red); color: #fff; }

      @media (max-width: 450px) {
         .orders-container { grid-template-columns: 1fr; }
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Your Dashboard</h3>
   <p><a href="home.php">home</a> <span> / orders</span></p>
</div>

<section class="orders">

   <h1 class="title">History</h1>

   <div class="orders-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY id DESC");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
               $status_class = ($fetch_orders['payment_status'] == 'pending') ? 'status-pending' : 'status-completed';
   ?>
   <div class="order-card">
      <span class="status-badge <?= $status_class; ?>">
         <?= $fetch_orders['payment_status']; ?>
      </span>
      
      <span class="order-date"><i class="far fa-calendar-alt"></i> <?= $fetch_orders['placed_on']; ?></span>
      
      <p><i class="fas fa-user"></i> <b><?= $fetch_orders['name']; ?></b></p>
      <p><i class="fas fa-phone"></i> <?= $fetch_orders['number']; ?></p>
      <p><i class="fas fa-envelope"></i> <?= $fetch_orders['email']; ?></p>
      <p><i class="fas fa-map-marker-alt"></i> <?= $fetch_orders['address']; ?></p>
      <p><i class="fas fa-wallet"></i> <?= $fetch_orders['method']; ?></p>

      <div class="order-products">
         <i class="fas fa-utensils"></i> Items: <?= $fetch_orders['total_products']; ?>
      </div>

      <p class="order-total">
         <span>Total</span>
         <span>₹<?= $fetch_orders['total_price']; ?>/-</span>
      </p>

      <a href="view_invoice.php?get_id=<?= $fetch_orders['id']; ?>" class="view-invoice-btn">
         <i class="fas fa-file-invoice"></i> View Detailed Invoice
      </a>
   </div>
   <?php
            }
         }else{
            echo '<p class="empty">No orders placed yet!</p>';
         }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script>
   document.addEventListener("DOMContentLoaded", function() {
      const loader = document.querySelector('.loader') || document.querySelector('#loader');
      if(loader) loader.style.display = 'none';
   });
</script>

</body>
</html>