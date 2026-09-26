<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header('location:home.php');
   exit();
}

$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

$grand_total = 0;
$total_calories = 0; 
$cart_items = []; 

$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->execute([$user_id]);

if($select_cart->rowCount() > 0){
   while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].')';
      $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);

      $get_prod = $conn->prepare("SELECT calories FROM `products` WHERE name = ?");
      $get_prod->execute([$fetch_cart['name']]);
      $f_prod = $get_prod->fetch(PDO::FETCH_ASSOC);
      $total_calories += ($f_prod['calories'] ?? 0) * $fetch_cart['quantity'];
   }
   $total_products = implode(' - ', $cart_items);
}

if(isset($_POST['submit'])){
   $name = htmlspecialchars($_POST['name']);
   $number = htmlspecialchars($_POST['number']);
   $email = htmlspecialchars($_POST['email']);
   $method = htmlspecialchars($_POST['method']);
   $address = htmlspecialchars($_POST['address']);
   $total_products = $_POST['total_products'];
   
   $bags_returned = isset($_POST['bags_returned']) ? (int)$_POST['bags_returned'] : 0;
   $total_discount = ($bags_returned == 1) ? 5 : (($bags_returned == 2) ? 10 : 0);
   $final_total = max(0, $grand_total - $total_discount);

   if($select_cart->rowCount() > 0){
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, total_calories, paper_bags_returned, discount_amount) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
      
      $insert_order->execute([
         $user_id, $name, $number, $email, $method, $address, 
         $total_products, $final_total, $total_calories, $bags_returned, $total_discount
      ]);

      $order_id = $conn->lastInsertId();
      
      if($bags_returned > 0){
         $conn->prepare("UPDATE `users` SET total_bags_returned = total_bags_returned + ? WHERE id = ?")->execute([$bags_returned, $user_id]);
      }
      $conn->prepare("DELETE FROM `cart` WHERE user_id = ?")->execute([$user_id]);
      
      header("location:view_invoice.php?get_id=" . $order_id);
      exit();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Secure Checkout</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      #loader, .loader { display: none !important; }
      body { display: block !important; background: #f4f4f4; }
      .checkout-grid { display: flex; flex-wrap: wrap; gap: 25px; max-width: 1200px; margin: 30px auto; padding: 0 20px; }
      .checkout-card { background: #fff; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #eee; flex: 1 1 450px; }
      .card-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 20px; border-bottom: 2px solid #f8f9fa; padding-bottom: 10px; display: flex; align-items: center; gap: 12px; }
      .eco-highlight { background: #f0fff4; border: 2px dashed #27ae60; border-radius: 12px; padding: 20px; margin-bottom: 25px; }
      .payment-box { border: 2px solid #e74c3c; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 15px; background: #fff5f5; margin-top: 10px;}
      .summary-line { display: flex; justify-content: space-between; font-size: 17px; margin-bottom: 12px; color: #666; }
      .grand-total { border-top: 2px solid #eee; margin-top: 20px; padding-top: 15px; font-size: 26px; font-weight: 800; color: #e74c3c; display: flex; justify-content: space-between; }
      .confirm-btn { width: 100%; background: #e74c3c; color: #fff; padding: 18px; font-size: 20px; font-weight: 700; border-radius: 10px; cursor: pointer; border: none; margin-top: 20px; transition: 0.3s; }
      .confirm-btn:hover { background: #333; }
   </style>
</head>
<body>
<?php include 'components/user_header.php'; ?>

<section class="checkout">
   <h1 class="title">Secure Checkout</h1>
   <form action="" method="post" id="checkout-form" class="checkout-grid">
      
      <div class="checkout-card">
         <h3 class="card-title"><i class="fas fa-shopping-bag"></i> Order Summary</h3>
         <div class="summary-line"><span>Subtotal</span><span>₹<?= $grand_total; ?></span></div>
         <div class="summary-line" style="color: #27ae60;"><span>Eco Discount</span><span id="js-discount">- ₹0</span></div>
         <div class="grand-total"><span>Total to Pay</span><span id="js-total">₹<?= $grand_total; ?></span></div>
         
         <h3 class="card-title" style="font-size: 18px; margin-top: 25px;"><i class="fas fa-credit-card"></i> Payment Mode</h3>
         <div class="payment-box">
            <i class="fas fa-money-bill-wave" style="font-size: 24px; color: #e74c3c;"></i>
            <div>
               <p style="font-weight: 700; color: #333; margin:0;">Cash On Delivery</p>
               <p style="font-size: 12px; color: #777; margin:0;">Pay when your food arrives</p>
            </div>
         </div>
      </div>

      <div class="checkout-card">
         <h3 class="card-title"><i class="fas fa-leaf" style="color: #27ae60;"></i> Sustainability Reward</h3>
         <div class="eco-highlight">
            <p style="font-size: 14px; margin-bottom: 10px;">Return clean bags to the delivery partner:</p>
            <label style="display: block; cursor: pointer; margin-bottom: 8px;">
               <input type="radio" name="bags_returned" value="0" data-save="0" checked> 0 Bags (Standard)
            </label>
            <label style="display: block; cursor: pointer; margin-bottom: 8px;">
               <input type="radio" name="bags_returned" value="1" data-save="5"> 1 Bag (₹5 Discount)
            </label>
            <label style="display: block; cursor: pointer;">
               <input type="radio" name="bags_returned" value="2" data-save="10"> 2 Bags (₹10 Discount)
            </label>
         </div>

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
         <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
         <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
         <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">
         <input type="hidden" name="method" value="cash on delivery">

         <button type="submit" name="submit" class="confirm-btn">Place Order</button>
      </div>
   </form>
</section>

<script>
   const radios = document.querySelectorAll('input[name="bags_returned"]');
   const baseTotal = <?= $grand_total; ?>;
   radios.forEach(r => {
      r.addEventListener('change', () => {
         const disc = parseInt(r.getAttribute('data-save'));
         document.getElementById('js-discount').innerText = '- ₹' + disc;
         document.getElementById('js-total').innerText = '₹' + (baseTotal - disc);
      });
   });
</script>
</body>
</html>