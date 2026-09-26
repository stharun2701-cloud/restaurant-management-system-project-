<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

   // Check if the exact same order was already submitted to prevent duplicates
   $select_catering = $conn->prepare("SELECT * FROM `catering` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_catering->execute([$name, $email, $number, $msg]);

   if($select_catering->rowCount() > 0){
      $message[] = 'Order request already sent!';
   }else{
      // Insert into the 'catering' table instead of 'messages'
      $insert_catering = $conn->prepare("INSERT INTO `catering`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_catering->execute([$user_id, $name, $email, $number, $msg]);
      $message[] = 'Catering request sent successfully!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bulk order| Cuisine Cloud</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* --- GLOBAL LOADER KILLER --- */
      #loader, .loader, .preloader { display: none !important; visibility: hidden !important; }
      body { display: block !important; opacity: 1 !important; }

      /* --- ENHANCED CONTACT STYLING --- */
      .contact .row {
         display: flex;
         align-items: center;
         flex-wrap: wrap;
         gap: 3rem;
         background-color: #f9f9f9;
         padding: 4rem 2rem;
         border-radius: 2rem;
      }

      .contact .row .image {
         flex: 1 1 40rem;
      }

      .contact .row .image img {
         width: 100%;
         filter: drop-shadow(0 1rem 2rem rgba(0,0,0,0.1));
      }

      .contact .row form {
         flex: 1 1 40rem;
         background-color: var(--white);
         padding: 3rem;
         border-radius: 1.5rem;
         box-shadow: 0 1rem 3rem rgba(0,0,0,0.05);
         border: 1px solid #eee;
      }

      .contact .row form h3 {
         font-size: 2.5rem;
         color: var(--black);
         margin-bottom: 2rem;
         text-transform: capitalize;
         font-weight: 700;
      }

      .contact .row form .box {
         width: 100%;
         background-color: #f5f5f5;
         padding: 1.4rem;
         font-size: 1.6rem;
         color: var(--black);
         margin: 1rem 0;
         border-radius: .8rem;
         border: 1px solid transparent;
         transition: 0.3s;
      }

      .contact .row form .box:focus {
         background-color: var(--white);
         border-color: var(--red);
         box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
      }

      .contact .row form textarea {
         height: 15rem;
         resize: none;
      }

      .contact .row form .btn {
         width: 100%;
         background-color: var(--red);
         color: var(--white);
         margin-top: 1.5rem;
         border-radius: .8rem;
         font-weight: 600;
         transition: 0.3s;
      }

      .contact .row form .btn:hover {
         background-color: var(--black);
         transform: translateY(-3px);
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Catering order</h3>
   <p><a href="home.php">home</a> <span> / contact</span></p>
</div>

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="Contact Us">
      </div>

      <form action="" method="post">
         <h3>we'd love to hear from you!</h3>
         <input type="text" name="name" maxlength="50" class="box" placeholder="Your full name" required>
         <input type="number" name="number" min="0" max="9999999999" class="box" placeholder="Your phone number" required maxlength="10">
         <input type="email" name="email" maxlength="50" class="box" placeholder="Your email address" required>
         <textarea name="msg" class="box" required placeholder="What's on your mind?" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="Send Message" name="send" class="btn">
      </form>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

<script>
   // Ensuring the loader is removed instantly
   window.onload = () => {
      const loader = document.querySelector('.loader');
      if(loader) loader.style.display = 'none';
   };
</script>

</body>
</html>