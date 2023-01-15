<?php 
session_start();
include 'checksession.php';
loginstatus();
login($_SESSION['id'], $_SESSION['email']);
?>

<?php
// include database connection file
include('config.php');
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
 
$query = "SELECT roomID,roomname,roomtype,beds FROM room ORDER BY roomID asc";
$result = mysqli_query($db_connection, $query);
?>
<!DOCTYPE html> 
<html lang="en"> 
<head>
  <title>Make A Booking</title>
  <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script>
    $(function() {

        $('input[name="checkin"]').datepicker({
            dateFormat: "yy-mm-dd",
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
            },
            minDate: new Date(),
        });
        });

    $(function() {

        $('input[name="checkout"]').datepicker({
            dateFormat: "yy-mm-dd",
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
            },
            minDate: new Date(),
        });
        });

  $(document).ready(function () {
 
    $('.dateFilter').datepicker({
      dateFormat: "yy-mm-dd",
      minDate: new Date()
    });
    
    // submitting the daterange for the search when the button is clicked
    $('#submit').click(function () {
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if (from_date != '' && to_date != '') {
        if (from_date < to_date) {
        $.ajax({
          url: "roomsearch.php",
          method: "POST",
          data: { from_date: from_date, to_date: to_date },
          success: function (data) {
            $('#rooms').html(data);
          }
        });
      }
      else{
        alert("Please select a check-out date later than check-in");
      }
    }
      else {
        alert("Please Select the Date");
      }
    });
  });
</script>
</head>
 
<body>
<?php
include "cleaninput.php";


    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//validate incoming data - only the first field is done for you in this example - rest is up to you do
//roomID
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['roomID']) and !empty($_POST['roomID']) and is_integer(intval($_POST['roomID']))) {
       $roomID = cleanInput($_POST['roomID']);        
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid roomID '; //append error message
          
    }
 
    if (isset($_SESSION['id']) and !empty($_SESSION['id']) and is_integer(intval($_SESSION['id']))) {
      $id = cleanInput($_SESSION['id']);        
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid user '; //append eror message
         
    }  
   // $id = $_SESSION['id'];
  //$id = is_integer(intval($_SESSION['id']));
   
//checkin
if (isset($_POST['checkin']) and !empty($_POST['checkin']) and is_string($_POST['checkin'])) {
      $checkin = cleanInput($_POST['checkin']);        
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid checkin date '; //append eror message
         
    }         
//checkout
if (isset($_POST['checkout']) and !empty($_POST['checkout']) and is_string($_POST['checkout'])) {       
    $checkout = cleanInput($_POST['checkout']);
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid checkout date '; //append eror message
        
    }             
//contactnumber    
    if (isset($_POST['contactnumber']) and !empty($_POST['contactnumber']) and is_string($_POST['contactnumber'])) {
      $contactnumber = cleanInput($_POST['contactnumber']);        
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid contact number '; //append eror message
         
    } 
//extras    
    if (isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])) {
      $extras = cleanInput($_POST['extras']); 
      $extras = (strlen($extras)>150)?substr($extras,1,150):$extras; //check length and clip if too big
      //we would also do context checking here for contents, etc       
    }             
       
//save the room data if the error flag is still clear
    if ($error == 0) {
        if ($checkin < $checkout) {
            $query = "INSERT INTO booking (roomID, customerID, checkin, checkout, contactnumber, extras) VALUES (?,?,?,?,?,?)";
            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt, 'iissss', $roomID, $id, $checkin, $checkout, $contactnumber, $extras);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>New booking added to the list</h2>";
        } else{
            echo"<h2>Please select a check-out date later than the check-in date</h2>".PHP_EOL;
        }
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
    mysqli_close($db_connection); //close the connection once done 
}
?>
<h1>Make a booking</h1>
<h2><a href='listbookings.php'>[Return to the bookings listing]</a><a href='index.php'>[Return to the main page]</a></h2>
<h2>Booking for a test</h2>
<form method="POST" action="addbooking.php">

  <p>
    <label for="roomID">Room name: </label>
    
  <?php
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    // prepare the query to create a dropdown box
    $rquery = "SELECT roomID, roomname, roomtype, beds FROM room";
    $rresult = mysqli_query($db_connection,$rquery);
    echo "<select name=roomID id=roomID required>";
    foreach ($rresult as $rrow){//Array of records stored in $rrow	
      echo "<option value=$rrow[roomID]>$rrow[roomname], $rrow[roomtype], $rrow[beds]</option>"; 
    }
    mysqli_close($db_connection); //close the connection once done
  ?>
  </select>
  </p>
  <p>
    <label for="checkin">Check-in date: </label>
    <input type="text" id="checkin" name="checkin" required> 
  </p> 
  <p>
    <label for="checkout">Check-out date: </label>
    <input type="text" id="checkout" name="checkout" required> 
  </p>
  <p>
    <label for="contactnumber">Contact number: </label>
    <input type="tel" name="contactnumber" id="contactnumber" pattern="^\+?\(?\d{3,4}\)?\d{6,10}" required> 
  </p>
  <p>
    <label for="extras">Booking extras: </label>
    <textarea type="text" rows="5" cols="35"  id="extras" name="extras" maxlength="150" ></textarea> 
  </p> 
  
   <input type="submit" name="submit" value="Add">
   <a href="listbookings.php" id="cancel" name="cancel" value="cancel">[Cancel]</a>

 </form>

  
    <h2>Search Room Availability</h2>
    </br>
    <form method=>
        <input type="text" name="from_date" id="from_date" class="form-control dateFilter" placeholder="Check-in" />
      
        <input type="text" name="to_date" id="to_date" class="form-control dateFilter" placeholder="Check-out" />
      
        <input id="submit" type="button" value="Search Availability">
</form>
    </br>
    
          <table id="rooms" class="table" border="1">
            <tr>
              <th>Room #</th>
              <th>Room Name</th>
              <th>Room Type</th>
              <th>Beds</th>
            </tr>
            <?php
            while($row = mysqli_fetch_array($result))
            {
            ?>
              <tr>
                  <td><?php echo $row["roomID"]; ?></td>
                  <td><?php echo $row["roomname"]; ?></td>
                  <td><?php echo $row["roomtype"]; ?></td>
                  <td><?php echo $row["beds"]; ?></td>
              </tr>
            <?php
            }
            ?>
          </table>
  
</body>
</html>