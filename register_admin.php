<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'username already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'new admin registered!';
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
   <title>Register Admin | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --dark-bg: #1e272e;
         --glass-bg: rgba(255, 255, 255, 0.95);
      }

      body {
         background: linear-gradient(rgba(30, 39, 46, 0.85), rgba(30, 39, 46, 0.85)), url('../images/admin-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
         min-height: 100vh;
         display: flex;
         flex-direction: column;
      }

      .form-container {
         display: flex;
         align-items: center;
         justify-content: center;
         flex-grow: 1;
         padding: 2rem;
      }

      .form-container form {
         background: var(--glass-bg);
         backdrop-filter: blur(15px);
         padding: 4rem 3rem;
         width: 45rem;
         border-radius: 3rem;
         box-shadow: 0 20px 40px rgba(0,0,0,0.3);
         border: 1px solid rgba(255,255,255,0.3);
         text-align: center;
      }

      .form-container h3 {
         font-size: 3rem;
         color: var(--dark-bg);
         text-transform: uppercase;
         margin-bottom: 2.5rem;
         font-weight: 800;
      }

      .form-container h3 span {
         color: var(--primary-color);
      }

      .form-container form .box {
         width: 100%;
         background: #f1f2f6;
         border-radius: 1.5rem;
         padding: 1.5rem 2rem;
         font-size: 1.8rem;
         color: var(--dark-bg);
         margin: 1rem 0;
         border: 2px solid transparent;
         transition: 0.3s;
      }

      .form-container form .box:focus {
         border-color: var(--primary-color);
         background: #fff;
         box-shadow: 0 5px 15px rgba(231, 76, 60, 0.1);
      }

      .btn {
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
         margin-top: 1.5rem;
      }

      .btn:hover {
         background: var(--primary-color);
         transform: translateY(-3px);
         box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
      }

      /* Notification message fix */
      .message {
         position: sticky;
         top: 2rem;
         max-width: 1200px;
         margin: 0 auto;
         background: #fff;
         padding: 2rem;
         display: flex;
         align-items: center;
         gap: 1.5rem;
         justify-content: space-between;
         border-radius: 1rem;
         border-left: 5px solid var(--primary-color);
         z-index: 10000;
      }

      .message span { font-size: 2rem; color: #333; }
      .message i { font-size: 2.5rem; color: var(--primary-color); cursor: pointer; }

   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="form-container">

   <form action="" method="POST">
      <h3>Register <span>Admin</span></h3>
      <input type="text" name="name" maxlength="20" required placeholder="Enter username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="Enter password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" maxlength="20" required placeholder="Confirm password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>