<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 02/06/16
 * Time: 16:59
 */


include("Functions.php");
session();

$conn=dbConnect();


if (($user=userLoggedIn())==false) {

//if the login form is submitted
    if (isset($_REQUEST['login'])) {

        $_REQUEST['username'] = Validation($_REQUEST['username']);
        $_REQUEST['password'] = Validation($_REQUEST['password']);

        // makes sure they filled it in
        if (!$_REQUEST['username']) {
            die('You did not fill in a username.');
        }
        if (!$_POST['password']) {
            die('You did not fill in a password.');
        }

        if ($_REQUEST['username'] == "admin") {
            Redirect("admin.php");
            // exit;
        }

        $check = mysqli_query($conn, "SELECT * FROM UTENTI WHERE USERNAME = '" . $_REQUEST['username'] . "'") or die(mysqli_error());
        //Gives error if user dosen't exist
        $check2 = mysqli_num_rows($check);
        if ($check2 == 0) {
            die('That user does not exist in our database.<br /><br />If you think this is wrong <a href="login.php">try again</a>.');
        }


        while ($info = mysqli_fetch_array($check)) {

            //     $_REQUEST['password'] = stripslashes($_REQUEST['password']);
            //     $info['PASSWORD'] = stripslashes($info['PASSWORD']);
            //     echo $_REQUEST['password'];

            //     $_REQUEST['password'] = md5($_REQUEST['password']);

            $password = $_REQUEST['password'];
            $timest = $info["SALT"];

            $passdb = $info["PASSWORD"];

            $passfinal2 = $password . $timest;
            echo $passfinal2;
            $passfinal2 = md5($passfinal2);

            $len = strlen($passfinal2);
            $passfinal2 = substr($passfinal2, 0, $len);
            //   echo "\n1)*** ".$passfinal2."\n";

            // echo "\n2)***".$passdb."\n";


            //gives error if the password is wrong
            if ($passfinal2 != $passdb) {
                die('Incorrect password, please <a href="Login.php">try again</a>.');
            } else { // if login is ok then we add a cookie
                $_REQUEST['username'] = $_REQUEST['username'];
                $_SESSION['username'] = $_REQUEST['username'];
                $hour = time() + 3600;


                setcookie("username", $_REQUEST['username'], $hour);
                setcookie("password", $passfinal2, $hour);


                Redirect("Home.php");
            }
        }


    } else {
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Login</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>


            <link href="css/Registration.css" rel="stylesheet" type="text/css">
            <link href="css/StyleGeneral.css" rel="stylesheet" type="text/css">

        </head>

        <body>

        <?php
        Navbar();
        ?>

        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <form action="Login.php" method="post">
                        <h2>Please Login In
                            <small> to rent our products !</small>
                        </h2>
                        <hr class="colorgraph">
                        <div class="form-group">
                            <input type="email" name="username" id="username" class="form-control input-lg"
                                   placeholder="Username " tabindex="4">
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control input-lg"
                                           placeholder="Password" tabindex="5">
                                </div>
                            </div>
                        </div>
                        <hr class="colorgraph">
                        <div class="row">
                            <div class="col-xs-12 col-md-12"><input name="login" type="submit" value="Login"
                                                                    class="btn btn-primary btn-block btn-lg"
                                                                    tabindex="7"></div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
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
}else
{
    Redirect("Home.php");
}
?>


