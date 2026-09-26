<?php
/* Including connection from the same directory */
include 'components/connect.php';

session_start();

/* Standard user authentication check */
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
   exit();
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Invoice - Cuisine Cloud</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      :root {
          --primary-red: #e74c3c;
          --dark-steel: #2c3e50;
          --eco-green: #2ecc71;
          --calorie-orange: #f39c12;
          --light-gray: #f8f9fa;
      }

      body { 
         background-color: #f0f2f5; 
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      .invoice-container {
          max-width: 900px;
          margin: 50px auto;
          background: #fff;
          padding: 50px;
          border-radius: 20px;
          box-shadow: 0 20px 50px rgba(0,0,0,0.05);
          position: relative;
          overflow: hidden;
      }

      /* Modern Header */
      .invoice-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 40px;
          padding-bottom: 30px;
          border-bottom: 2px solid var(--light-gray);
      }

      .brand-box {
          display: flex;
          align-items: center;
          gap: 15px;
      }

      .logo-img { 
          height: 75px; 
          width: 100px; 
          object-fit: cover;
          border-radius: 15px;
      }

      .brand-name {
          font-size: 28px;
          font-weight: 800;
          color: var(--dark-steel);
          letter-spacing: -1px;
          
      }

      .brand-name span {
          color: var(--primary-red); /* Red "Cloud" */
      }

      .invoice-meta { text-align: right; }
      .invoice-meta h2 { 
          font-size: 32px; 
          color: var(--dark-steel); 
          margin: 0;
          letter-spacing: 2px;
      }

      /* Details Grid */
      .details-grid {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 40px;
          margin-bottom: 40px;
      }

      .details-grid h4 {
          text-transform: uppercase;
          font-size: 13px;
          color: #999;
          margin-bottom: 10px;
          letter-spacing: 1px;
      }

      .details-grid p { font-size: 16px; margin: 5px 0; color: var(--dark-steel); }
      .details-grid p span { font-weight: 600; }

      /* Modern Feature Cards */
      .feature-row {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 20px;
          margin-bottom: 40px;
      }

      .feature-card {
          background: var(--light-gray);
          padding: 20px;
          border-radius: 15px;
          text-align: center;
          transition: transform 0.3s;
      }

      .feature-card i { font-size: 24px; margin-bottom: 10px; }
      .feature-card small { display: block; color: #777; font-size: 12px; margin-bottom: 5px; }
      .feature-card b { font-size: 18px; color: var(--dark-steel); }

      /* Items Table Modernized */
      .items-table {
          width: 100%;
          border-collapse: separate;
          border-spacing: 0 10px;
          margin-bottom: 30px;
      }

      .items-table th { 
          padding: 15px; 
          color: #999; 
          text-transform: uppercase; 
          font-size: 12px; 
          text-align: left;
      }

      .items-table td { 
          background: #fff; 
          padding: 20px; 
          border-top: 1px solid #f0f0f0;
          border-bottom: 1px solid #f0f0f0;
          font-size: 16px;
      }

      .items-table td:first-child { border-left: 1px solid #f0f0f0; border-radius: 10px 0 0 10px; }
      .items-table td:last-child { border-right: 1px solid #f0f0f0; border-radius: 0 10px 10px 0; text-align: right; }

      /* Summary Section */
      .summary-wrap {
          display: flex;
          justify-content: flex-end;
          margin-top: 20px;
      }

      .summary-box {
          width: 300px;
          background: var(--dark-steel);
          color: #fff;
          padding: 25px;
          border-radius: 15px;
      }

      .summary-line {
          display: flex;
          justify-content: space-between;
          margin-bottom: 10px;
          font-size: 14px;
          opacity: 0.8;
      }

      .summary-line.total {
          margin-top: 15px;
          padding-top: 15px;
          border-top: 1px solid rgba(255,255,255,0.1);
          font-size: 20px;
          font-weight: 700;
          opacity: 1;
      }

      .eco-badge {
          display: inline-block;
          background: rgba(46, 204, 113, 0.1);
          color: var(--eco-green);
          padding: 5px 12px;
          border-radius: 20px;
          font-size: 12px;
          font-weight: 700;
      }

      .btn-area {
          margin-top: 50px;
          display: flex;
          justify-content: center;
          gap: 20px;
      }

      .btn-modern {
          padding: 15px 35px;
          border-radius: 12px;
          font-weight: 600;
          text-decoration: none;
          display: flex;
          align-items: center;
          gap: 10px;
          cursor: pointer;
          border: none;
          transition: 0.3s;
      }

      .btn-print { background: var(--primary-red); color: white; }
      .btn-back { background: var(--light-gray); color: var(--dark-steel); }
      .btn-modern:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

      @media print {
          header, footer, .btn-area { display: none !important; }
          body { background: white; }
          .invoice-container { box-shadow: none; margin: 0; padding: 20px; width: 100%; }
          .summary-box { background: #eee !important; color: black !important; }
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="invoice-container">

   <?php
      $get_id = isset($_GET['get_id']) ? $_GET['get_id'] : '';
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ? AND user_id = ?");
      $select_orders->execute([$get_id, $user_id]);

      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>

   <div class="invoice-header">
      <div class="brand-box">
         <img src="project images/logo.jpeg" alt="Logo" class="logo-img">
         <div class="brand-name">Cuisine<span>Cloud</span></div>
      </div>
      <div class="invoice-meta">
         <h2>INVOICE</h2>
         <p style="color:#999;">Order ID: #<?= $fetch_orders['id']; ?></p>
      </div>
   </div>

   <div class="details-grid">
      <div class="customer-info">
         <h4>Billed To</h4>
         <p><span><?= $fetch_orders['name']; ?></span></p>
         <p><?= $fetch_orders['number']; ?></p>
         <p style="font-size: 14px; color: #777;"><?= $fetch_orders['address']; ?></p>
      </div>
      <div class="order-info" style="text-align: right;">
         <h4>Order Details</h4>
         <p>Date: <span><?= $fetch_orders['placed_on']; ?></span></p>
         <p>Method: <span><?= $fetch_orders['method']; ?></span></p>
         <p>Status: <span style="color:var(--primary-red);"><?= $fetch_orders['payment_status']; ?></span></p>
      </div>
   </div>

   <div class="feature-row">
      <div class="feature-card">
         <i class="fas fa-fire" style="color: var(--calorie-orange);"></i>
         <small>Nutritional Info</small>
         <b><?= $fetch_orders['total_calories']; ?> kcal</b>
      </div>
      <div class="feature-card">
         <i class="fas fa-leaf" style="color: var(--eco-green);"></i>
         <small>Eco Contribution</small>
         <b><?= $fetch_orders['paper_bags_returned']; ?> Bags</b>
      </div>
      <div class="feature-card">
         <i class="fas fa-wallet" style="color: #3498db;"></i>
         <small>Saved Today</small>
         <b style="color: var(--eco-green);">₹<?= $fetch_orders['discount_amount']; ?></b>
      </div>
   </div>

   <table class="items-table">
      <thead>
         <tr>
            <th>Order Description</th>
            <th style="text-align: right;">Amount</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>
               <p style="font-weight: 600; margin: 0;"><?= $fetch_orders['total_products']; ?></p>
               <span class="eco-badge"><i class="fas fa-check-circle"></i> Eco-Packaging Applied</span>
            </td>
            <td style="font-weight: 700;">₹<?= ($fetch_orders['total_price'] + $fetch_orders['discount_amount']); ?></td>
         </tr>
      </tbody>
   </table>

   <div class="summary-wrap">
      <div class="summary-box">
         <div class="summary-line">
            <span>Subtotal</span>
            <span>₹<?= ($fetch_orders['total_price'] + $fetch_orders['discount_amount']); ?></span>
         </div>
         <div class="summary-line" style="color: var(--eco-green);">
            <span>Eco Discount</span>
            <span>- ₹<?= $fetch_orders['discount_amount']; ?></span>
         </div>
         <div class="summary-line total">
            <span>Grand Total</span>
            <span>₹<?= $fetch_orders['total_price']; ?></span>
         </div>
      </div>
   </div>

   <div class="footer-thanks" style="text-align: center; margin-top: 40px; color: #bbb; font-size: 14px;">
      <p>Thank you for ordering with Cuisine Cloud. Your healthy and sustainable choice matters!</p>
   </div>

   <div class="btn-area">
      <button onclick="window.print()" class="btn-modern btn-print">
         <i class="fas fa-print"></i> Download Invoice
      </button>
      <a href="orders.php" class="btn-modern btn-back">
         <i class="fas fa-arrow-left"></i> Back to Orders
      </a>
   </div>

   <?php
         }
      } else {
         echo '<div style="text-align:center; padding:50px;"><h2>Order Not Found!</h2><a href="orders.php" class="btn-modern btn-print">Return to Orders</a></div>';
      }
   ?>
</div>

</body>
</html>