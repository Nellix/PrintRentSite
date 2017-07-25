<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 07/06/16
 * Time: 19:48
 */


include ("Functions.php");
//session();
$conn = dbConnect();
session();
print_r($_SERVER);




if(isset($_REQUEST["insmacchine"]))
{
    SetMacchine($conn,$_REQUEST["nummacchine"]);
}

if(isset($_COOKIE["ok_macchine"]))
{
    echo '<div  class="container" style="background-color:green;" id=\"popupadmin\"><h3>Result: </h3><p>' . $_COOKIE["ok_macchine"] . '</p><br></div>';

}else if(isset($_COOKIE["not_macchine"]))
{
    echo '<div  class="container" style="background-color:red;" id=\"popupadmin\"><h3>Result: </h3><p>' . $_COOKIE["not_macchine"] . '</p><br></div>';

}

?>



<head>
    <title>Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/Functions.js"></script>
    <link href="css/StyleGeneral.css" rel="stylesheet" type="text/css">
    <link href="css/Home.css" rel=stylesheet type="text/css"/>

</head>
<body>

<?php
Navbar();
?>



<div>
    <form action="admin.php" method="post">
    <h3 class="col-sm-4">Inserisci numero macchine :</h3>
        <br>
    <input  id="nummacchine" name="nummacchine"> </input>
        <input id="insmacchine" type="submit"  class="btn btn-primary btn-block btn-lg" value="Inserisci" name="insmacchine"> </input>
    </form>
</div>
</body>
