<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email or number already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --dark-bg: #f8f9fa;
         --text-main: #2d3436;
         --white: #ffffff;
         --shadow: 0 15px 35px rgba(0,0,0,0.1);
      }

      body {
         background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/hero-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
         min-height: 100vh;
      }

      .form-container {
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 8rem 2rem;
      }

      .form-container form {
         background: rgba(255, 255, 255, 0.95);
         backdrop-filter: blur(10px);
         padding: 4rem;
         width: 50rem;
         border-radius: 2rem;
         box-shadow: var(--shadow);
         text-align: center;
         border: 1px solid rgba(255,255,255,0.3);
      }

      .form-container form h3 {
         font-size: 3rem;
         color: var(--text-main);
         text-transform: uppercase;
         margin-bottom: 2.5rem;
         font-weight: 800;
         letter-spacing: 1px;
      }

      .form-container form h3 span {
         color: var(--primary-color);
      }

      .form-container form .box {
         width: 100%;
         margin: 1.2rem 0;
         padding: 1.5rem 2rem;
         font-size: 1.7rem;
         color: var(--text-main);
         border-radius: 1rem;
         background-color: #f0f2f5;
         border: 2px solid transparent;
         transition: all 0.3s ease;
      }

      .form-container form .box:focus {
         border-color: var(--primary-color);
         background-color: var(--white);
         box-shadow: 0 5px 15px rgba(231, 76, 60, 0.1);
      }

      .form-container form .btn {
         width: 100%;
         margin-top: 2rem;
         background-color: var(--primary-color);
         color: var(--white);
         padding: 1.5rem;
         font-size: 1.8rem;
         font-weight: 600;
         border-radius: 1rem;
         cursor: pointer;
         transition: 0.3s;
         text-transform: uppercase;
         letter-spacing: 1px;
      }

      .form-container form .btn:hover {
         background-color: #c0392b;
         transform: translateY(-3px);
         box-shadow: 0 10px 20px rgba(231, 76, 60, 0.2);
      }

      .form-container form p {
         margin-top: 2.5rem;
         font-size: 1.6rem;
         color: #666;
      }

      .form-container form p a {
         color: var(--primary-color);
         font-weight: 600;
         text-decoration: none;
      }

      .form-container form p a:hover {
         text-decoration: underline;
      }

      /* Responsive adjustment */
      @media (max-width: 768px) {
         .form-container form {
            width: 100%;
            padding: 3rem 2rem;
         }
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Join <span>Cuisine Cloud</span></h3>
      <input type="text" name="name" required placeholder="Full Name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Email Address" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Phone Number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Create Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Create Account" name="submit" class="btn">
      <p>Already a member? <a href="login.php">Login here</a></p>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>