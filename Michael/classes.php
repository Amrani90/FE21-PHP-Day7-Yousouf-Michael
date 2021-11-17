<?php
class Vehicles
{
    function __construct(public $name, public $model, public $makeYear, public $color, public $fuel)
    {
    }
    function naMo()
    {
        return "This vehicle is called " . $this->name . " and is of model " . $this->model . ".";
    }
}
$car1 = new Vehicles("Mazda", "Mod 1", "2015", "blue", "Diesel");
class Ships extends Vehicles
{
    function __construct(public $name, public $model, public $makeYear, public $color, public $fuel, public $weight, public $comp)
    {
        parent::__construct($name, $model, $makeYear, $color, $fuel);
    }
    function shipIntro()
    {
        $a = $this->naMo();
        return $a . "It weighs " . $this->weight . " tons.";
    }
}
$ship1 = new Ships("Symfony", "Mod-2", "1990", "white", "Diesel", 50000, "Cunnard");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes</title>
</head>

<body>
    <h3>Class Vehicles</h3>
    <!-- displays This car is called Mazda and is of model Mod 1. -->
    <p><?= $car1->naMo(); ?></p>
    <hr>
    <h3>Class Ships</h3>
    <p><?= $ship1->shipIntro() ?></p>
</body>

</html>