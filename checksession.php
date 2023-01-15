<?php
//overrides for development purposes only - comment this out when testing the login
//$_SESSION['loggedin'] = 1;     
//$_SESSION['userid'] = 1; //this is the ID for the admin user  
//$_SESSION['username'] = 'Mr Test';
//end of overrides

function isAdmin() {
 if (($_SESSION['loggedin'] == 1) and ($_SESSION['userid'] == 1)) 
     return TRUE;
 else 
     return FALSE;
}

//function to check if the user is logged else send to the login page 
function checkUser() {
    
return true;
    $_SESSION['URI'] = '';    
    if ($_SESSION['loggedin'] == 1)
       return TRUE;
    else {
       $_SESSION['URI'] = 'http://localhost'.$_SERVER['REQUEST_URI']; //save current url for redirect     
       header('Location: http://localhost/motueka/login.php', true, 303);       
    }       
}

//just to show we are are logged in
function loginStatus()
{

    if (array_key_exists('loggedin', $_SESSION)) {
        if ($_SESSION['loggedin'] == 1) {
            $username = $_SESSION['email'];
            if ($_SESSION['role'] == 9) {
                echo "<h2>Logged in as Admin $username</h2>";
            } else {
                echo "<h2>Logged in as Customer $username</h2>";
            }
           
        } else {
            echo "<h2>You are logged off</h2>";
        }
    }
}

function adminCheck(){
    if ($_SESSION['role'] != 9) {
        header('Location: http://localhost/motueka/restrictedaccess.php', true, 301);
    }
}


function login($id, $username) {
    if ($_SESSION['loggedin'] != 1) {
      $_SESSION['URI'] = 'http://localhost'.$_SERVER['REQUEST_URI'];
      // redirect to login page
      header('Location: http://localhost/motueka/unauthorizedaccess.php', true, 301);
    } else {
      // redirect to original page
      header('Location: ' . $_SESSION['URI'], true, 301);
    }
  
    // update session variables
    $_SESSION['loggedin'] = 1;        
    $_SESSION['userid'] = $id;   
    $_SESSION['username'] = $username; 
    // clear the URI variable
    $_SESSION['URI'] = '';
  }

//simple logout function
function logout(){
  $_SESSION['loggedin'] = 0;
  $_SESSION['userid'] = -1;        
  $_SESSION['username'] = '';
  $_SESSION['URI'] = '';    
}
?>