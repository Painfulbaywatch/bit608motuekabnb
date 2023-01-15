<?php 
session_start();
?>
<?php
// include database connection file
include('config.php');
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();

if(isset($_POST["from_date"], $_POST["to_date"])) {
    $searchresult = "";
    $query = sprintf('SELECT * 
    FROM room 
    WHERE roomID NOT IN(SELECT roomID 
    FROM booking WHERE  checkin >= "%s" AND checkout <= "%s") ORDER BY roomID asc', $_POST["from_date"], $_POST["to_date"]);
    $result = mysqli_query($db_connection, $query);
 
    $searchresult .='
    <table class="table table-bordered">
    <tr>
    <th>Room #</th>
    <th>Room Name</th>
    <th>Room Type</th>
    <th>Beds</th>
    </tr>';
 
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $searchresult .='
            <tr>
            <td>'.$row["roomID"].'</td>
            <td>'.$row["roomname"].'</td>
            <td>'.$row["roomtype"].'</td>
            <td>'.$row["beds"].'</td>
            </tr>';
        }
    }
    else
    {
        $searchresult .= '
        <tr>
        <td colspan="4">No Rooms Found</td>
        </tr>';
    }
    $searchresult .= '</table>';
    echo $searchresult;
}
?>
