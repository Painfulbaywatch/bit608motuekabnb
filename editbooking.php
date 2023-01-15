<?php 
session_start();
include 'checksession.php';
checkUser();
adminCheck();
loginstatus();
?>
<!DOCTYPE html> 
<html lang="en">
  <head>
    <title>Edit a Booking</title>
    <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

 <script>
    $(function() {
		// checkin datepicker settings
        $('input[name="checkin"]').datepicker({
            dateFormat: "yy-mm-dd",
            autoUpdateInput: false,
            minDate: new Date(),
        });
        });
		// checkout datepicker settings
    $(function() {

        $('input[name="checkout"]').datepicker({
            dateFormat: "yy-mm-dd",
            autoUpdateInput: false,
            minDate: new Date(),
        });
        });
 </script>
</head>
 <body>

<?php
include "config.php"; //load in any variables
include "cleaninput.php";

$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
$error=0;
if (mysqli_connect_errno()) {
  echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
  exit; //stop processing the page further
};

//retrieve the bookingID from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
        exit;
    } 
}
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
//validate incoming data 
    
//bookingID (sent via a form it is a string not a number so we try a type conversion!)    
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Booking ID '; //append error message
       $id = 0;  
    }   
//roomID
	if (isset($_POST['roomID']) and !empty($_POST['roomID']) and is_integer(intval($_POST['roomID']))) {
		$roomID = cleanInput($_POST['roomID']); 
	} else {
		$error++; //bump the error flag
		$msg .= 'Invalid Room ID '; //append error message
		$id = 0;  
	}   
//checkin
	if (isset($_POST['checkin']) and !empty($_POST['checkin'])) {
		$checkin = cleanInput($_POST['checkin']); 
	} else {
		$error++; //bump the error flag
		$msg .= 'Invalid Date '; //append error message
		$id = 0;  
	}          
//checkout
	if (isset($_POST['checkout']) and !empty($_POST['checkout'])) {
		$checkout = cleanInput($_POST['checkout']); 
	} else {
		$error++; //bump the error flag
		$msg .= 'Invalid Date '; //append error message
		$id = 0;  
	} 
//contactnumber
	if (isset($_POST['contactnumber']) and !empty($_POST['contactnumber'])) {
		$contactnumber = cleanInput($_POST['contactnumber']); 
	} else {
		$error++; //bump the error flag
		$msg .= 'Invalid Date '; //append error message
		$id = 0;  
	}                 
//extras
	if (isset($_POST['extras'])) {
		$extras = cleanInput($_POST['extras']); 
	} else {
		$error++; //bump the error flag
		$msg .= 'Invalid entry '; //append error message
		$id = 0;  
	}           
//review
	if (isset($_POST['review'])) {
		$review = cleanInput($_POST['review']); 
	} else {
		$error++; //bump the error flag
		$msg .= 'Invalid entry '; //append error message
		$id = 0;  
	}   

//save the booking data if the error flag is still clear and booking id and room ID are > 0
    if ($error == 0 and $id > 0)
	  {
        $query = 'UPDATE booking SET roomID=?,checkin=?,checkout=?,contactnumber=?,extras=?,review=? WHERE bookingID='.$id;
        $stmt = mysqli_prepare($db_connection, $query); //prepare the query
        mysqli_stmt_bind_param($stmt, 'isssss', $roomID, $checkin, $checkout, $contactnumber, $extras, $review); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Booking details updated.</h2>";  
    } 
	  else
	  { 
      echo "<h2>$msg</h2>";
    }      
}
//locate the booking to edit by using the bookingID
//we also include the booking ID in our form for sending it back for saving the data
$query = 'SELECT * FROM booking INNER JOIN room ON booking.roomID = room.roomID INNER JOIN customer ON booking.customerID 
= customer.customerID WHERE bookingid='.$id;
$result = mysqli_query($db_connection,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);

?>
<h1>Edit a booking</h1>
<h2><a href='listbookings.php'>[Return to the Bookings listing]</a><a href='menu.php'>[Return to the main page]</a></h2>
<!-- form for updating booking data -->
<form method="POST" action="editbooking.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
   <p>
    <label for="roomID">Room (name, type, beds): </label>
	<?php
	//creating a dropdown box that preselects the room from the booking table
	$rquery="SELECT roomID,roomname,roomtype,beds FROM room order by roomID"; 
	$rresult = mysqli_query($db_connection,$rquery);
	echo "<select name=roomID value=$row[roomname], $row[roomtype], $row[beds] >";
	foreach ($rresult as $rrow){//Array of records stored in $rrow	
	if($rrow['roomID'] != $row['roomID']){
		echo "<option value=$rrow[roomID]>$rrow[roomname], $rrow[roomtype], $rrow[beds]</option>"; 
		}else{
		echo "<option value=$rrow[roomID] selected>$rrow[roomname], $rrow[roomtype], $rrow[beds]</option>"; //auto-selecting the current room from the db
		}
	}
 echo "</select>";// Closing of list box
 ?>
  </p> 
  <p>
    <label for="checkin">Check-in: </label>
    <input type="text" name="checkin" value="<?php echo $row['checkin']; ?>" required> 
  </p>  
  <p>
    <label for="checkout">Check-out: </label>
    <input type="text" name="checkout" value="<?php echo $row['checkout']; ?>" required> 
  </p>
  <p>
    <label for="contactnumber">Contact number: </label>
    <input type="tel" name="contactnumber" pattern="^\+?\(?\d{3,4}\)?\d{6,10}" value="<?php echo $row['contactnumber']; ?>" required> 
  </p>
  <p>
    <label for="extras">Booking extras: </label>
    <textarea rows="5" cols="35"  name="extras" maxlength="150" value="extras"><?php echo $row['extras']; ?></textarea> 
  </p> 
  <p>
    <label for="review">Room review: </label>
    <textarea rows="5" cols="35"  name="review" maxlength="150" value="review"><?php echo $row['review']; ?></textarea> 
  </p>
   <input type="submit" name="submit" value="Update">
 </form>
<?php 
} 
else
{ 
  echo "<h2>room not found with that ID</h2>"; //simple error feedback
}
mysqli_free_result($result);
mysqli_close($db_connection); //close the connection once done
?>

</body>
<?php
echo '</div></div>';
include "footer.php";
?>
</html>