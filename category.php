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
   <title>Category - Food Menu</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      :root {
         --primary: #e74c3c;
         --dark: #2d3436;
         --light-gray: #f4f7f6;
         --shadow: 0 10px 20px rgba(0,0,0,0.05);
      }

      body { background-color: var(--light-gray); }

      /* Container for the Back Button to keep it aligned left with the grid */
      .top-action-bar {
         max-width: 1200px;
         margin: 20px auto;
         padding: 0 20px;
         display: flex;
         justify-content: flex-start;
      }

      .back-home-btn {
         display: inline-flex;
         align-items: center;
         gap: 10px;
         background: #fff;
         color: var(--dark);
         padding: 12px 20px;
         border-radius: 12px;
         font-size: 16px;
         font-weight: 600;
         box-shadow: var(--shadow);
         transition: 0.3s;
         border: 1px solid #eee;
      }

      .back-home-btn:hover {
         background: var(--primary);
         color: #fff;
         transform: translateX(-5px);
      }

      /* Unified Grid System */
      .products .box-container {
         max-width: 1200px;
         margin: 0 auto;
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
         gap: 30px;
         padding: 20px;
         align-items: stretch; /* Ensures boxes in the same row have equal height */
      }

      /* Modern Box Styling */
      .products .box {
         background: #fff;
         border-radius: 25px;
         padding: 25px;
         box-shadow: var(--shadow);
         position: relative;
         display: flex;
         flex-direction: column; /* Vertical alignment */
         justify-content: space-between; /* Pushes price/qty to the bottom */
         transition: 0.3s ease;
         border: 1px solid #f0f0f0;
         text-align: center;
      }

      .products .box:hover {
         transform: translateY(-10px);
         box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      }

      /* Image containment to prevent overlap */
      .products .box img {
         width: 100%;
         height: 180px;
         object-fit: contain;
         margin: 15px 0;
      }

      .cal-badge {
         position: absolute;
         top: 15px;
         left: 15px;
         background: #fffde7;
         color: #fbc02d;
         padding: 5px 12px;
         border-radius: 50px;
         font-size: 13px;
         font-weight: 700;
         border: 1px solid #fff9c4;
      }

      /* Eye and Cart icons */
      .products .box .fa-eye,
      .products .box .fa-shopping-cart {
         position: absolute;
         top: 15px;
         right: 15px;
         height: 40px;
         width: 40px;
         line-height: 40px;
         font-size: 18px;
         background: #fff;
         color: var(--dark);
         border-radius: 50%;
         box-shadow: 0 5px 10px rgba(0,0,0,0.05);
      }

      .products .box .fa-shopping-cart {
         top: 65px;
         background: var(--primary);
         color: #fff;
      }

      .products .box .fa-eye:hover {
         background: var(--dark);
         color: #fff;
      }

      .products .box .name {
         font-size: 20px;
         color: var(--dark);
         font-weight: 700;
         margin: 10px 0;
         /* Limits text to 1 line to keep alignment perfect */
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
      }

      /* Bottom section alignment */
      .products .box .flex {
         display: flex;
         align-items: center;
         justify-content: space-between;
         margin-top: 15px;
         padding-top: 15px;
         border-top: 1px solid #f9f9f9;
      }

      .products .box .price {
         font-size: 22px;
         color: var(--primary);
         font-weight: 800;
      }

      .products .box .qty {
         width: 65px;
         padding: 8px;
         border: 1.5px solid #eee;
         border-radius: 10px;
         font-size: 16px;
         font-weight: 700;
         text-align: center;
      }

      .title {
         text-align: center;
         margin-bottom: 20px;
         text-transform: capitalize;
         font-size: 30px;
         color: var(--dark);
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="top-action-bar">
   <a href="home.php" class="back-home-btn">
      <i class="fas fa-home"></i> Back to Home
   </a>
</div>

<section class="products">

   <h1 class="title"><?= htmlspecialchars($_GET['category']); ?> Menu</h1>

   <div class="box-container">

      <?php
         $category = $_GET['category'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
         $select_products->execute([$category]);
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         
         <div class="cal-badge">
            <i class="fas fa-fire"></i> <?= $fetch_products['calories']; ?> kcal
         </div>
         
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         
         <div class="name"><?= $fetch_products['name']; ?></div>
         
         <div class="flex">
            <div class="price"><span>₹</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No products found in this category!</p>';
         }
      ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>