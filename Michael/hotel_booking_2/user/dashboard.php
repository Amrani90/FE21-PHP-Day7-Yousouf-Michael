<?php
session_start();
require_once '../components/db_connect.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
//if session user exist it shouldn't access dashboard.php
if (isset($_SESSION["user"])) {
    header("Location: home.php");
    exit;
}
$disp_user = 'none';
$disp_book = 'none';
$disp_hotel = 'none';
if (isset($_GET['user'])) {
    $status = 'adm';
    $disp_user = 'initial';
    $sql = "SELECT * FROM user WHERE status != '$status'";
    $result = mysqli_query($connect, $sql);

    //this variable will hold the body for the table
    $tbody = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $tbody .= "<tr>
           <td><img class='img-thumbnail rounded-circle' src='../pictures/" . $row['u_picture'] . "' alt=" . $row['f_name'] . "></td>
           <td>" . $row['f_name'] . " " . $row['s_name'] . "</td>
           <td>" . $row['date_of_birth'] . "</td>
           <td>" . $row['email'] . "</td>
           <td><a href='update.php?id=" . $row['u_id'] . "'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
           <a href='delete.php?id=" . $row['u_id'] . "'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
        </tr>";
        }
    } else {
        $tbody = "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
    }
}
if (isset($_GET['bookings'])) {
    $disp_book = 'initial';
    $sql2 = "SELECT book_id,hotel_name, f_name, s_name, room from user join bookings ON user.u_id=bookings.fk_u_id JOIN hotels on bookings.fk_hotel_id=hotels.hotel_id;";
    $result2 = mysqli_query($connect, $sql2);
    $booking = '';
    if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) {
            $booking .= "<tr>
           <td>" . $row2['hotel_name'] . "</td>
           <td>" . $row2['f_name'] . " " . $row2['s_name'] . "</td>
           
           <td>" . $row2['room'] . "</td>
           <td><a href='../bookings/update.php?id=" . $row2['book_id'] . "'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
           <a href='../bookings/delete.php?id=" . $row2['book_id'] . "'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
        </tr>"; //for delete or edit insert $row2['hotel_id'] after ?id= in the links!
        }
    } else {
        $booking = "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
    }
}
if (isset($_GET['hotels'])) {
    $disp_hotel = 'initial';
    $sql3 = "SELECT *from hotels;";
    $result3 = mysqli_query($connect, $sql3);
    $hotelbody = '';
    if ($result3->num_rows > 0) {
        while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {
            $hotelbody .= "<tr>
           <td><img class='img-thumbnail rounded-circle' src='../pictures/" . $row3['picture'] . "' alt=" . $row3['hotel_name'] . "></td>
           <td>" . $row3['hotel_name'] . "</td>
           <td>" . $row3['address'] . "</td>
           <td>" . $row3['price'] . "</td>
           <td>" . $row3['roomnumb'] . "</td>
           <td><a href='../hotels/update.php?id=" . $row3['hotel_id'] . "'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
           <a href='../hotels/delete.php?id=" . $row3['hotel_id'] . "'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
        </tr>";
        }
    } else {
        $hotelbody = "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management</title>
    <?php require_once '../components/boot-css.php' ?>
    <style type="text/css">
        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }

        td {
            text-align: left;
            vertical-align: middle;
        }

        tr {
            text-align: center;
        }

        .userImage {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>
    <a href="dashboard.php?user">Manage Users</a>
    <hr>
    <a href="dashboard.php?bookings">Manage bookings</a>
    <hr>
    <a href="dashboard.php?hotels">Manage hotels</a>
    <hr>
    <div class="container" style='display: <?= $disp_user ?> ;'>
        <div class="row">
            <div class="col-2">
                <img class="userImage" src="../pictures/admavatar.png" alt="Adm avatar">
                <p class="">Administrator</p>
                <!-- Link to the products -->
                <a href="logout.php?logout">Sign Out</a>
            </div>
            <div class="col-8 mt-2">
                <p class='h2'>Users</p>
                <table class='table table-striped'>
                    <thead class='table-success'>
                        <tr>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Date of birth</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $tbody ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container" style='display: <?= $disp_book ?> ;'>
        <h2>Bookings</h2>
        <table class='table table-striped'>
            <thead class='table-success'>
                <tr>
                    <th>Hotel</th>
                    <th>Customer</th>
                    <th>Room</th>
                </tr>
            </thead>
            <tbody>
                <?= $booking ?>
            </tbody>
        </table>
        <a href="../bookings/create.php" class="btn btn-info">Add Booking</a>
    </div>
    <div class="container" style='display: <?= $disp_hotel ?> ;'>
        <h2>Hotels</h2>
        <table class='table table-striped'>
            <thead class='table-success'>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Price per Night</th>
                    <th>Number of rooms</th>
                </tr>
            </thead>
            <tbody>
                <?= $hotelbody ?>
            </tbody>
        </table>
        <a href="../hotels/create.php" class="btn btn-info">Add Hotel</a>
    </div>
</body>

</html>