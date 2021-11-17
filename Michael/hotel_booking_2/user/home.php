<?php
session_start();
require_once '../components/db_connect.php';

// if adm will redirect to dashboard
if (isset($_SESSION['adm'])) {
    header("Location: dashboard.php");
    exit;
}
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// select logged-in users details - procedural style
$id = $_SESSION['user'];
$res = mysqli_query($connect, "SELECT * FROM user WHERE u_id=" . $_SESSION['user']);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
$sql_hotels = "SELECT * FROM hotels";
$res_hotels = mysqli_query($connect, $sql_hotels);
$hotel_body = '';
$hotel_opt = '';
if ($res_hotels->num_rows > 0) {
    while ($row2 = $res_hotels->fetch_array(MYSQLI_ASSOC)) {
        $hotel_body .= "<div class='col-12 colmd-6 col-lg-3'>
        <div class='card' style='width: 18rem;'>
            <img src='../pictures/" . $row2['picture'] . "' class='card-img-top' alt='Error'>
            <div class='card-body'>
                <h5 class='card-title'>" . $row2['hotel_name'] . "</h5>
                <p class='card-text'>" . $row2['address'] . "</p>
                <h6>Price per night: " . $row2['price'] . " Euros</h6>
            </div>
        </div>
    </div>";
        $hotel_opt .= "<option value='{$row2['hotel_id']}'>{$row2['hotel_name']}</option>";
    }
}
$booking_bd = '';
$sql6 = "SELECT * FROM bookings JOIN hotels ON bookings.fk_hotel_id=hotels.hotel_id WHERE fk_u_id=$id;";
$booking_bd = '';
$res6 = mysqli_query($connect, $sql6);
if ($res6->num_rows > 0) {
    while ($row6 = $res6->fetch_array(MYSQLI_ASSOC)) {
        $booking_bd .= "<div class='col-12 colmd-6 col-lg-3'>
         <div class='card' style='width: 18rem;'>
             <img src='../pictures/" . $row6['picture'] . "' class='card-img-top' alt='Error'>
             <div class='card-body'>
                 <h5 class='card-title'>" . $row6['hotel_name'] . "</h5>
                 <p class='card-text'> Room " . $row6['room'] . "</p>
                 <h6>Price per night: " . $row6['price'] . " Euros</h6>
             </div>
         </div>
     </div>";
    }
}
$message = '';
if ($_POST) {
    $hotel = $_POST['hotels'];
    $customer = $_POST['id'];
    $room = $_POST['room'];
    $sql4 = "SELECT * FROM hotels WHERE hotel_id=$hotel";
    $res4 = mysqli_query($connect, $sql4);
    $row4 = mysqli_fetch_array($res4, MYSQLI_ASSOC);
    $sql5 = "SELECT * FROM bookings WHERE fk_hotel_id=" . $hotel . " AND room=" . $room;
    $res5 = mysqli_query($connect, $sql5);
    if ($room > $row4['roomnumb']) {
        $message = "The maximal number of rooms of this hotel is " . $row4['roomnumb'] . ".";
    } elseif ($res5->num_rows > 0) {
        $message = "This room is already booked.";
    } else {
        $sql3 = "INSERT INTO `bookings` (`book_id`, `fk_hotel_id`, `fk_u_id`, `room`) VALUES (NULL, '$hotel', '$customer', '$room')";
        if (mysqli_query($connect, $sql3) === TRUE) {
            $message = "You have booked the room!";
        } else {
            $message = "Connection error.";
        }
    }
}
mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - <?php echo $row['f_name']; ?></title>
    <?php require_once '../components/boot-css.php' ?>
    <style>
        .userImage {
            width: 200px;
            height: 200px;
        }

        .hero {
            background: rgb(2, 0, 36);
            background: linear-gradient(24deg, rgba(2, 0, 36, 1) 0%, rgba(0, 212, 255, 1) 100%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="hero">
            <img class="userImage" src="../pictures/<?php echo $row['u_picture']; ?>" alt="<?php echo $row['f_name']; ?>">
            <p class="text-white">Hi <?php echo $row['f_name']; ?></p>
            <a href="logout.php?logout">Sign Out</a>
            <a href="update.php?id=<?php echo $_SESSION['user'] ?>">Update your profile</a>
        </div>
        <h2 class="text-center">Hotels</h2>
        <div class="row"><?= $hotel_body ?></div>
        <hr>
        <h4 class="text-center"><?= $message ?></h4>
        <legend class='h2'>Choose a Hotel</legend>
        <form action="home.php" method="post" enctype="multipart/form-data">
            <table class='table'>
                <tr>
                    <th>Hotel</th>
                    <td>
                        <select class="form-select" name="hotels" aria-label="Default select example">
                            <?php echo $hotel_opt; ?>
                            <option selected value='none'>Undefined</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Room</th>
                    <td><input class='form-control' type="number" name="room" placeholder="Number" step="any" /></td>
                </tr>

                <tr>
                    <input type="hidden" name="id" value="<?php echo $id ?>" />
                    <td><button class='btn btn-success' type="submit">Book</button></td>
                </tr>
            </table>
        </form>
        <div class="row">
            <h2>Your bookings</h2>
            <?= $booking_bd ?>
        </div>

    </div>

</body>

</html>