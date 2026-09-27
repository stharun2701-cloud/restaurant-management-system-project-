<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --dark-bg: #1e272e;
         --glass-bg: rgba(255, 255, 255, 0.9);
      }

      body {
         margin: 0;
         padding: 0;
         display: flex;
         align-items: center;
         justify-content: center;
         min-height: 100vh;
         /* High-end gradient background */
         background: linear-gradient(135deg, #1e272e 0%, #2f3640 100%);
         font-family: 'Poppins', sans-serif;
         position: relative;
         overflow: hidden;
      }

      /* Decorative background elements */
      body::before {
         content: '';
         position: absolute;
         top: -10%; left: -10%;
         width: 400px; height: 400px;
         background: var(--primary-color);
         filter: blur(150px);
         border-radius: 50%;
         opacity: 0.3;
         z-index: -1;
      }

      .form-container {
         width: 100%;
         display: flex;
         justify-content: center;
         padding: 2rem;
      }

      .form-container form {
         background: var(--glass-bg);
         backdrop-filter: blur(15px);
         padding: 4rem 3rem;
         width: 45rem;
         border-radius: 3rem;
         box-shadow: 0 25px 50px rgba(0,0,0,0.3);
         border: 1px solid rgba(255,255,255,0.2);
         text-align: center;
      }

      .form-container form h3 {
         font-size: 3.5rem;
         color: var(--dark-bg);
         text-transform: uppercase;
         margin-bottom: 1rem;
         font-weight: 800;
      }

      .form-container form p {
         font-size: 1.4rem;
         color: #666;
         margin-bottom: 2.5rem;
         padding: 1rem;
         background: rgba(0,0,0,0.05);
         border-radius: 1rem;
      }

      .form-container form p span {
         color: var(--primary-color);
         font-weight: 700;
      }

      .form-container form .box {
         width: 100%;
         margin: 1.5rem 0;
         padding: 1.5rem 2rem;
         font-size: 1.8rem;
         color: var(--dark-bg);
         border-radius: 1.5rem;
         background: #f1f2f6;
         border: 2px solid transparent;
         transition: 0.3s;
      }

      .form-container form .box:focus {
         border-color: var(--primary-color);
         background: #fff;
         box-shadow: 0 5px 15px rgba(231, 76, 60, 0.1);
      }

      .form-container form .btn {
         width: 100%;
         background: var(--dark-bg);
         color: #fff;
         font-size: 2rem;
         padding: 1.5rem;
         border-radius: 1.5rem;
         cursor: pointer;
         text-transform: capitalize;
         transition: 0.3s;
         font-weight: 600;
         margin-top: 1rem;
      }

      .form-container form .btn:hover {
         background: var(--primary-color);
         transform: translateY(-3px);
         box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
      }

      .form-container form .option-btn {
         display: block;
         margin-top: 2rem;
         font-size: 1.6rem;
         color: #777;
         text-decoration: none;
         transition: 0.3s;
      }

      .form-container form .option-btn:hover {
         color: var(--primary-color);
         text-decoration: underline;
      }

      /* Error Message Styling */
      .message {
         position: fixed;
         top: 2rem;
         left: 50%;
         transform: translateX(-50%);
         background: #fff;
         padding: 1.5rem 2rem;
         border-radius: 1rem;
         box-shadow: 0 10px 20px rgba(0,0,0,0.1);
         display: flex;
         align-items: center;
         gap: 1.5rem;
         z-index: 10001;
         border-left: 5px solid var(--primary-color);
      }

      .message span { font-size: 1.8rem; color: #333; }
      .message i { font-size: 2rem; color: var(--primary-color); cursor: pointer; }

   </style>

</head>
<body>

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

<section class="form-container">

   <form action="" method="POST">
      <h3>Admin<span>Login</span></h3>
      <p>Enter credentials to access dashboard</p>
      
      <input type="text" name="name" maxlength="20" required placeholder="Username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="password" name="pass" maxlength="20" required placeholder="Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="login now" name="submit" class="btn">
      
      <a href="../home.php" class="option-btn">Back to main website</a>
   </form>

</section>

</body>
</html>