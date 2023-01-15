<?php 
session_start();
include 'checksession.php';
loginstatus();
login($_SESSION['id'], $_SESSION['email']);
?>
<!DOCTYPE html> 
<html lang="en">
    <head>
        <title>Current bookings</title> 
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
$query = 'SELECT booking.bookingID,booking.roomID,booking.customerID,booking.checkin,booking.checkout,booking.contactnumber,booking.extras,booking.review,room.roomID,room.roomname,
roomtype,customer.customerID,customer.firstname,customer.lastname,customer.email FROM booking INNER JOIN room ON booking.roomID = room.roomID INNER JOIN customer ON booking.customerID 
= customer.customerID ORDER BY booking.checkin';
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Booking list</h1>
<h2><a href='addbooking.php'>[Make a booking]</a><a href="index.php">[Return to main page]</a></h2>
<table border="1">
<thead><tr><th>Booking (room, dates)</th><th>Customer</th><th>Action</th></tr></thead>
<?php

//display current bookings
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookingID'];	
	  echo '<tr><td>'.$row['roomname'].", ".$row['checkin'].", ".$row['checkout'].'</td><td>'.$row['lastname'].", ".$row['firstname'].'</td>';
	  echo '<td><a href="viewbooking.php?id='.$id.'">[view]</a>';
	  echo '<a href="editbooking.php?id='.$id.'">[edit]</a>';
	  echo '<a href="deletebooking.php?id='.$id.'">[delete]</a></td>';
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
  