<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 05/06/16
 * Time: 20:05
 *
 *
 1)Se non si è loggati , mettere nella pagina di "Tuttele prenotazioni" il tasto "Prenota cin la scfitta "(Esegui log in)" vicino
    alla scritta "Preota"del tasto.
    1.1)Tale tasto divrà reindirizzzare alla paginia "Login.php".
 2)Navbar comenue a tutte le schermate , pure in "Login.php" e "Registrazione.php";
 3)Box "Id macchina"  nella div "removePrenotazione" di "LeMiePrenotazioni.php" allargarlo, il numero non si vede;
 4)Aggiungere DataTimebox ;
 5)Aggiustare tutti i die in form ;
 *
 */


include ("Functions.php");
session();
$conn = dbConnect();
$container=queryMacchine($conn);

$num_macchine = $container["nmacchine"];
//echo "Num macchine ".$num_macchine;
$i=0;
while($i<$num_macchine)
{

    $idMacchine[$i]=$container["ID".$i];

    $i++;
}
$i--;
$idmax=$idMacchine[$i];
//echo 'id max '.$idmax;

if (($user=userLoggedIn())==false) {
    Redirect("Login.php");
}else {

    if (isset($_REQUEST["InsPrenotazione"])) {
        $data_inizio = Validation($_POST['datainizio']);
        $data_fine = Validation($_POST['datafine']);

      //  08/06/2016 21:34 -> 2016-06-07 18:00:00;

        $data_inizio=convDate($data_inizio);
        $data_fine=convDate($data_fine);

        $secondiinizio = strtotime($data_inizio);
        $secondifine = strtotime($data_fine);

 //   echo "Data inizio : ".$data_inizio." Data fine : ".$data_fine;


        $dur = (($secondifine - $secondiinizio) / 60);
        //   echo "durata ".$dur;
        if ($dur > 1 && $secondifine > $secondiinizio && $secondifine != 0 && $secondiinizio != 0) {
            $utente = $_SESSION['username'];
            insert($conn, $data_inizio, $data_fine,$idmax,$num_macchine,$idMacchine,$dur,$utente);
        } else {
            die("Durata Prenotazione Non Valida");
        }


    }


    if(isset($_REQUEST["RemovePrenotazione"]))
    {
        $data_inizio = Validation($_REQUEST['datainizio']);
        $data_fine = Validation($_REQUEST['datafine']);

        $data_inizio=convDate($data_inizio);
        $data_fine=convDate($data_fine);
        $secondiinizio = strtotime($data_inizio);
        $secondifine = strtotime($data_fine);


        $dur = (($secondifine - $secondiinizio) );
        if($dur>60 && $secondifine>$secondiinizio && $secondifine != 0 && $secondiinizio != 0)
        {
            $utente = $_SESSION['username'];
            $idmacchina=Validation($_REQUEST['ids']);

            $delete=deletePrenotation($conn,$data_inizio,$data_fine,$utente,$idmacchina);

        }else
        {
            die("Controlla i campi. Valori non Validi.");
        }

    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Inserisci Prenotazione</title>

        <meta name="keywords" content="timeslider, time-slider, time slider, rangeslider, range-slider, range slider, jquery, javascript">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link href="css/timeslider.css" rel="stylesheet" type="text/css"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/Functions.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

        <link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen"
              href="https://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
    </head>
    <body>


<?php
Navbar();



?>




<?php

    if(isset($_COOKIE["Popup"])) {
        if($_COOKIE["stringa"] != null) {
            if ($_COOKIE["stringa"] > 0) {
                if (isset($_COOKIE["wait"]))
                {
                    echo '<div  class="container" style="background-color:red;" id=\"popupdivOK\"><br><p>' . $_COOKIE["text"] . ' </p></div>';

                }else if(isset($_COOKIE["notaviable"]))
                {
                    echo '<div  class="container" style="background-color:red;" id=\"popupdivOK\"><br><p>'.$_COOKIE["sql"].' </p></div>';

                }else {
                    echo '<div  class="container" style="background-color:green;" id=\"popupdivOK\"><h3>Result: </h3><p>Query: ' . $_COOKIE["sql"] . '</p><br><p>' . $_COOKIE["text"] . '</p></div>';
                }

            } else if ($_COOKIE["stringa"] == 0) {
                echo '<div class="container" style="background-color:orange;" id=\"popupdivOK\"><h3>Result: </h3><p>Query: ' . $_COOKIE["sql"] . '</p><p>' . $_COOKIE["text"] . '</p></div>';


            }else
            {
                echo '<div  class="container" style="background-color:red;" id=\"popupdivOK\"><h3>Result: </h3><p>Query: ' . $_COOKIE["sql"] . '</p><br><p>'.$_COOKIE["text"].' </p></div>';

            }
        }
        $hour = time() - 60;
        setcookie("Popup",1,$hour);
        setcookie("stringa","",$hour);
        setcookie("sql","",$hour);
    }
?>
 

    <script src="js/timeslider.js">
        var current_time = (new Date()).getTime() + ((new Date()).getTimezoneOffset() * 60 * 1000 * -1);
       // $('#datainizio').attr("placeholder",getTime() ).val("").focus().blur();
    </script>

<div class="rows" hidden id="divPrenotazione" >
    <h3>Aggiungiamo Prenotazione </h3>
    <form action="LeMiePrenotazioni.php" method="post">

        <div class="col-xs-4 col-md-4" class="form-group">
            <p>Data Inizio</p>
            <div class="input-group date" id='datetimepicker2' >
                <input type="datetime" name="datainizio" id="datainizio" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div  class="col-xs-4 col-md-4" class="form-group">
            <p>Data Fine</p>
            <div class="input-group date" id='datetimepicker1'>
                <input name="datafine" id="datafine" type="datetime" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <br>
        <div class="col-xs-4 col-md-4"><input name="InsPrenotazione"  type="submit" value="Inserisci Prenotazione" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
        <br>
    </form>
</div>


    <script>
        var d = new Date();
    //    d.substringData()s

    //    $('#datainizio').attr("placeholder",d.toLocaleString()).val(d.toLocaleString()).focus().blur();
    </script>

    <div hidden class="rows" id="rimPrenotazione" >
        <h3>Rimuovi Prenotazione </h3>
        <form action="LeMiePrenotazioni.php" method="post">

            <div class="col-xs-4 col-md-4" class="form-group">
                <p>Data Inizio</p>
                <div class="input-group date" id='datetimepicker4' >
                    <input type="datetime" name="datainizio" id="datainizio" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div  class="col-xs-4 col-md-4" class="form-group">
                <p>Data Fine</p>
                <div class="input-group date" id='datetimepicker3'>
                    <input name="datafine" id="datafine" type="datetime" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="col-xs-4 col-md-4" class="form-group">
                <p>Id Printer</p>
                <input type="text" name="ids" id="ids" class="form-control input-lg" placeholder="id" tabindex="3">
            </div>

            <div class="col-xs-12 col-md-12"><input name="RemovePrenotazione"  type="submit" value="Rimuovi Prenotazione" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
            <br>
            <br>
        </form>

    </div>


<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
            locale: 'it'
        });
    });
    $(function () {
        $('#datetimepicker2').datetimepicker({
            locale: 'it'
        });
    });
    $(function () {
        $('#datetimepicker3').datetimepicker({
            locale: 'it'
        });
    });
    $(function () {
        $('#datetimepicker4').datetimepicker({
            locale: 'it'
        });
    });
