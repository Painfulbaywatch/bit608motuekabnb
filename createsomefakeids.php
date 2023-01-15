<?php 
session_start();
include 'checksession.php';
checkUser();
loginstatus();
// THIS PAGE IS ONLY FOR DEVELOPMENT PURPOSES AND WILL NOT REMAIN IN THE LATTER STAGES 
?>

<DOCTYPE html> 
     <html> 
        <head>
            <title>Create Some Fake I.D.s</title>
            <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
         <body> 
             <?php 
             include "config.php"; 
             $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE); 
             if (mysqli_connect_errno()) { 
                 echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ; 
                 exit; //stop processing further 
             }; 
             $query = "INSERT INTO customer (firstname, lastname, email, password, role) VALUES (?,?,?,?,?)"; 
             $stmt = mysqli_prepare($db_connection, $query); //prepare the query  
 // create hashed password - used for both members, just an example 
             $password="temp1234"; 
             $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
 // admin user 
             $firstname = "The"; 
             $lastname = "Admin"; 
             $email = "admin@memberadmin.co.nz"; 
             $role = 9; 
             mysqli_stmt_bind_param($stmt,'ssssi', $firstname, $lastname, $email, $hashed_password, $role);  
             mysqli_stmt_execute($stmt); 
 // ordinary member 
             $firstname = "Ordinary"; 
             $lastname = "Member"; 
             $email = "amember@amember.co.nz"; 
             $role = 1; 
             mysqli_stmt_bind_param($stmt,'ssssi', $firstname, $lastname, $email, $hashed_password, $role);  
             mysqli_stmt_execute($stmt); 
 // non member 
             $firstname = "Non"; 
             $lastname = "Member"; 
             $email = "nonmember@nonmember.co.nz"; 
             $role = 0; 
             mysqli_stmt_bind_param($stmt,'ssssi', $firstname, $lastname, $email, $hashed_password, $role);  
             mysqli_stmt_execute($stmt); 
             mysqli_stmt_close($stmt);   
             mysqli_close($db_connection); //close the connection once done 
         ?> 
         <p>test data added to the database</p> 
     </body> 
 </html> 
  