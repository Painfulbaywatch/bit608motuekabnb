<?php 
session_start();
include 'checksession.php';
checkUser();
loginstatus();
?>
<!DOCTYPE html> 
<html lang="en"> 
<head>
<title>Browse rooms</title>
<meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT roomID,roomname,roomtype FROM room ORDER BY roomtype';
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Room list</h1>
<h2><a href='addroom.php'>[Add a room]</a><a href="index.php">[Return to main page]</a></h2>
<table border="1">
<thead><tr><th>Room Name</th><th>Type</th><th>Action</th></tr></thead>
<?php

//makes sure we have rooms
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['roomID'];	
	  echo '<tr><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td>';
	  echo '<td><a href="viewroom.php?id='.$id.'">[view]</a>';
	  echo '<a href="editroom.php?id='.$id.'">[edit]</a>';
	  echo '<a href="deleteroom.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>';
   }
} else echo "<h2>No rooms found!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done
?>
</table>
</body>
<?php
echo '</div></div>';
include "footer.php";
?>
</html>
  