<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 02/06/16
 * Time: 16:59
 */


include("Functions.php");

session();
$conn = dbConnect();

if (isset($_REQUEST['register'])) {

    $_REQUEST['nome']= Validation($_REQUEST['nome']);
    $_REQUEST['cognome']= Validation($_REQUEST['cognome']);
    $_REQUEST['password_confirmation']= Validation($_REQUEST['password_confirmation']);
    $_REQUEST['password']= Validation($_REQUEST['password']);
    $_REQUEST['email']= Validation($_REQUEST['email']);


//This makes sure they did not leave any fields blank
    if (!$_REQUEST['nome'] | !$_REQUEST['cognome'] | !$_REQUEST['password_confirmation']| !$_REQUEST['password']| !$_REQUEST['email']) {
        die('You did not complete all of the required fields');
    }

    $usercheck = $_REQUEST['email'];
    if(strpos($usercheck,'@')===false)
        die("username not valid");

    $check = mysqli_query($conn, "SELECT * FROM UTENTI WHERE USERNAME = '$usercheck'") or die(mysqli_error());

    $check2 = mysqli_num_rows($check);


//if the name exists it gives an error
    if ($check2 != 0) {
        die('Sorry, the username ' . $_REQUEST['email'] . ' is already in use.');
    }
// this makes sure both passwords entered match
    if ($_REQUEST['password'] != $_REQUEST['password_confirmation']) {
        die('Your passwords did not match. ');
    }
// here we encrypt the password and add slashes if needed
//password = md5(password+timestamp);
    $timestampsql=rand(0,100);
    $passwordsql=$_REQUEST['password'];
    $passfinal=$passwordsql.$timestampsql;

    $passfinal = md5($passfinal);

//echo $timestampsql .' '.$passfinal.' '.$_REQUEST['username'].' '.$_REQUEST['cognome'].''.$_REQUEST['nome'];
// now we insert it into the database
    $insert = "INSERT INTO UTENTI (NOME, COGNOME, PASSWORD, USERNAME, SALT ) VALUES ('" . $_REQUEST['nome'] . "', '" . $_REQUEST['cognome'] . "', '" . $passfinal . "', '" . $_REQUEST['email'] . "', '" . $timestampsql . "')";
    $add_member = mysqli_query($conn,$insert);
    if($add_member==true)
       Redirect("Home.php");
    else {

        Redirect("Registration.php");
    }
}
else
{
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registrazione</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="Registration.js"></script>
    <link href="css/Registration.css" rel="stylesheet" type="text/css">
    <link href="css/StyleGeneral.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php
Navbar();
?>

<div class="container" >

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <form action="Registration.php" method="post" id="formRegistration" name="formRegistration">
                <h2>Please Sign Up <small>to rent our products !</small></h2>
                <hr class="colorgraph">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="nome" id="nome" class="form-control input-lg" placeholder="First Name" tabindex="1">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="cognome" id="cognome" class="form-control input-lg" placeholder="Last Name" tabindex="2">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="4">
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="5">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-lg" placeholder="Confirm Password" tabindex="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-sm-3 col-md-3">
					<span class="button-checkbox">
						<button type="button" class="btn" data-color="info" tabindex="7">I Agree</button>
                        <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
					</span>
                    </div>
                    <div class="col-xs-8 col-sm-9 col-md-9">
                        By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> set out by this site, including our Cookie Use.
                    </div>
                </div>

                <hr class="colorgraph">
                <div class="row">
                    <div class="col-xs-12 col-md-12"><input id="register" name="register" onclick="ClickRegistrazione()" type="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-x">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->





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

<?php
}
?>


