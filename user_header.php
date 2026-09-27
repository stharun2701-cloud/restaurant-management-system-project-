<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<style>
   /* --- THE ULTIMATE LOADER KILLER --- */
   #loader, .loader, .preloader { display: none !important; opacity: 0 !important; visibility: hidden !important; pointer-events: none !important; }
   body { display: block !important; opacity: 1 !important; overflow: auto !important; }

   /* --- PREMIUM HEADER DESIGN --- */
   .header {
      position: sticky;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      background-color: var(--white);
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
   }

   .header .flex {
      padding: 1rem 2rem; /* Adjusted padding for logo height */
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
   }

   /* --- LOGO STYLING --- */
   .header .logo {
      display: flex;
      align-items: center;
   }

   .header .logo img {
      height: 5.5rem; /* Standardized height for navigation bar */
      width: auto;
      object-fit: contain;
      transition: transform 0.3s ease;
   }
    .header .logo span {
      color: var(--red);
   }
   .header .logo img:hover {
      transform: scale(1.05);
   }

   .header .navbar a {
      margin: 0 1.2rem;
      font-size: 1.8rem;
      color: var(--light-color);
      font-weight: 500;
      transition: 0.3s;
   }

   .header .navbar a:hover {
      color: var(--red);
      text-decoration: underline;
   }

   .header .icons div,
   .header .icons a {
      font-size: 2.2rem;
      color: var(--black);
      cursor: pointer;
      margin-left: 1.8rem;
      transition: 0.3s;
      position: relative;
   }

   .header .icons a span {
      position: absolute;
      top: -8px; right: -10px;
      background-color: var(--red);
      color: var(--white);
      font-size: 1.2rem;
      height: 2rem; width: 2rem;
      line-height: 2rem;
      border-radius: 50%;
      text-align: center;
      font-weight: 600;
   }

   .header .icons a:hover,
   .header .icons div:hover {
      color: var(--red);
   }

   /* Profile Dropdown Improvements */
   .header .profile {
      position: absolute;
      top: 110%; right: 2rem;
      background-color: var(--white);
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      padding: 2rem;
      width: 30rem;
      border: var(--border);
      display: none;
      animation: fadeIn 0.3s ease;
   }

   .header .profile.active {
      display: block;
   }

   @keyframes fadeIn {
      from { transform: translateY(10px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
   }
</style>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">
         <img src="project images/logo.jpeg" alt="Cuisine Cloud Logo">
         Cuisine <span>Cloud</span>
      </a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="menu.php">Menu</a>
         <a href="orders.php">Orders</a>
         <a href="catering.php">Catering</a>
         <a href="about.php">About</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php">
            <i class="fas fa-shopping-cart"></i>
            <span><?= $total_cart_items; ?></span>
         </a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name" style="font-weight: 700; color: var(--black);"><?= $fetch_profile['name']; ?></p>
         <div class="flex" style="padding: 1rem 0; justify-content: center; gap: 1rem;">
            <a href="profile.php" class="btn" style="font-size: 1.5rem; padding: 1rem 2rem;">Profile</a>
            <a href="components/user_logout.php" onclick="return confirm('Logout from Cuisine Cloud?');" class="delete-btn" style="font-size: 1.5rem; padding: 1rem 2rem;">Logout</a>
         </div>
         <?php
            }else{
         ?>
            <p class="name" style="margin-bottom: 1.5rem;">Welcome to Cuisine Cloud</p>
            <div class="flex" style="padding:0; justify-content: center; gap:1rem;">
               <a href="login.php" class="btn" style="font-size: 1.4rem;">Login</a>
               <a href="register.php" class="option-btn" style="font-size: 1.4rem;">Register</a>
            </div>
         <?php
          }
         ?>
      </div>

   </section>

</header>

<script>
   (function() {
      // 1. Loader Killer
      const removeLoader = () => {
         const loader = document.querySelector('.loader') || document.querySelector('#loader');
         if(loader) loader.remove();
         document.body.style.opacity = '1';
      };
      
      removeLoader();
      window.addEventListener('load', removeLoader);

      // 2. Profile and Navbar Toggles
      document.addEventListener('DOMContentLoaded', () => {
         let profile = document.querySelector('.header .flex .profile');
         let navbar = document.querySelector('.header .flex .navbar');

         document.querySelector('#user-btn').onclick = () =>{
            profile.classList.toggle('active');
            navbar.classList.remove('active');
         }

         document.querySelector('#menu-btn').onclick = () =>{
            navbar.classList.toggle('active');
            profile.classList.remove('active');
         }

         window.onscroll = () =>{
            profile.classList.remove('active');
            navbar.classList.remove('active');
         }
      });
   })();
</script>