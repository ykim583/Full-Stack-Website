<?php
@include 'config.php';
include 'util.php';

if (isset($_POST['submit'])) {
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];

    $select = "SELECT * FROM user_form WHERE email = '$email' && password = '$pass'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
        } else {
            // Get the current maximum value of the 'no' column
            $query = "SELECT MAX(no) AS max_no FROM user_form";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $max_no = $row['max_no'];

            // Increment the maximum 'no' value by 1 to assign to the new user
            $user_no = $max_no + 1;

            $insert = "INSERT INTO user_form (id, name, email, password, user_type, no) VALUES ('$id', '$name', '$email', '$pass', '$user_type', '$user_no')";
            mysqli_query($conn, $insert);
            header('location: login_form.php');
            exit;
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="id" required placeholder="Type your student Id" pattern="[0-9]+" title="Please enter only numeric values">
      <input type="text" name="name" required placeholder="enter your name">
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="password" name="cpassword" required placeholder="confirm your password">
      <select name="user_type">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select>
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>already have an account? <a href="login_form.php">login now</a></p>
   </form>

</div>

</body>
</html>