</script>

    <?php

    $num_macchine = $container["nmacchine"];
    //echo "Num macchine ".$num_macchine;
    $i=0;
    while($i<$num_macchine)
    {
        // echo "mi, macchine".$num_macchine;
        $nome=$container["NOME".$i];
        $id=$container["ID".$i];
        $macchine_id[$i]=$id;
        /*
            -AGGIUNGERE DESCRIZIONE
            -ADD QTA

         */
        echo   '<h3>'."Stampante  ".$id.'</h3><div id="'.$id.'" class="time-slider"></div>';

        $i++;
    }

    ?>

    <p id="messages"></p>



    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>



    <script type="text/javascript">
        var current_time = (new Date()).getTime() + ((new Date()).getTimezoneOffset() * 60 * 1000 * -1);

        $(document).ready(function () {
            (function () {
                $('#version').text('Version: '+ $.fn.TimeSlider.VERSION);
            })();




            <?php

            $username=$_SESSION["username"];
            $i=0;
            while($i<$num_macchine) {
                //  echo $conn;

                $result = queryMySchedul($conn, $macchine_id[$i],$username);

                if ($result == NULL) {


                    echo('$(\'#' . $macchine_id[$i] . '\').TimeSlider({
         
                start_timestamp: current_time - 3600 * 12 * 1000,
                init_cells: [
						{
                    \'start\': (current_time )- (3600 *5 * 1000) ,
                    \'text\': \'nell\',
                            \'style\': {
                            \'background-color\': \'#1cf055\'
                            }
                        },
				');

                    echo ']});';
                    echo '
            ';

                }else
                {

                    $num_prenotazioni = $result["nprenotazioni"];


                    echo('$(\'#' . $macchine_id[$i] . '\').TimeSlider({
                start_timestamp: current_time - 3600 * 12 * 1000,
                init_cells: [');

                    $j = 0;
                    while ($j < $num_prenotazioni)
                    {
                        $nome = $result["MACCHINA" . $j];
                        $id_prenotazione = $result["ID" . $j];
                        $durata = $result["DURATA" . $j];
                        $data_inizio = $result["DATA_INIZIO" . $j];
                        $data_fine = $result["DATA_FINE" . $j];
                        $utente = $result["UTENTE" . $j];

                        $inizio = strtotime($data_inizio);
                        $fine = strtotime($data_fine);


                        echo('{
                          
                            \'start\': '.((2*3600*1000)+($inizio*1000)).' ,
                            \'stop\': '.((2*3600*1000)+($inizio*1000)+($durata*60*1000)).',
                            \'style\': {
                            \'background-color\': \'#ff7680\'
                            }
                        },
				');
                        $j++;
                    }

                    echo ']});';
                    echo '
            ';
                }

                $i++;


            }
            ?>




        });
    </script>







    <br>
    <br>

    <div class="col-xs-4 col-md-4"><input id="addPrenotazione" onclick="addPrenotazione()" type="button" value="Add Prenotazione" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
    <div class="col-xs-4 col-md-4"><input id="RemovePrenotazione" onclick="RemovePrenotazione()" type="button" value="Remove Prenotazione" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>


    </body>
    </html>


    <?php


}

?>