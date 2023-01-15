<?php 
session_start();
include 'checksession.php';
?>

<!DOCTYPE html> 
 <html lang="en"> 
   <head> 
     <title>Log Out</title> 
     <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1"> 
   </head> 
   <body> 
     <h1>Customer Menu</h1> 
     <?php  
        logout();
     ?> 
     <p><a href="index.php">You are now logged out. Return to the menu</a></p> 
   </body> 
 </html> 