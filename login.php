 <?php
 session_start();
 include "checksession.php";

 ?>

<!DOCTYPE html> 
 <html lang="en"> 
    <head>
        <title>Login</title>
        <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
        <body>

 <?php 
     include('config.php'); 
     $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE); 
     if (mysqli_connect_errno()) { 
         echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ; 
         exit; //stop processing the page further 
     }; 
 // if the login form has been filled in 
     if (isset($_POST['email'])) 
     { 
         $email = $_POST['email']; 
         $password = $_POST['password'];
     $firstname = ""; 
 //prepare a query and send it to the server 
         $stmt = mysqli_stmt_init($db_connection); 
         mysqli_stmt_prepare($stmt, "SELECT customerID, firstname, password, role FROM customer WHERE email=?"); 
         mysqli_stmt_bind_param($stmt, "s", $email); 
         mysqli_stmt_execute($stmt); 
         mysqli_stmt_bind_result($stmt, $customerID, $firstname,  $hashpassword, $role); 
         mysqli_stmt_fetch($stmt); 
         if(!$customerID) 
         { 
             echo '<p class="error">Unable to find member with email!'.$email.'</p>'; 
         } 
         else 
         {
         if (password_verify($password, $hashpassword)) {
             $_SESSION['loggedin'] = true;
             $_SESSION['email'] = $email;
             $_SESSION['role'] = $role;
             $_SESSION['id'] = $customerID;
             echo '<p>Congratulations ' . $_SESSION['email'] . ', you are logged in!</p>';
             if ($_SESSION['role'] == 9) {
                 echo '<p>You have Admin access</p>';
             } else {
                 echo '<p>You have Customer access</p>';
             }
             login($_SESSION['id'], $_SESSION['email']);
         } 
             else 
             { 
                 echo '<p>Username/password combination is wrong!</p>'; 
             } 
         } 
         echo '<p><a href="index.php">Return to the menu</a></p>'; 
     } 
 ?> 
 <!-- the action is to this page so the form will also submit to this page --> 
 <form method="POST" action="login.php"> 
     <h1>Customer Login</h1> 
     <label for="email">Email address: </label> 
     <input type="email" id="email" size="30" name="email" required>  
     <p> 
     <label for="password">Password: </label> 
     <input type="password" id="password" size="15" name="password" min="10" max="30" required> 
     </p>  
     <input type="submit" name="submit" value="Login"> <a href = "index.php">[Cancel]</a>
 </form> 
 </body> 
 </html>    