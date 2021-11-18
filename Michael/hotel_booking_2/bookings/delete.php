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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM `bookings` WHERE book_id=$id";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $hotel_id = $data['fk_hotel_id'];
        $u_id = $data['fk_u_id'];
        $room = $data['room'];
        $quer1 = "SELECT * FROM `hotels` WHERE hotel_id=$hotel_id";
        $res1 = mysqli_query($connect, $quer1);
        $data2 = mysqli_fetch_assoc($res1);
        $hotel_name = $data2['hotel_name'];
        $quer2 = "SELECT * FROM `user` WHERE u_id=$u_id";
        $res2 = mysqli_query($connect, $quer2);
        $data3 = mysqli_fetch_assoc($res2);
        $f_name = $data3['f_name'];
        $s_name = $data3['s_name'];
    }
}
if ($_POST) {
    $id = $_POST['id'];
    $sql = "DELETE FROM bookings WHERE book_id = {$id}";
    if ($connect->query($sql) === TRUE) {
        $class = "alert alert-success";
        $message = "Successfully Deleted!";
        header("refresh:1;url=../user/dashboard.php?bookings");
    } else {
        $class = "alert alert-danger";
        $message = "The entry was not deleted due to: <br>" . $connect->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking</title>
    <?php require_once '../components/boot-css.php' ?>
    <style type="text/css">
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 70%;
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
    </style>
</head>

<body>
    <div class="<?php echo $class; ?>" role="alert">
        <p><?php echo ($message) ?? ''; ?></p>
    </div>
    <fieldset>
        <legend class='h2 mb-3'>Delete request</legend>
        <h5>You have selected the data below:</h5>
        <table class="table w-75 mt-3">
            <tr>
                <td><?php echo "User: $f_name $s_name"; ?></td>
                <td><?php echo "Hotel: $hotel_name";  ?></td>
                <td><?php echo "Room: $room "; ?></td>
            </tr>
        </table>

        <h3 class="mb-4">Do you really want to delete this entry?</h3>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <button class="btn btn-danger" type="submit">Yes, delete it!</button>
            <a href="../user/dashboard.php?bookings"><button class="btn btn-warning" type="button">No, go back!</button></a>
        </form>
    </fieldset>
</body>

</html>