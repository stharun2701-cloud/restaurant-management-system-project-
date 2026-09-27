<?php
// Displaying alerts/messages with updated styling
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
   .header {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0,0,0,0.1);
      position: sticky;
      top: 0; z-index: 1000;
   }

   .header .flex {
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
      padding: 1.5rem 2rem;
   }

   .header .flex .logo {
      font-size: 2.5rem;
      color: var(--black);
      font-weight: 800;
      text-transform: uppercase;
   }

   .header .flex .logo span {
      color: var(--primary-color);
   }

   .header .navbar a {
      margin: 0 1rem;
      font-size: 1.8rem;
      color: var(--black);
      font-weight: 500;
      transition: 0.3s;
      position: relative;
   }

   .header .navbar a:hover {
      color: var(--primary-color);
   }

   /* --- New Home Button Styling --- */
   .header .navbar a.home-btn {
      background-color: var(--primary-color);
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 0.5rem;
      margin-left: 2rem;
   }

   .header .navbar a.home-btn:hover {
      background-color: var(--black);
      color: white;
   }

   /* Notification Badge Styling */
   .badge {
      position: absolute;
      top: -5px;
      right: -10px;
      background: var(--primary-color);
      color: white;
      font-size: 1rem;
      padding: 2px 5px;
      border-radius: 50%;
      font-weight: bold;
   }

   .profile {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(15px);
      border: 1px solid rgba(0,0,0,0.1) !important;
      border-radius: 2rem !important;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
   }
</style>

<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="dashboard.php">home</a>
         <a href="products.php">products</a>
         <a href="placed_orders.php">orders</a>
         
         <a href="catering_orders.php">catering
            <?php
               // Use isset to prevent the $conn undefined error
               if(isset($conn)){
                  $select_cat_count = $conn->prepare("SELECT * FROM `catering` WHERE status = ?");
                  $select_cat_count->execute(['pending']);
                  if($select_cat_count->rowCount() > 0){
                     echo '<span class="badge">'.$select_cat_count->rowCount().'</span>';
                  }
               }
            ?>
         </a>

         <a href="admin_accounts.php">admins</a>
         <a href="users_accounts.php">users</a>
         <a href="messages.php">messages</a>

         <a href="../home.php" class="home-btn"><i class="fas fa-external-link-alt"></i> view website</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            if(isset($conn)){
               $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_profile->execute([$admin_id]);
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            }
         ?>
         <p style="font-weight: 700; font-size: 2rem; color: var(--black);"><?= $fetch_profile['name'] ?? 'Admin'; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn">login</a>
            <a href="register_admin.php" class="option-btn">register</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
      </div>

   </section>

</header>