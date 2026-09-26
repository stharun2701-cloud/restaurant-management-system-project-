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

/* --- LOGIC ACTIONS --- */
if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'Item removed from cart';
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   $message[] = 'Cart cleared!';
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'Quantity updated successfully';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Your Cart | Cuisine Cloud</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* --- LOADER KILLER --- */
      #loader, .loader, .preloader { display: none !important; visibility: hidden !important; }
      body { display: block !important; opacity: 1 !important; }

      /* --- CART SPECIFIC DESIGN --- */
      .cart-grid {
         display: grid;
         grid-template-columns: 1fr 350px;
         gap: 30px;
         max-width: 1200px;
         margin: 30px auto;
         padding: 0 20px;
         align-items: start;
      }

      .cart-items {
         background: #fff;
         border-radius: 15px;
         padding: 20px;
         box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      }

      .cart-item-card {
         display: flex;
         align-items: center;
         gap: 20px;
         padding: 20px;
         border-bottom: 1px solid #eee;
         position: relative;
      }

      .cart-item-card:last-child { border-bottom: none; }

      .cart-item-card img {
         width: 120px;
         height: 120px;
         object-fit: cover;
         border-radius: 10px;
      }

      .cart-item-info { flex: 1; }

      .cart-item-info .name {
         font-size: 20px;
         font-weight: 700;
         color: #333;
         margin-bottom: 10px;
      }

      .cart-item-info .flex {
         display: flex;
         align-items: center;
         gap: 15px;
      }

      .cart-item-info .price {
         font-size: 18px;
         color: var(--red);
         font-weight: 600;
      }

      .qty-wrapper {
         display: flex;
         align-items: center;
         background: #f5f5f5;
         padding: 5px 10px;
         border-radius: 8px;
      }

      .qty-wrapper input {
         width: 50px;
         background: transparent;
         border: none;
         text-align: center;
         font-size: 16px;
         font-weight: bold;
      }

      .update-btn {
         background: none;
         color: #27ae60;
         font-size: 18px;
         cursor: pointer;
         transition: 0.3s;
      }

      .update-btn:hover { transform: scale(1.2); }

      /* Floating Summary Card */
      .cart-summary {
         background: #fff;
         border-radius: 15px;
         padding: 25px;
         box-shadow: 0 10px 25px rgba(0,0,0,0.08);
         position: sticky;
         top: 100px;
      }

      .summary-title {
         font-size: 22px;
         font-weight: 800;
         margin-bottom: 20px;
         border-bottom: 2px solid #f9f9f9;
         padding-bottom: 10px;
      }

      .total-row {
         display: flex;
         justify-content: space-between;
         font-size: 18px;
         margin-bottom: 15px;
         color: #666;
      }

      .grand-total-row {
         display: flex;
         justify-content: space-between;
         font-size: 24px;
         font-weight: 900;
         color: var(--black);
         border-top: 2px solid #eee;
         padding-top: 20px;
         margin-top: 20px;
      }

      .checkout-btn {
         display: block;
         width: 100%;
         text-align: center;
         background: var(--red);
         color: #fff;
         padding: 15px;
         border-radius: 10px;
         font-size: 18px;
         font-weight: 700;
         margin-top: 20px;
      }

      .checkout-btn.disabled {
         background: #ccc;
         pointer-events: none;
      }

      .remove-item {
         position: absolute;
         top: 20px; right: 10px;
         font-size: 20px;
         color: #999;
         cursor: pointer;
      }

      .remove-item:hover { color: var(--red); }

      @media (max-width: 991px) {
         .cart-grid { grid-template-columns: 1fr; }
         .cart-summary { position: static; }
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="home.php">home</a> <span> / cart</span></p>
</div>

<section class="shopping-cart">
   <h1 class="title">Review Your Items</h1>

   <div class="cart-grid">
      
      <div class="cart-items">
         <?php
            $grand_total = 0;
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
                  $grand_total += $sub_total;
         ?>
         <form action="" method="post" class="cart-item-card">
            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
            
            <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
            
            <div class="cart-item-info">
               <div class="name"><?= $fetch_cart['name']; ?></div>
               <div class="flex">
                  <div class="price">₹<?= $fetch_cart['price']; ?></div>
                  
                  <div class="qty-wrapper">
                     <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>">
                     <button type="submit" name="update_qty" class="fas fa-check-circle update-btn" title="Update Quantity"></button>
                  </div>
               </div>
               <div style="margin-top: 10px; font-size: 14px; color: #999;">
                  Subtotal: <span style="color: var(--black); font-weight: bold;">₹<?= $sub_total; ?></span>
               </div>
            </div>

            <button type="submit" name="delete" class="fas fa-trash remove-item" onclick="return confirm('Remove this item?');"></button>
         </form>
         <?php
               }
            }else{
               echo '<div style="text-align:center; padding: 40px;"><img src="images/empty-cart.png" style="width:200px; opacity:0.5;"><p class="empty">Your cart is feeling lonely...</p></div>';
            }
         ?>
      </div>

      <div class="cart-summary">
         <h3 class="summary-title">Order Summary</h3>
         
         <div class="total-row">
            <span>Items Total</span>
            <span>₹<?= $grand_total; ?></span>
         </div>
         <div class="total-row">
            <span>Delivery</span>
            <span style="color: #27ae60;">FREE</span>
         </div>

         <div class="grand-total-row">
            <span>Total</span>
            <span>₹<?= $grand_total; ?></span>
         </div>

         <a href="checkout.php" class="checkout-btn <?= ($grand_total > 0)?'':'disabled'; ?>">
            Proceed to Checkout
         </a>

         <div style="margin-top: 20px; text-align: center;">
            <a href="menu.php" style="font-size: 14px; color: #666; text-decoration: underline;">Continue Shopping</a>
         </div>

         <?php if($grand_total > 0): ?>
         <form action="" method="post" style="margin-top: 20px;">
            <button type="submit" name="delete_all" class="delete-btn" style="width: 100%; padding: 10px; font-size: 14px;" onclick="return confirm('Empty your whole cart?');">Clear Cart</button>
         </form>
         <?php endif; ?>
      </div>

   </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
   // Instant Loader Killer
   (function() {
      const die = () => {
         const l = document.querySelector('.loader') || document.querySelector('#loader');
         if(l) l.remove();
      };
      die();
      window.onload = die;
   })();
</script>

</body>
</html>