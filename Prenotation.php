<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 04/06/16
 * Time: 15:44
 */



include ("Functions.php");
session();
$conn = dbConnect();
$container=queryMacchine($conn);

$utenti=Array();
$utenti=SelectUtenti($conn);
$colore = array("#ea0e1e", "#0099ff","#dc25e7","#e6e725","#ea7513");

$numUtenti=$utenti["nutenti"];

$i=0;
while($i<$numUtenti)
{
    $nome=$utenti[$i];
    $coloriutenti[$nome]=$colore[$i];
    $i++;
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TimeSlider Demo</title>
    <meta name="description" content="TimeSlider Plugin for jQuery">
    <meta name="keywords" content="timeslider, time-slider, time slider, rangeslider, range-slider, range slider, jquery, javascript">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href="css/timeslider.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</head>
<body>

<?php
Navbar();
?>


<h3>Tutte le Prenotazioni:</h3>
<p id="Stato"></p>
<div style="margin: 50px 30px;">
    <p>Zoom 1...24 hours:</p>
    <div id="zoom-slider123" style="width:300px;margin-bottom:10px;"></div>
</div>



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
    echo   '<h3>'."Stampante ".$id.'</h3><div id="'.$id.'" class="time-slider"></div>';

    $i++;
}

?>

<p id="messages"></p>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>





<script src="js/timeslider.js">
    TimeSlider.DEFAULTS["timecell_enable_move"]:true;
</script>


<script type="text/javascript">
    var current_time = (new Date()).getTime() ;//+ ((new Date()).getTimezoneOffset() * 60 * 1000 * -1);

    $(document).ready(function () {
        (function () {
            $('#version').text('Version: '+ $.fn.TimeSlider.VERSION);
            $('#zoom-slider123').slider({
                min: 0,
                max: 24,
                value: 24,
                step: 0.1,
                slide: function(event, ui) {
                    $('#35').TimeSlider({hours_per_ruler: ui.value});
                    $('#44').TimeSlider({hours_per_ruler: ui.value});
                    $('#47').TimeSlider({hours_per_ruler: ui.value});
                    $('#48').TimeSlider({hours_per_ruler: ui.value});
                    $('#49').TimeSlider({hours_per_ruler: ui.value});
                    $('#50').TimeSlider({hours_per_ruler: ui.value});
                    $('#51').TimeSlider({hours_per_ruler: ui.value});
                    $('#52').TimeSlider({hours_per_ruler: ui.value});
                }
            });
        })();

        
        <?php

        $i=0;
        while($i<$num_macchine) {
            $result = queryPrenotazioneMacchina($conn, $macchine_id[$i]);

                if ($result == NULL) {


                    echo('$(\'#' . $macchine_id[$i] . '\').TimeSlider({
                start_timestamp: current_time - 3600 * 12 * 1000,
                init_cells: [
						{
                    \'start\': (current_time )- (3600 *5 * 1000) ,
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
                        
                        $col=$coloriutenti[$utente];
                        
                         //   console.log("COLORE:".$col);
                         //   echo "colore ".$col;
                        echo('{
                          
                            \'start\': '.((2*3600*1000)+($inizio*1000)).' ,
                            \'stop\': '.((2*3600*1000)+($inizio*1000)+($durata*60*1000)).',
                            \'style\': {
                            \'background-color\':\''.($col).'\' 
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

<?php
if (($user=userLoggedIn())!=false) {

    echo '<form action="LeMiePrenotazioni.php" method="post">
        <div class="row">
            <div class="col-xs-12 col-md-12"><input name="Prenota" type="submit" value="Prenota" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
        </div>
    </form>';
    
           echo '<div id="Legenda">
                    <ul class="list-group">';
    
        $colore = array("", "#ea0e1e", "#360eea","#dc25e7","#e6e725");
        $s=0;
        while($s<($numUtenti))
        {
                    $col=$coloriutenti[$utenti[$s]];

            echo '<div  class="col-sm-3"><li style=\'background-color: '.$col.'\' class="list-group-item"><p>'."Uente ".$utenti[$s].'</p></li></div>';
            $s++;
        }
    
                echo '</ul></div>  ';

}else
{
    echo '
        <div class="row">
            <div class="col-xs-12 col-md-12"><input onClick="javascript:location.href=\'Login.php\'" name="login" type="submit" value="Prenota(Accedi al Login)" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
        </div>
  ';

} 

?>


</body>
</html>
