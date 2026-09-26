<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

// Logic to handle feedback submission
if(isset($_POST['send_feedback'])){
   if($user_id == ''){
      $message[] = 'please login first!';
   }else{
      $name = htmlspecialchars($_POST['name']);
      $msg = htmlspecialchars($_POST['msg']);
      $rating = (int)$_POST['rating'];

      // Note: status is 'pending' by default so you can approve it in admin panel
      $insert_feedback = $conn->prepare("INSERT INTO `messages`(user_id, name, message, rating, status) VALUES(?,?,?,?,?)");
      $insert_feedback->execute([$user_id, $name, $msg, $rating, 'pending']);
      $message[] = 'feedback sent! it will appear after admin approval.';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      :root {
          --primary: #e74c3c;
          --dark: #2d3436;
          --white: #ffffff;
          --shadow: 0 1rem 2rem rgba(0,0,0,0.05);
      }

      #loader, .loader, .preloader { display: none !important; }
      body { display: block !important; opacity: 1 !important; background: #f9f9f9; }

      /* --- MODERN STEPS --- */
      .steps { background: var(--white); padding: 5rem 2rem; }
      .steps .box-container {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(28rem, 1fr));
          gap: 3rem;
          max-width: 1200px;
          margin: 0 auto;
      }
      .steps .box-container .box {
          position: relative;
          padding: 4rem 3rem;
          background: var(--white);
          border-radius: 2rem;
          text-align: center;
          border: 1px solid #f0f0f0;
          transition: 0.3s;
      }
      .steps .box-container .box:hover { transform: translateY(-10px); }
      .steps .box-container .box::before {
          content: attr(data-step);
          position: absolute;
          top: -20px;
          left: 50%;
          transform: translateX(-50%);
          height: 50px; width: 50px; line-height: 50px;
          background: var(--primary);
          color: #fff; border-radius: 50%;
          font-size: 2rem; font-weight: 800;
          box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
      }
      .steps .box-container .box img { height: 12rem; margin-bottom: 2rem; }

      /* --- ABOUT ROW --- */
      .about { padding: 8rem 2rem; background: linear-gradient(to bottom, #fff, #f8f9fa); }
      .about .row {
          display: flex; align-items: center; flex-wrap: wrap;
          gap: 6rem; max-width: 1200px; margin: 0 auto;
      }
      .about .row .image { flex: 1 1 45rem; }
      .about .row .image img { width: 100%; animation: float 4s ease-in-out infinite; }
      @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
      .about .row .content { flex: 1 1 45rem; }
      .about .row .content .tag { color: var(--primary); font-size: 1.6rem; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 1rem; }
      .about .row .content h3 { font-size: 4.5rem; color: var(--dark); line-height: 1.1; margin-bottom: 2rem; }
      .about .row .content p { font-size: 1.8rem; color: #666; line-height: 1.8; margin-bottom: 3rem; border-left: 5px solid var(--primary); padding-left: 2rem; }

      .about-features { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3.5rem; }
      .about-features .feat-item { display: flex; align-items: center; gap: 1.5rem; font-size: 1.6rem; color: var(--dark); font-weight: 600; }
      .about-features .feat-item i { color: var(--primary); font-size: 2rem; }

      /* --- OWNER SECTION --- */
      .owner-section { padding: 8rem 2rem; background: #fff; }
      .owner-container {
          max-width: 1200px; margin: 0 auto; display: flex; align-items: center;
          flex-wrap: wrap; gap: 4rem; background: #fdfdfd; padding: 4rem;
          border-radius: 3rem; box-shadow: var(--shadow);
      }
      .owner-image img { height: 35rem; width: 35rem; object-fit: cover; border-radius: 2rem; border: 1rem solid #fff; box-shadow: var(--shadow); }
      .owner-content { flex: 1 1 50rem; }
      .owner-content h3 { font-size: 3.5rem; color: var(--dark); margin: 1rem 0; }
      .owner-content p { font-size: 1.7rem; color: #666; line-height: 2; margin-bottom: 2rem; }
      .owner-socials { display: flex; gap: 1.5rem; }
      .owner-socials a { height: 5rem; width: 5rem; line-height: 5rem; text-align: center; font-size: 2rem; background: #fff; color: var(--dark); border-radius: 50%; box-shadow: var(--shadow); transition: 0.3s; }
      .owner-socials a:hover { background: var(--primary); color: #fff; transform: translateY(-5px); }

      /* --- FEEDBACK & REVIEWS --- */
      .feedback-form { max-width: 800px; margin: 5rem auto; background: #fff; padding: 3rem; border-radius: 2rem; box-shadow: var(--shadow); }
      .feedback-form .box { width: 100%; background: #f4f4f4; border-radius: .5rem; padding: 1.4rem; font-size: 1.8rem; margin: 1rem 0; border: none; }
      .reviews .slide { background: #fff; padding: 3rem; border-radius: 2rem; text-align: center; border: 1px solid #eee; margin-bottom: 4rem; transition: .3s; }
      .reviews .stars { background: #fff9c4; display: inline-block; padding: .5rem 1.5rem; border-radius: 5rem; margin-bottom: 1rem; }
      .reviews .stars i { color: #fbc02d; font-size: 1.4rem; }
      .empty { font-size: 2rem; color: var(--primary); text-align: center; width: 100%; padding: 2rem; }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>About Us</h3>
   <p><a href="home.php">home</a> <span> / about</span></p>
</div>

<section class="owner-section">
   <h1 class="title">Meet the Visionary</h1>
   <div class="owner-container">
      <div class="owner-image">
         <img src="images/owner.jpeg" alt="Founder">
      </div>
      <div class="owner-content">
         <span>Founder & CEO</span>
         <h3>The Minds Behind Cuisine Cloud</h3>
         <p>What started as a simple late-night craving during college dorm sessions turned into a mission to revolutionize how we experience food. As a former college student, I understood the gap between "fast food" and "quality food."</p>
         <p>Cuisine Cloud was built on the principle that excellence shouldn't be a luxury. Every dish we serve is a step toward a sustainable, delicious future.</p>
         <div class="owner-socials">
            <a href="#" class="fab fa-linkedin"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="mailto:owner@cuisinecloud.com" class="fas fa-envelope"></a>
         </div>
      </div>
   </div>
</section>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>
      <div class="content">
         <span class="tag">Since 2026</span>
         <h3>Why Choose Cuisine Cloud?</h3>
         <p>"From a college student's vision to a growing reality, we serve excellence on a plate. Food is symbolic of love when words are inadequate."</p>
         <div class="about-features">
            <div class="feat-item"><i class="fas fa-check-circle"></i> Best Quality</div>
            <div class="feat-item"><i class="fas fa-check-circle"></i> 24/7 Service</div>
            <div class="feat-item"><i class="fas fa-check-circle"></i> Free Delivery</div>
            <div class="feat-item"><i class="fas fa-check-circle"></i> Sustainable</div>
         </div>
         <a href="menu.php" class="btn">explore menu</a>
      </div>
   </div>
</section>

<section class="steps">
   <h1 class="title">How it Works</h1>
   <div class="box-container">
      <div class="box" data-step="1">
         <img src="images/step-1.png" alt="">
         <h3>Select Your Feast</h3>
         <p>Browse our extensive menu of curated cuisines and pick your favorites.</p>
      </div>
      <div class="box" data-step="2">
         <img src="images/step-2.png" alt="">
         <h3>Lightning Delivery</h3>
         <p>Our riders ensure your meal reaches you steaming hot in record time.</p>
      </div>
      <div class="box" data-step="3">
         <img src="images/step-3.png" alt="">
         <h3>Taste the Magic</h3>
         <p>Unpack, serve, and enjoy the premium quality flavor of Cuisine Cloud.</p>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="title">Foodie Feedbacks</h1>
   <div class="swiper reviews-slider">
      <div class="swiper-wrapper">
         <?php
            // Pulling only APPROVED reviews from the database
            $select_reviews = $conn->prepare("SELECT * FROM `messages` WHERE status = 'approved' ORDER BY id DESC");
            $select_reviews->execute();
            if($select_reviews->rowCount() > 0){
               while($fetch_reviews = $select_reviews->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="swiper-slide slide">
            <div class="stars">
               <?php 
                  $rating = $fetch_reviews['rating'] ?? 5;
                  for($i=1; $i<=5; $i++){
                     echo ($i <= $rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                  }
               ?>
            </div>
            <p>"<?= $fetch_reviews['message']; ?>"</p>
            <h3><?= $fetch_reviews['name']; ?></h3>
         </div>
         <?php
               }
            }else{
               echo '<p class="empty">No approved reviews yet!</p>';
            }
         ?>
      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>

<section class="feedback-container">
   <div class="feedback-form">
      <h3>Share Your Experience</h3>
      <form action="" method="post">
         <input type="text" name="name" required placeholder="enter your name" class="box" maxlength="50">
         <select name="rating" class="box" required>
            <option value="5">5 Stars (Excellent)</option>
            <option value="4">4 Stars (Good)</option>
            <option value="3">3 Stars (Average)</option>
            <option value="2">2 Stars (Need improvement)</option>
            <option value="1">1 Star (Poor)</option>
         </select>
         <textarea name="msg" class="box" required placeholder="your feedback..." maxlength="500"></textarea>
         <input type="submit" value="send feedback" name="send_feedback" class="btn">
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
var swiper = new Swiper(".reviews-slider", {
   loop: true, 
   grabCursor: true, 
   spaceBetween: 20,
   pagination: { el: ".swiper-pagination", clickable:true },
   breakpoints: { 
      0: { slidesPerView: 1 }, 
      700: { slidesPerView: 2 }, 
      1024: { slidesPerView: 3 } 
   },
});
</script>

</body>
</html>