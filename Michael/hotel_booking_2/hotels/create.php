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
require_once '../components/db_connect.php';
require_once '../components/file_upload.php';
$error = false;
$hotel_name = $address = $price = $picture = $roomnumb = '';
$hotel_nameError = $addressError = $priceError =  $picError = $roomnumbError = '';
if (isset($_POST['btn-signup'])) {
    $hotel_name = $_POST['hotel_name'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $uploadError = '';
    $picture = file_upload($_FILES['picture'], 'hotels');
    $roomnumb = $_POST['roomnumb'];
    if (empty($hotel_name)) {
        $error = true;
        $hotel_nameError = "Please enter hotel name.";
    } elseif (empty($address)) {
        $error = true;
        $addressError = "Please enter hotel address.";
    } elseif (empty($price)) {
        $error = true;
        $priceError = "Please enter price per night.";
    } elseif (empty($roomnumb)) {
        $error = true;
        $roomnumbError = "Please enter number of rooms.";
    } else {
        // checks whether the email exists or not
        $query = "SELECT address FROM hotels WHERE address='$address'";
        $result = mysqli_query($connect, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $addressError = "One hotel already has this address, you can update it.";
        }
    }
    if (!$error) {

        $query = "INSERT INTO hotels(hotel_name, address, price, picture,roomnumb)
                 VALUES('$hotel_name', '$address', '$price', '$picture->fileName','$roomnumb')";
        $res = mysqli_query($connect, $query);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Hotel added";
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hotel</title>
    <?php require_once '../components/boot-css.php' ?>
</head>

<body>
    <div class="container">
        <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" enctype="multipart/form-data">
            <h2>Add a hotel</h2>
            <hr />
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

            <input type="text" name="hotel_name" class="form-control" placeholder="Hotel name" maxlength="50" value="<?php echo $hotel_name ?>" />
            <span class="text-danger"> <?php echo $hotel_nameError; ?> </span>

            <input type="text" name="address" class="form-control" placeholder="Address" maxlength="50" value="<?php echo $address ?>" />
            <span class="text-danger"> <?php echo $addressError; ?> </span>

            <div class="d-flex">
                <input type="number" name="price" class="form-control" placeholder="Price per night" maxlength="40" value="<?php echo $price ?>" />

                <span class="text-danger"> <?php echo $priceError; ?> </span>

                <input class='form-control w-50' type="file" name="picture">
                <span class="text-danger"> <?php echo $picError; ?> </span>
            </div>
            <input type="number" name="roomnumb" class="form-control" placeholder="Number of rooms" maxlength="15" value="<?php echo $roomnumb ?>" />
            <span class="text-danger"> <?php echo $roomnumbError; ?> </span>
            <hr />
            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Add</button>
            <hr />
            <a href="../user/dashboard.php?hotels" class="btn btn-warning">Back to hotels</a>
        </form>
    </div>
</body>

</html>