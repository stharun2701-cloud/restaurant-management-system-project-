<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);
      if($select_name->rowCount() > 0){
         $message[] = 'username already taken!';
      }else{
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
         $message[] = 'username updated successfully!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
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
   <title>Update Profile | Admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      :root {
         --primary-color: #e74c3c;
         --dark-bg: #1e272e;
         --glass-bg: rgba(255, 255, 255, 0.95);
         --text-main: #2d3436;
      }

      body {
         background: linear-gradient(rgba(30, 39, 46, 0.85), rgba(30, 39, 46, 0.85)), url('../images/admin-bg.jpg') no-repeat;
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
         min-height: 100vh;
      }

      .form-container {
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 5rem 2rem;
         min-height: 80vh;
      }

      .form-container form {
         background: var(--glass-bg);
         backdrop-filter: blur(15px);
         padding: 4rem;
         width: 45rem;
         border-radius: 2.5rem;
         box-shadow: 0 20px 40px rgba(0,0,0,0.4);
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

      .form-container form .box {
         width: 100%;
         margin: 1.2rem 0;
         padding: 1.5rem 2rem;
         font-size: 1.7rem;
         color: var(--text-main);
         border-radius: 1.2rem;
         background-color: #f1f3f6;
         border: 2px solid transparent;
         transition: all 0.3s ease;
      }

      .form-container form .box:focus {
         border-color: var(--primary-color);
         background-color: #fff;
         box-shadow: 0 5px 15px rgba(231, 76, 60, 0.15);
         outline: none;
      }

      .form-container form .btn {
         width: 100%;
         margin-top: 2rem;
         background-color: var(--primary-color);
         color: #fff;
         padding: 1.5rem;
         font-size: 1.8rem;
         font-weight: 700;
         border-radius: 1.2rem;
         cursor: pointer;
         transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
         text-transform: uppercase;
      }

      .form-container form .btn:hover {
         background-color: #c0392b;
         transform: translateY(-5px);
         box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
      }

      @media (max-width: 480px) {
         .form-container form {
            width: 100%;
            padding: 3rem 2rem;
         }
      }
   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="form-container">

   <form action="" method="POST">
      <h3>Update Profile</h3>
      <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
      <input type="password" name="old_pass" maxlength="20" placeholder="Old Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="New Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm New Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>