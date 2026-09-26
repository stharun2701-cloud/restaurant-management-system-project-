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
   <title>Our Menu | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* --- GLOBAL LOADER KILLER --- */
      #loader, .loader, .preloader { display: none !important; visibility: hidden !important; }
      body { display: block !important; opacity: 1 !important; overflow: auto !important; }

      /* --- ENHANCED MENU STYLING --- */
      .products .box-container {
          display: grid;
          grid-template-columns: repeat(auto-fit, 33rem);
          justify-content: center;
          align-items: flex-start;
          gap: 2.5rem;
          padding: 2rem;
      }

      .products .box-container .box {
          background-color: var(--white);
          border-radius: 2rem;
          padding: 2rem;
          box-shadow: 0 1rem 2rem rgba(0,0,0,0.05);
          border: 1px solid #eee;
          position: relative;
          overflow: hidden;
          transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }

      .products .box-container .box:hover {
          transform: translateY(-1rem);
          box-shadow: 0 1.5rem 3rem rgba(0,0,0,0.1);
          border-color: var(--red);
      }

      .products .box-container .box img {
          width: 100%;
          height: 20rem;
          object-fit: contain;
          margin-bottom: 1.5rem;
      }

      .products .box-container .box .name {
          font-size: 2rem;
          color: var(--black);
          padding: 0.5rem 0;
          font-weight: 700;
      }

      /* --- CALORIES STYLING --- */
      .products .box-container .box .calories {
          font-size: 1.4rem;
          color: #666;
          margin-bottom: 1rem;
          display: flex;
          align-items: center;
          gap: 0.5rem;
      }

      .products .box-container .box .calories i {
          color: #ffa500; /* Orange flame color */
      }

      /* --- MODERN PILL QUANTITY SELECTOR --- */
      .qty-container {
          display: flex;
          align-items: center;
          justify-content: space-between;
          width: 130px; 
          margin-top: 1rem;
          background: #f0f2f5; 
          padding: 5px;
          border-radius: 50px; 
          box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
      }

      .qty-btn {
          height: 35px;
          width: 35px;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          border: none;
          border-radius: 50%; 
          background: var(--white);
          color: var(--black);
          font-size: 1.6rem;
          font-weight: bold;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          transition: all 0.3s ease;
      }

      .qty-btn:hover {
          background: var(--red);
          color: var(--white);
          transform: scale(1.1);
      }

      .products .box-container .box .qty {
          width: 40px;
          border: none;
          background: transparent;
          color: var(--black);
          font-size: 1.8rem;
          font-weight: 700;
          text-align: center;
          user-select: none;
          pointer-events: none;
      }

      .qty::-webkit-inner-spin-button, 
      .qty::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

      .products .box-container .box .flex {
          display: flex;
          flex-direction: column;
          gap: 1rem;
          align-items: flex-start;
          border-top: 1px solid #f0f0f0;
          padding-top: 1.5rem;
          margin-top: 1rem;
      }

      .products .box-container .box .price { font-size: 2.2rem; color: var(--black); font-weight: 800; }
      .products .box-container .box .price span { color: var(--red); }
      
      .products .box-container .box .cat {
          font-size: 1.4rem; color: var(--red); font-weight: 600;
          background: #fff5f5; padding: 0.5rem 1.2rem; border-radius: 2rem;
      }

      /* Action Buttons */
      .products .box-container .box .fa-eye,
      .products .box-container .box .fa-shopping-cart {
          position: absolute; top: 1.5rem; height: 4.5rem; width: 4.5rem;
          line-height: 4.5rem; font-size: 2rem; background-color: var(--white);
          color: var(--black); border-radius: 50%; text-align: center;
          box-shadow: 0 .5rem 1rem rgba(0,0,0,0.1); transition: 0.3s;
      }
      .products .box-container .box .fa-eye { left: -10rem; }
      .products .box-container .box .fa-shopping-cart { right: -10rem; }
      .products .box-container .box:hover .fa-eye { left: 1.5rem; }
      .products .box-container .box:hover .fa-shopping-cart { right: 1.5rem; }
      .products .box-container .box .fa-eye:hover,
      .products .box-container .box .fa-shopping-cart:hover { background-color: var(--red); color: var(--white); }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Explore Our Menu</h3>
   <p><a href="home.php">home</a> <span> / menu</span></p>
</div>

<section class="products">
   <h1 class="title">Our Latest Dishes</h1>
   <div class="box-container">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` ");
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
         
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat">
            <i class="fas fa-tag"></i> <?= $fetch_products['category']; ?>
         </a>
         
         <div class="name"><?= $fetch_products['name']; ?></div>

         <div class="calories">
            <i class="fas fa-fire"></i> 
            <span><?= $fetch_products['calories']; ?> kcal</span>
         </div>
         
         <div class="flex">
            <div class="price"><span>₹</span><?= $fetch_products['price']; ?></div>
            
            <div class="qty-container">
               <button type="button" class="qty-btn minus-btn">-</button>
               <input type="number" name="qty" class="qty" min="1" max="99" value="1" readonly>
               <button type="button" class="qty-btn plus-btn">+</button>
            </div>
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No dishes found in the kitchen!</p>';
         }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
   document.addEventListener('click', (e) => {
      if (e.target.classList.contains('plus-btn')) {
         const input = e.target.parentElement.querySelector('.qty');
         if (parseInt(input.value) < 99) input.value = parseInt(input.value) + 1;
      }
      if (e.target.classList.contains('minus-btn')) {
         const input = e.target.parentElement.querySelector('.qty');
         if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
      }
   });

   (function() {
      const killLoader = () => {
         const loader = document.querySelector('.loader') || document.querySelector('#loader');
         if(loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 500);
         }
      };
      killLoader();
      window.addEventListener('load', killLoader);
   })();
</script>

</body>
</html>