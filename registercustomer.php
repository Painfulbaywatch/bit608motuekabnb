<?php 
session_start();
include 'checksession.php';
loginstatus();
?>
<!DOCTYPE html> 
<html lang="en">
  <head>
    <title>Register new customer</title>
    <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
 <body>

<?php
include "config.php"; //load in any variables
include "cleaninput.php";

//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//validate incoming data 
//firstname
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['firstname']) and !empty($_POST['firstname']) and is_string($_POST['firstname'])) {
       $fn = cleanInput($_POST['firstname']); 
       $firstname = (strlen($fn)>50)?substr($fn,1,50):$fn; //check length and clip if too big       
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid firstname '; //append error message
       $firstname = '';  
    } 
//lastname
if (isset($_POST['lastname']) and !empty($_POST['lastname']) and is_string($_POST['lastname'])) {
  $ln = cleanInput($_POST['lastname']); 
  $lastname = (strlen($ln)>50)?substr($ln,1,50):$ln; //check length and clip if too big       
} else {
  $error++; //bump the error flag
  $msg .= 'Invalid lastname '; //append error message
  $lastname = '';  
} 
               
//email
if (isset($_POST['email']) and !empty($_POST['email']) and is_string($_POST['email'])) {
  $email = cleanInput($_POST['email']);
} else {
  $error++; //bump the error flag
  $msg .= 'Invalid email '; //append error message
  $email = '';  
}         
    
//password 
if (isset($_POST['password']) and !empty($_POST['password']) and is_string($_POST['password'])) {
  $password = cleanInput($_POST['password']); 
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);       
} else {
  $error++; //bump the error flag
  $msg .= 'Invalid password '; //append error message
  $password = '';  
} 
               
        
//save the customer data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO customer (firstname,lastname,email,password,role) VALUES (?,?,?,?,'1')";
        $stmt = mysqli_prepare($db_connection,$query); //prepare the query		
        mysqli_stmt_bind_param($stmt,'ssss', $firstname, $lastname, $email, $hashed_password); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>customer saved</h2>";        		
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
    mysqli_close($db_connection); //close the connection once done
}
?>
<h1>New Customer Registration</h1>
<h2><a href='listcustomers.php'>[Return to the Customer listing]</a><a href='index.php'>[Return to the main page]</a></h2>
<!-- customer registration form -->
<form method="POST" action="registercustomer.php">
  <p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="1" maxlength="50" required> 
  </p> 
  <p>
    <label for="lastname">Last Name: </label>
    <input type="text" id="lastname" name="lastname" minlength="1" maxlength="50" required> 
  </p>  
  <p>  
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100" size="50" required> 
   </p>
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" minlength="8" maxlength="32" required> 
  </p> 
  
   <input type="submit" name="submit" value="Register">
   <a href="listcustomers.php" id="cancel" name="cancel" value="cancel">[Cancel]</a>
 </form>
</body>
</html>
  