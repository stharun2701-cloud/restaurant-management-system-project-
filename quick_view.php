<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick View - Product Details</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      :root {
          --primary-color: #e74c3c;
          --dark-color: #333;
          --light-bg: #f9f9f9;
          --shadow: 0 10px 30px rgba(0,0,0,0.08);
          --green: #27ae60;
          --white: #fff;
      }

      .quick-view {
          padding: 60px 20px;
          background: var(--light-bg);
          min-height: 80vh;
      }

      .back-btn-container {
          max-width: 1000px;
          margin: 0 auto 20px auto;
          display: flex;
      }

      .back-link {
          display: inline-flex;
          align-items: center;
          gap: 10px;
          background: #fff;
          color: var(--dark-color);
          padding: 10px 20px;
          border-radius: 10px;
          font-size: 16px;
          font-weight: 600;
          box-shadow: 0 4px 10px rgba(0,0,0,0.05);
          transition: 0.3s;
          border: 1px solid #eee;
      }

      .back-link:hover {
          background: var(--dark-color);
          color: #fff;
          transform: translateX(-5px);
      }

      .quick-view .box {
          max-width: 1000px;
          margin: 0 auto;
          display: grid;
          grid-template-columns: 1.2fr 1fr;
          gap: 50px;
          align-items: center;
          background: #fff;
          padding: 50px;
          border-radius: 25px;
          box-shadow: var(--shadow);
          position: relative;
      }

      .quick-view .box img {
          width: 100%;
          height: 450px;
          object-fit: contain;
          border-radius: 20px;
          transition: 0.4s;
      }

      .product-content { display: flex; flex-direction: column; gap: 18px; }

      .badge-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

      .quick-view .box .cat {
          font-size: 13px;
          color: var(--primary-color);
          background: #fff0f0;
          padding: 6px 16px;
          border-radius: 50px;
          font-weight: 700;
          text-transform: uppercase;
      }

      .calorie-badge {
          font-size: 13px;
          background: #fffde7;
          color: #9a7d0a;
          padding: 6px 16px;
          border-radius: 50px;
          font-weight: 700;
          border: 1px solid #fff9c4;
          display: inline-flex;
          align-items: center;
          gap: 5px;
      }

      .quick-view .box .name { font-size: 36px; color: var(--dark-color); font-weight: 800; }
      .quick-view .box .price { font-size: 30px; color: var(--primary-color); font-weight: 700; }

      .health-note {
          font-size: 15px; color: #666; line-height: 1.6; padding: 15px;
          background: #fdfdfd; border-left: 4px solid var(--green); border-radius: 8px;
      }

      /* --- UPDATED QUANTITY SELECTOR STYLE --- */
      .qty-flex {
          display: flex;
          align-items: center;
          gap: 20px;
          margin: 10px 0;
      }

      .qty-container {
          display: flex;
          align-items: center;
          justify-content: space-between;
          width: 140px;
          background: #f0f2f5;
          padding: 5px;
          border-radius: 50px;
          box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
      }

      .qty-btn {
          height: 40px;
          width: 40px;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          border: none;
          border-radius: 50%;
          background: var(--white);
          color: var(--dark-color);
          font-size: 18px;
          font-weight: bold;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          transition: 0.3s;
      }

      .qty-btn:hover { background: var(--primary-color); color: var(--white); }

      .quick-view .box .qty {
          width: 40px;
          border: none;
          background: transparent;
          color: var(--dark-color);
          font-size: 20px;
          font-weight: 700;
          text-align: center;
          pointer-events: none;
      }

      .qty::-webkit-inner-spin-button, 
      .qty::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

      .cart-btn {
          background: var(--primary-color); color: #fff; font-size: 18px; padding: 18px;
          border-radius: 15px; cursor: pointer; transition: 0.3s ease; border: none;
          font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 12px;
          width: 100%;
      }

      .cart-btn:hover { background: var(--dark-color); transform: translateY(-3px); }

      @media (max-width: 991px) {
          .quick-view .box { grid-template-columns: 1fr; padding: 30px; }
          .quick-view .box img { height: 350px; }
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <div class="back-btn-container">
      <a href="javascript:history.back()" class="back-link">
         <i class="fas fa-arrow-left"></i> Go Back
      </a>
   </div>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
      
      <div class="image-container">
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="<?= $fetch_products['name']; ?>">
      </div>

      <div class="product-content">
         <div class="badge-row">
            <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat">
               <i class="fas fa-tag"></i> <?= $fetch_products['category']; ?>
            </a>
            <span class="calorie-badge">
               <i class="fas fa-fire"></i> <?= $fetch_products['calories']; ?> kcal
            </span>
         </div>

         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="price">₹<?= $fetch_products['price']; ?></div>

         <div class="health-note">
            <p><i class="fas fa-leaf" style="color: var(--green);"></i> <strong>Nutrition Info:</strong> This meal contains approximately <strong><?= $fetch_products['calories']; ?> calories</strong> per serving.</p>
         </div>
         
         <div class="qty-flex">
            <span style="font-size: 16px; font-weight: 600; color: #666;">Select Quantity:</span>
            <div class="qty-container">
               <button type="button" class="qty-btn minus-btn">-</button>
               <input type="number" name="qty" class="qty" min="1" max="99" value="1" readonly>
               <button type="button" class="qty-btn plus-btn">+</button>
            </div>
         </div>

         <button type="submit" name="add_to_cart" class="cart-btn">
            <i class="fas fa-shopping-cart"></i> Add To Cart
         </button>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Product not found!</p>';
      }
   ?>

</section>

<?php include 'components/footer.php'; ?>

<script>
   // Quantity adjustment logic
   document.addEventListener('click', (e) => {
      if (e.target.classList.contains('plus-btn')) {
         const input = e.target.parentElement.querySelector('.qty');
         let val = parseInt(input.value);
         if (val < 99) input.value = val + 1;
      }
      if (e.target.classList.contains('minus-btn')) {
         const input = e.target.parentElement.querySelector('.qty');
         let val = parseInt(input.value);
         if (val > 1) input.value = val - 1;
      }
   });
</script>

</body>
</html>