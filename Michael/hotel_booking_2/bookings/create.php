<?php
session_start();
require_once '../components/db_connect.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../user/login.php");
    exit;
}
//if session user exist it shouldn't access dashboard.php
if (isset($_SESSION["user"])) {
    header("Location: ../user/home.php");
    exit;
}
require_once '../components/db_connect.php';
require_once '../components/file_upload.php';
$error = false;
$hotel_id = $u_id = $room = $hotelbody = $userbody = ''; //in sql fk_
$hotelError = $userError = $roomError = '';
$sql1 = "SELECT u_id, f_name, s_name FROM user;";
$result1 = mysqli_query($connect, $sql1);
while ($row1 = $result1->fetch_array(MYSQLI_ASSOC)) {
    $userbody .=
        "<option value='{$row1['u_id']}'>{$row1['f_name']} {$row1['s_name']}</option>";
}
$sql2 = "SELECT hotel_id, hotel_name FROM hotels;";
$result2 = mysqli_query($connect, $sql2);
while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) {
    $hotelbody .=
        "<option value='{$row2['hotel_id']}'>{$row2['hotel_name']}</option>";
}
//old below:
if (isset($_POST['submit'])) {
    $u_id = $_POST['u_id'];
    $hotel_id = $_POST['hotel_id'];
    $room = $_POST['room'];
    $uploadError = '';

    if (empty($u_id)) {
        $error = true;
        $userError = "Please choose a user.";
    } elseif (empty($hotel_id)) {
        $error = true;
        $hotelError = "Please choose a hotel.";
    } elseif (empty($room)) {
        $error = true;
        $roomError = "Please choose a room.";
    } else {
        // checks whether the email exists or not
        $query = "SELECT fk_hotel_id, room FROM bookings WHERE fk_hotel_id='$hotel_id' AND room='$room'";
        $result = mysqli_query($connect, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $addressError = "This room of that hotel is already booked, you can update or delete the record.";
        }
    }
    if (!$error) {

        $query = "INSERT INTO bookings(fk_hotel_id, fk_u_id, room)
                 VALUES('$hotel_id', '$u_id', '$room')";
        $res = mysqli_query($connect, $query);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Booking added";
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once '../components/boot-css.php' ?>
    <title>PHP CRUD | Add Product</title>
    <style>
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 60%;
        }
    </style>
</head>

<body>
    <?php
    if (isset($errMSG)) {
    ?>
        <div class="alert alert-<?php echo $errTyp ?>">
            <p><?php echo $errMSG; ?></p>
            <p><?php echo $uploadError; ?></p>
        </div>

    <?php
    }
    ?>
    <fieldset>
        <legend class='h2'>Add Booking</legend>
        <form action="create.php" method="post" enctype="multipart/form-data">
            <table class='table'>
                <tr>
                    <th>User</th>
                    <td>
                        <select class="form-select" name="u_id" aria-label="Default select example">
                            <?php echo $userbody; ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Hotel</th>
                    <td>
                        <select class="form-select" name="hotel_id" aria-label="Default select example">
                            <?php echo $hotelbody; ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Room</th>
                    <td><input class='form-control' type="number" name="room" placeholder="Room number" step="any" /></td>
                </tr>
                <tr>
                    <td><button class='btn btn-success' type="submit" name="submit">Insert booking</button></td>
                    <td><a href="../user/dashboard.php?bookings"><button class='btn btn-warning' type="button">Back</button></a></td>
                </tr>
            </table>
        </form>
    </fieldset>
</body>

</html>