<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
   exit();
}

/* FETCH USER PROFILE */
$select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

/* SUSTAINABILITY DATA */
$total_bags = $fetch_profile['total_bags_returned'] ?? 0;
$badge = "Eco Explorer";
$badge_color = "#95a5a6";
$badge_icon = "fa-seedling";

if($total_bags >= 50){
   $badge = "Gold Eco Warrior";
   $badge_color = "#f1c40f";
   $badge_icon = "fa-crown";
}
elseif($total_bags >= 25){
   $badge = "Silver Eco Supporter";
   $badge_color = "#bdc3c7";
   $badge_icon = "fa-medal";
}
elseif($total_bags >= 10){
   $badge = "Bronze Eco Starter";
   $badge_color = "#cd7f32";
   $badge_icon = "fa-award";
}

$progress_percent = min(($total_bags / 50) * 100, 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Profile | Cuisine Cloud</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      #loader, .loader { display: none !important; }
      body { display: block !important; }

      .user-details .user {
         max-width: 65rem;
         margin: 2rem auto;
         background: var(--white);
         border-radius: 2rem;
         padding: 4rem;
         box-shadow: 0 1.5rem 4rem rgba(0,0,0,0.1);
         border: 1px solid #f0f0f0;
         position: relative; /* For positioning the back button */
      }

      /* --- BACK BUTTON STYLE --- */
      .back-btn {
         position: absolute;
         top: 2rem;
         left: 2rem;
         font-size: 1.6rem;
         color: var(--light-color);
         display: flex;
         align-items: center;
         gap: 0.8rem;
         transition: 0.3s;
      }

      .back-btn:hover {
         color: var(--red);
         transform: translateX(-5px);
      }

      .user-details .user .profile-pic {
         height: 12rem;
         width: 12rem;
         border-radius: 50%;
         margin: 1rem auto 3rem;
         display: block;
         border: 4px solid var(--red);
         padding: 5px;
      }

      /* --- PERFECT ALIGNMENT GRID --- */
      .info-grid {
         display: grid;
         grid-template-columns: 5rem 1fr 2fr;
         align-items: center;
         gap: 1.5rem;
         margin-bottom: 3rem;
         text-align: left;
      }

      .info-grid i {
         font-size: 2rem;
         color: var(--red);
         text-align: center;
      }

      .info-grid .label {
         font-size: 1.6rem;
         color: var(--light-color);
         font-weight: 600;
         text-transform: uppercase;
      }

      .info-grid .value {
         font-size: 1.8rem;
         color: var(--black);
         word-break: break-all;
      }

      /* --- BUTTONS --- */
      .btn-container {
         display: flex;
         gap: 1.5rem;
         margin-bottom: 3rem;
      }
      .btn-container .btn { flex: 1; margin: 0; }

      /* --- ECO BOX --- */
      .eco-card {
         background: #f0fff4;
         border: 1px solid #c6f6d5;
         padding: 2.5rem;
         border-radius: 1.5rem;
         text-align: center;
      }

      .eco-card h3 { color: #2f855a; font-size: 2rem; margin-bottom: 1.5rem; }

      .badge-tag {
         display: inline-flex;
         align-items: center;
         gap: 1rem;
         padding: 0.8rem 2rem;
         border-radius: 5rem;
         color: #fff;
         font-weight: 700;
         font-size: 1.4rem;
         margin-bottom: 2rem;
      }

      .progress-container {
         background: #e2e8f0;
         height: 1rem;
         border-radius: 1rem;
         margin: 1.5rem 0;
         overflow: hidden;
      }

      .progress-bar {
         height: 100%;
         background: #38a169;
         transition: 1s ease-in-out;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="user-details">

   <div class="user">
      
      <a href="home.php" class="back-btn">
         <i class="fas fa-arrow-left"></i> back to home
      </a>

      <img src="images/user-icon.png" alt="" class="profile-pic">

      <div class="info-grid">
         <i class="fas fa-user"></i>
         <span class="label">Name</span>
         <span class="value"><?= $fetch_profile['name']; ?></span>

         <i class="fas fa-phone"></i>
         <span class="label">Phone</span>
         <span class="value"><?= $fetch_profile['number']; ?></span>

         <i class="fas fa-envelope"></i>
         <span class="label">Email</span>
         <span class="value"><?= $fetch_profile['email']; ?></span>

         <i class="fas fa-map-marker-alt"></i>
         <span class="label">Location</span>
         <span class="value">
            <?= ($fetch_profile['address'] == '') ? '<i style="color:#999">Not set</i>' : $fetch_profile['address']; ?>
         </span>
      </div>

      <div class="btn-container">
         <a href="update_profile.php" class="btn">Update Profile</a>
         <a href="update_address.php" class="btn">Update Address</a>
      </div>

      <div class="eco-card">
         <h3>🌱 Sustainability Impact</h3>
         <div class="badge-tag" style="background: <?= $badge_color; ?>;">
            <i class="fas <?= $badge_icon; ?>"></i> <?= $badge; ?>
         </div>
         
         <p style="font-size: 1.6rem; color: #4a5568;">
            Bags Returned: <strong><?= $total_bags; ?></strong>
         </p>

         <div class="progress-container">
            <div class="progress-bar" style="width: <?= $progress_percent; ?>%;"></div>
         </div>
         <p style="font-size: 1.2rem; color: #718096;">Milestone: <?= round($progress_percent); ?>% towards next rank</p>
      </div>
   </div>

</section>

<?php include 'components/footer.php'; ?>

</body>
</html>