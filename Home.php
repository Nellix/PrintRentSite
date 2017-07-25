<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 02/06/16
 * Time: 18:23
 */

include ("Functions.php");
session();
$conn = dbConnect();
if(userLoggedIn()!=false)
verifyMacchine($conn);

?>


<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/Functions.js"></script>
    <link href="css/StyleGeneral.css" rel="stylesheet" type="text/css">
    <link href="css/Home.css" rel=stylesheet type="text/css"/>

</head>
<body>

<?php
Navbar();

if(isset($_COOKIE["ok_macchine"]))
{
    echo '<div  class="container" style="background-color:green;" id=\"popupadmin\"><h3>Result: </h3><p>' . $_COOKIE["ok_macchine"] . '</p><br></div>';

}else if(isset($_COOKIE["not_macchine"]))
{
    echo '<div  class="container" style="background-color:red;" id=\"popupadmin\"><h3>Result: </h3><p>' . $_COOKIE["not_macchine"] . '</p><br></div>';

}

?>


<div class="container" id="containerHome">
    <div class="jumbotron">
        <h1>Stampanti 3D's Page</h1>
        <p>Created by Aniello Paoo Malinconico</p>
    </div>


    <div class="row">
        <div class="col-sm-8">
            <h3>Our Products, your life ...</h3>

            <br>
         <script>  loadImg(); </script>

            <div class="dumbCrossFade">
                <img width="600" height="450" src="img/stampante1.jpg" alt="xxxx" />
            </div>

        </div>
        <div class="col-sm-4">
            <h3>Informazioni Personali</h3>

            <?php

            if (($user=userLoggedIn())!=false) {
              echo '<ul class="list-group">
                <li class="list-group-item list-group-item-info"><a href="Home.php">Benvenuto</a></li>
                <li class="list-group-item list-group-item-info"><a href="Prenotation.php">Le mie Prenotazioni</a></li>
                <li class="list-group-item list-group-item-info"><a href="Aboutus.php">About us</a></li>
                <li class="list-group-item list-group-item-info"><a href="Contactus.php">Contact us</a></li>
            </ul>';
            }
            else
            {
                echo $user;
                echo '<ul class="list-group">
                            <li class="list-group-item list-group-item-info"><a href="Login.php">Login</a></li>
                            <li class="list-group-item list-group-item-info"><a href="Registration.php">Sign up</a></li>
                            <li class="list-group-item list-group-item-info"><a href="Prenotation.php">Schedule Print \'s rents</a></li>
                </ul>';
            }

            ?>


        </div>
    </div>



    <br>
    <footer>
        <div class="col-sm-4">
            <img src="http://security.polito.it/img/polito.gif" alt="Polito" width="100" height="100">
        </div>
        <br><br><br>
        <div class="col-sm-8" id="firma">
            <p>Created by Malinconico Aniello Paolo </p>

        </div>

    </footer>
    </div>
</body>
</html>
