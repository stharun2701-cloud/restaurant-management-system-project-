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
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* --- EMERGENCY LOADER KILLER --- */
      #loader, .loader, .preloader { display: none !important; visibility: hidden !important; }
      body { display: block !important; opacity: 1 !important; overflow: auto !important; }

      /* --- HERO SECTION TWEAKS --- */
      .hero .slide {
         display: flex;
         align-items: center;
         flex-wrap: wrap-reverse;
         gap: 2rem;
         padding-bottom: 4rem;
         padding-top: 2rem;
      }

      /* --- MODERN CATEGORY GLASSMORPHISM --- */
      .category .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
         gap: 2rem;
      }

      .category .box-container .box {
         padding: 2rem;
         text-align: center;
         border: 1px solid #eee;
         background: #fff;
         border-radius: 1.5rem;
         transition: .3s cubic-bezier(.4, 0, .2, 1);
         box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      }

      .category .box-container .box:hover {
         background-color: var(--black);
         transform: translateY(-10px);
      }

      .category .box-container .box:hover h3 {
         color: var(--white);
      }

      /* --- PRODUCT CARD ENHANCEMENTS --- */
      .products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, 33rem);
         justify-content: center;
         align-items: flex-start;
         gap: 2rem;
      }

      .products .box-container .box {
         position: relative;
         background-color: var(--white);
         padding: 2rem;
         border-radius: 1.5rem;
         border: var(--border);
         box-shadow: var(--box-shadow);
         overflow: hidden;
         transition: .3s;
      }

      .products .box-container .box:hover {
         box-shadow: 0 1rem 3rem rgba(0,0,0,0.15);
         transform: scale(1.02);
      }

      /* --- CALORIES BADGE STYLE --- */
      .products .box-container .box .calories {
         display: inline-block;
         padding: 0.5rem 1rem;
         background: rgba(243, 156, 18, 0.1);
         color: #f39c12; /* Calorie Orange */
         font-size: 1.4rem;
         font-weight: 600;
         border-radius: .5rem;
         margin: 1rem 0;
      }

      .products .box-container .box .calories i {
         margin-right: .5rem;
      }

      .products .box-container .box .fa-eye,
      .products .box-container .box .fa-shopping-cart {
         position: absolute;
         top: 1.5rem;
         height: 4.5rem;
         width: 4.5rem;
         line-height: 4.5rem;
         font-size: 2rem;
         background-color: #f5f5f5;
         color: var(--black);
         border-radius: .5rem;
         text-align: center;
         transition: .2s;
         z-index: 10;
      }

      .products .box-container .box .fa-eye { left: -10rem; }
      .products .box-container .box .fa-shopping-cart { right: -10rem; }

      .products .box-container .box:hover .fa-eye { left: 1.5rem; }
      .products .box-container .box:hover .fa-shopping-cart { right: 1.5rem; }

      .products .box-container .box .fa-eye:hover,
      .products .box-container .box .fa-shopping-cart:hover {
         background-color: var(--red);
         color: var(--white);
      }

      .products .box-container .box .cat {
         font-size: 1.5rem;
         color: var(--red);
         font-weight: bold;
         display: block;
      }

      .products .box-container .box .qty {
         padding: 1rem;
         border-radius: .5rem;
         border: var(--border);
         width: 7rem;
         font-size: 1.7rem;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="hero">
   <div class="swiper hero-slider">
      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
               <span>Premium Quality</span>
               <h3>Delicious Pizza</h3>
               <a href="menu.php" class="btn">Explore Menu</a>
            </div>
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Fast Delivery</span>
               <h3>Cheesy Burger</h3>
               <a href="menu.php" class="btn">Explore Menu</a>
            </div>
            <div class="image">
               <img src="images/home-img-2.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Freshly Prepared</span>
               <h3>Roasted Chicken</h3>
               <a href="menu.php" class="btn">Explore Menu</a>
            </div>
            <div class="image">
               <img src="images/home-img-3.png" alt="">
            </div>
         </div>

      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>

<section class="category">
   <h1 class="title">What's on your mind?</h1>
   <div class="box-container">
      <a href="category.php?category=fast food" class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Fast Food</h3>
      </a>
      <a href="category.php?category=main dish" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Main Dishes</h3>
      </a>
      <a href="category.php?category=drinks" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Drinks</h3>
      </a>
      <a href="category.php?category=desserts" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Desserts</h3>
      </a>
   </div>
</section>

<section class="products">
   <h1 class="title">Latest Delicacies</h1>
   <div class="box-container">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         
         <div class="calories"><i class="fas fa-fire"></i> <?= $fetch_products['calories']; ?> kcal</div>
         
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>₹</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>
   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn">See Full Menu</a>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>
   var swiper = new Swiper(".hero-slider", {
      loop:true,
      grabCursor: true,
      effect: "flip",
      pagination: {
         el: ".swiper-pagination",
         clickable:true,
      },
   });
</script>

</body>
</html>