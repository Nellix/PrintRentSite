<?php
/**
 * Created by PhpStorm.
 * User: AnielloMalinconico
 * Date: 02/06/16
 * Time: 16:57
 */

define("DB_SERVER","127.0.0.1");
define("DB_NAME","SitoDB");
define("DB_USER_NAME","root");
define("DB_USER_PWD","");
define("NUM_STAMPANTI",4);

function dbConnect() {

    $conn = mysqli_connect(DB_SERVER,DB_USER_NAME, DB_USER_PWD,DB_NAME);
    if(mysqli_connect_error()) {
        Header("Errore di collegamento al DB");
    }
    return $conn;
}


function Navbar()
{
    echo '<nav class="navbar navbar-default" id="bs-example-navbar-collapse">

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav" id="bs-example-navbar-collapse-2">
                <li role="presentation"><a href="Home.php"><img  alt="Brand" src="img/u.png" width="10" height="10"> Home</a></li>

                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        Products <span class="caret"></span>
                        <span class="badge">2</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a href="#">Stampanti</a></li>
                        <li role="presentation"><a href="Stampanti3D.php">Stampanti 3D</a></li>
                    </ul>
                </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        Prenotaton\'s Section <span class="caret"></span>
                        <span class="badge">2</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a href="Prenotation.php">Tutte le Prenotazioni</a></li>
                        <li id="Lemieprenotazioni" hidden role="presentation"><a href="LeMiePrenotazioni.php">Le mie Prenotazioni</a></li>
                    </ul>
                </li>
                <li role="presentation" ><a href=#>About us</a></li>
            </ul>';




if (($user=userLoggedIn())!=false) {
    echo $user;
    echo '<div class="navbar-header"><a class="navbar-brand" >' . 'Benvenuto,  ' . $user . '</a></div>';
    echo '<form class="navbar-form navbar-right" action="Logout.php" method="post">'
        . '<button class="btn btn-default" id="logout" name="logout">Logout</button> </form>';
    echo '<script>$(\'#Lemieprenotazioni\').show();</script>';

}else
{
    echo $user;
    echo '<ul class="nav navbar-nav"><li ><a href="Login.php">Login</a></li></ul>';

}

  echo '</div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>';


    
}




function session()
{
 //   echo session_id();
    session_start();
    session_status();

    $t=time();
    $diff=0;
    $new=false;

    if (isset($_SESSION['time']))
    {
        $t0=$_SESSION['time'];
        $diff=($t-$t0); // inactivity period
    } else
    {
        $new=true;
    }


    if (!$new && ($diff > 120)) // new or with inactivity period too long
    {
        //session_unset(); // Deprecated
        $_SESSION=array(); //azzero il contenuto di session
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!

        if (ini_get("session.use_cookies")) { // PHP using cookies to handle session
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600*24, $params["path"], $params["domain"],$params["secure"], $params["httponly"]);
        }

        session_destroy();  // destroy session
           /// redirect client to login page

       // header('HTTP/1.1 307 temporary redirect');
        Redirect('Home.php');
    exit; // IMPORTANT to avoid further output from the script
    }else
    {
        $_SESSION['time']=time(); /* update time */
       // echo '<html><body>Tempo ultimo accesso aggiornato: ' .$_SESSION['time'].'</body></html>';
  }

}


function userLoggedIn() {
    if (isset($_SESSION['username'])) {
     return ($_SESSION['username']);
  } else {
    return false;
}
}



function Logout()
{
    session_start();
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600 * 24,
            $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        setcookie("username","", time() - 3600 * 24);
        setcookie("password","", time() - 3600 * 24);
    }
    session_destroy();  // destroy session

    Redirect("Home.php");
}


function queryMacchine($conn)
{
    $check = mysqli_query($conn, "SELECT * FROM MACCHINE")or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
        die('Any products in our database.<br /><br />If you think this is wrong <a href="Prenotation.php">try again</a>.');
    }

  // echo "Numero macchine : ".$check2;

    $container = Array();
    $container["nmacchine"]=$check2;

    $i=0;

    while($info = mysqli_fetch_array( $check )) {

        $container["NOME".$i] = $info["NOME"];
        $container["ID".$i] = $info["ID"];
        $container["DESCRIZIONE".$i] = $info["DESCRIZIONE"];
        $container["QTA".$i] = $info["QTA"];
        $i++;
    }

    return $container;
}


function queryPrenotazioneMacchina($conn,$id_macchina)
{
    $check = mysqli_query($conn, "SELECT * FROM PRENOTAZIONI WHERE MACCHINA = ".$id_macchina)or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
      //  die('Any products in our database.<br /><br />If you think this is wrong <a href="Prenotation.php">try again</a>.');
        return null;
    }

    $container = Array();
    $container["nprenotazioni"]=$check2;

   // echo "dslknò  ".$check2;

    $i=0;

    while($info = mysqli_fetch_array( $check )) {

        $container["ID".$i] = $info["ID"];
        $container["MACCHINA".$i] = $info["MACCHINA"];
        $container["DURATA".$i] = $info["DURATA"];
        $container["DATA_INIZIO".$i] = $info["DATA_INIZIO"];
        $container["DATA_FINE".$i] = $info["DATA_FINE"];
        $container["UTENTE".$i] = $info["UTENTE"];
        $i++;
    }

    return $container;
}



function queryMySchedul($conn,$id_macchina,$utente)
{

    $sql = "SELECT * FROM PRENOTAZIONI WHERE MACCHINA = \"$id_macchina\" AND UTENTE = \"$utente\"";
    $check = mysqli_query($conn, $sql)or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
        //  die('Any products in our database.<br /><br />If you think this is wrong <a href="Prenotation.php">try again</a>.');
        return null;
    }

    $container = Array();
    $container["nprenotazioni"]=$check2;

    // echo "dslknò  ".$check2;

    $i=0;

    while($info = mysqli_fetch_array( $check )) {

        $container["ID".$i] = $info["ID"];
        $container["MACCHINA".$i] = $info["MACCHINA"];
        $container["DURATA".$i] = $info["DURATA"];
        $container["DATA_INIZIO".$i] = $info["DATA_INIZIO"];
        $container["DATA_FINE".$i] = $info["DATA_FINE"];
        $container["UTENTE".$i] = $info["UTENTE"];
        $i++;
    }

    return $container;
}


function selectMacchineOccupatel($conn,$DataInizio,$DataFine)
{
    $sql = "SELECT DISTINCT MACCHINA FROM PRENOTAZIONI WHERE DATA_FINE >\"$DataInizio\" AND DATA_INIZIO < \"$DataFine\"";
    $check = mysqli_query($conn, $sql)or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
        //  die('Any products in our database.<br /><br />If you think this is wrong <a href="Prenotation.php">try again</a>.');
        return null;
    }

    $container = Array();
    $container["nmacchinedisp"]=$check2;

    // echo "dslknò  ".$check2;

    $i=0;

    while($info = mysqli_fetch_array( $check )) {
        
        $container["MACCHINA".$i] = $info["MACCHINA"];
        $i++;
    }

    return $container;  //return id's machine not free
}


function insertPrenotation($conn,$idmacchina,$durata,$data_inizio,$data_fine,$utente)
{
    echo " " . $idmacchina . " " . $durata . " " . $data_inizio . " " . $data_fine . " " . $utente;
    $hour = time() + 60;
    $sql = "INSERT INTO PRENOTAZIONI (MACCHINA,DURATA,DATA_INIZIO,DATA_FINE,UTENTE) VALUES  ('" . $idmacchina . "', '" . $durata . "', '" . $data_inizio . "', '" . $data_fine . "', '" . $utente . "')";
    $check = mysqli_query($conn, $sql) or die(mysqli_error());
    //Gives error if user dosen't exis
    //$check2 = mysqli_num_rows($check);
    if ($check == false) {
        $stringa="null";
        $text="Query non riuscita";
        setcookie("stringa",$stringa,$hour);
        setcookie("Popup",1,$hour);
        setcookie("text",$text,$hour);
        setcookie("sql",$sql,$hour);
      
    } else
    {
        $id=selecdIDPrenotazione($conn,$data_inizio,$data_fine,$utente,$idmacchina);
       // echo "ID INSERT : ".$id."---";

        setcookie($id, $id ,$hour);
        $stringa=mysqli_affected_rows($conn);

        if($stringa!=0)
        {
            $text="Numero rghe inserite con successo:".$stringa;
            setcookie("Popup",1,$hour);
            setcookie("stringa",$stringa,$hour);
            setcookie("text",$text,$hour);
            setcookie("sql",$sql,$hour);
        }else
        {
            $text="Query non corretta.Prenotazione non disponibile. Numero prenotazioni inserite:".$stringa;
            setcookie("Popup",1,$hour);
            setcookie("stringa",$stringa,$hour);
            setcookie("text",$text,$hour);
            setcookie("sql",$sql,$hour);
        }

        Redirect("LeMiePrenotazioni.php");
    }

}

function deletePrenotation($conn,$data_inizio,$data_fine,$utente,$idmacchina)
{
  //  echo   " " . $data_inizio . " " . $data_fine . " " . $utente;
    $id=selecdIDPrenotazione($conn,$data_inizio,$data_fine,$utente,$idmacchina);
    $hour = time() + 60;
    $stringa="";
    if(!(isset($_COOKIE[$id])))
    {
        $sql = "DELETE FROM PRENOTAZIONI WHERE DATA_FINE =\"$data_fine\" AND DATA_INIZIO = \"$data_inizio\" AND UTENTE = \"$utente\" AND MACCHINA=\"$idmacchina\"";
        $check = mysqli_query($conn, $sql) or die(mysqli_error());
        //Gives error if user dosen't exis
        //$check2 = mysqli_num_rows($check);
        if ($check == false) {
            $stringa="null";
            $text="Query non riuscita";
            setcookie("stringa",$stringa,$hour);
            setcookie("Popup",1,$hour);
            setcookie("text",$text,$hour);
            setcookie("sql",$sql,$hour);
        } else
        {
            setcookie($id, $id ,$hour);
            $stringa=mysqli_affected_rows($conn);

            if($stringa!=0)
            {
                $text="Numero righe cancellate con successo:".$stringa;
                setcookie("Popup",1,$hour);
                setcookie("stringa",$stringa,$hour);
                setcookie("text",$text,$hour);
                setcookie("sql",$sql,$hour);
            }else
            {
                $text="Query non corretta. Numero prenotazioni inserite:".$stringa;
                setcookie("Popup",1,$hour);
                setcookie("stringa",$stringa,$hour);
                setcookie("text",$text,$hour);
                setcookie("sql",$sql,$hour);
            }
        }

    }else {
        $text="Devi aspettare un minuto prima di poterla cancellare";
        setcookie("Popup",1,$hour);
        setcookie("stringa",10000,$hour);
        setcookie("wait","ok",$hour);
        setcookie("text",$text,$hour);
    }
    Redirect("LeMiePrenotazioni.php");
}

function SelectUtenti($conn)
{
    $sql = "SELECT * FROM UTENTI ";
    $check = mysqli_query($conn, $sql)or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
        //  die('Any products in our database.<br /><br />If you think this is wrong <a href="Prenotation.php">try again</a>.');
        return null;
    }

    $container = Array();
    $container["nutenti"]=$check2;

    $i=0;

    while($info = mysqli_fetch_array( $check )) {

        $container[$i] = $info["USERNAME"];
        $i++;
    }

    return $container;  //return id's machine not free
}

function selecdIDPrenotazione($conn,$data_inizio,$data_fine,$utente,$idmacchina)
{
    $sql = "SELECT ID FROM PRENOTAZIONI WHERE DATA_FINE = \"$data_fine\" AND DATA_INIZIO = \"$data_inizio\" AND UTENTE = \"$utente\" AND MACCHINA=\"$idmacchina\"";

    $check = mysqli_query($conn, $sql)or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
    return null;
    }else{
        $info = mysqli_fetch_array( $check );
        $idp=$info["ID"];
    }
    return $idp;
}


function SetMacchine($conn,$numMacchine)
{

    $sql = "DELETE FROM MACCHINE ";
    $check = mysqli_query($conn, $sql) or die(mysqli_error());

    $i=1;
    $hour = time() + 10;
    $nomiMacchine = array("", "Printer s3000", "Printer g-4700s","Printer Series Azul","Printer HZ-990","Printer Y-890","Printer Series X","Printer Series TOP-990");
    $descrizione = array("", "Bella", "TOP","Bella","Migliore","Bella","Series X","Series TOP-990");

    $uno=1;
    $go=true;
    while($i<=$numMacchine && $go==true)
    {
        $sql = "INSERT INTO MACCHINE (ID,NOME,QTA,DESCRIZIONE) VALUES  ('" . $i . "', '" . $nomiMacchine[$i] . "', '" .$uno. "', '" . $descrizione[$i] . "')";
        $check = mysqli_query($conn, $sql) or die(mysqli_error());
        //Gives error if user dosen't exis
        //$check2 = mysqli_num_rows($check);
        if ($check == false) {

            $stringa="Errore nelle insert";
            setcookie("not_macchine",$stringa,$hour);
            $go=false;

        } else
        {

            $stringa=mysqli_affected_rows($conn);

            if($stringa==0)
            {
                $go=false;
            }
        }

        $i++;
     }

    $i--;
            if($i==$numMacchine)
            {
                $text="Numero rghe inserite con successo:".$i;
                setcookie("ok_macchine",$text,$hour);

            }else
            {
                $stringa="Query non corretta.Prenotazione non disponibile. Numero macchine inserite:".$stringa;
                setcookie("not_macchine",$stringa,$hour);

            }


    Redirect("admin.php");



}


function convDate($data)
{
    $gg=substr($data,0,2);
    $mm=substr($data,3,2);
    $yy=substr($data,6,4);
    $hh=substr($data,11,2);
    $mi=substr($data,14,2);
    
    $date=$yy.'-'.$mm.'-'.$gg." ".$hh.":".$mi.":00";
    
    return $date;

}


function verifyMacchine($conn)
{
    if(!(isset($_SESSION["NUM_STAMPANTI"])))
    {
        mysqli_autocommit($conn,false);
        $hour = time() + 20;
        $sql='SELECT * FROM MACCHINE FOR UPDATE ';
        $check = mysqli_query($conn, $sql) or die(mysqli_error());
        //Gives error if user dosen't exis
        //$check2 = mysqli_num_rows($check);
        if ($check == false) {

            $stringa="Errore nelle insert";
            setcookie("not_macchine",$stringa,$hour);


        } else
        {
            $stringa = mysqli_affected_rows($conn);
            $nprint=$stringa;

            if($stringa > NUM_STAMPANTI)
            {
                $sql='SELECT ID FROM MACCHINE WHERE ID NOT IN (SELECT MACCHINA FROM PRENOTAZIONI) ';
                $check = mysqli_query($conn, $sql) or die(mysqli_error());
                if ($check == false) {

                    $text="Errore nelle insert";
                    setcookie("not_macchine",$text,$hour);


                } else
                {


                    $var=0;
                    $num=mysqli_affected_rows($conn);

                    while(  $info = mysqli_fetch_array($check))
                    {
                        $ids_macchine[$var]=$info[0];
                        echo $info[0]." ";
                        $var++;
                    }

                        $var=0;
                  while($nprint > NUM_STAMPANTI && $num>0)
                    {

                        echo 'ID MACCHINA : ' . $ids_macchine[$var]." ";
                        $sql = 'DELETE FROM MACCHINE WHERE ID = ' . $ids_macchine[$var] . ' ';

                        $nprint--;
                        $num--;
                        $var++;


                        $check = mysqli_query($conn, $sql) or die(mysqli_error());
                        if ($check == false) {

                            $stringa="Non posso cancellare ulteriori macchine. Tutte le stampanti sono occupate";
                            setcookie("not_macchine",$stringa,$hour);
                        }


                    }
                   $stringa="Numero macchine cancellate:".$var;
                    setcookie("ok_macchine",$stringa,$hour);
                     }
            }else if($stringa < NUM_STAMPANTI)
            {
                //INSERT
                $i=$stringa;

                $sql='SELECT MAX(ID) FROM MACCHINE ';
                $check = mysqli_query($conn, $sql) or die(mysqli_error());
                if ($check == false) {

                    $stringa="Errore nelle insert";
                    setcookie("not_macchine",$stringa,$hour);


                } else{
                    $info = mysqli_fetch_array( $check );
                    $idp=$info;
                    $idp++;
                $nomiMacchine = array("Printer s3000", "Printer g-4700s","Printer Series Azul","Printer HZ-990","Printer Y-890","Printer Series X","Printer Series TOP-990");
                $descrizione = array("Bella", "TOP","Bella","Migliore","Bella","Series X","Series TOP-990");

                $uno=1;
                $go=true;
                while($i<NUM_STAMPANTI && $go==true)
                {
                    $sql = "INSERT INTO MACCHINE (ID,NOME,QTA,DESCRIZIONE) VALUES  ('" . $idp . "', '" . $nomiMacchine[$i] . "', '" .$uno. "', '" . $descrizione[$i] . "')";
                    $check = mysqli_query($conn, $sql) or die(mysqli_error());
                    $idp++;
                    //Gives error if user dosen't exis
                    //$check2 = mysqli_num_rows($check);
                    if ($check == false) {

                        $stringa="Errore nelle insert";
                        setcookie("not_macchine",$stringa,$hour);
                        $go=false;

                    } else
                    {

                        $stringa=mysqli_affected_rows($conn);

                        if($stringa==0)
                        {
                            $go=false;
                        }
                    }

                    $i++;
                }


                if($i==NUM_STAMPANTI)
                {
                    $text="Ho in totale ".$i." stampanti";
                    setcookie("ok_macchine",$text,$hour);

                }else
                {
                    $stringa="Query non corretta.Prenotazione non disponibile. Numero macchine inserite:".$stringa;
                    setcookie("not_macchine",$stringa,$hour);

                }

            }
            }



        }

        mysqli_commit($conn);
        $_SESSION["NUM_STAMPANTI"]=NUM_STAMPANTI;

        Redirect("Home.php");
    }
    
    
    
    
}



function insert($conn, $data_inizio, $data_fine,$idmax,$num_macchine,$idMacchine,$dur,$utente)
{
   // $id_not_disp = selectMacchineOccupatel($conn, $data_inizio, $data_fine);
    mysqli_autocommit($conn,false);
    $sql = "SELECT ID FROM MACCHINE WHERE ID NOT IN (SELECT DISTINCT MACCHINA FROM PRENOTAZIONI WHERE DATA_FINE >\"$data_inizio\" AND DATA_INIZIO < \"$data_fine\") ORDER BY ID FOR UPDATE ";
    $check = mysqli_query($conn, $sql)or die(mysqli_error());
    //Gives error if user dosen't exist
    $check2 = mysqli_num_rows($check);
    if ($check2 == 0){
        //  die('Any products in our database.<br /><br />If you think this is wrong <a href="Prenotation.php">try again</a>.');
       $id_disp=NULL;
    }else
    {
        $id_disp = Array();
        $id_disp["nmacchinedisp"]=$check2;

        // echo "dslknò  ".$check2;

        $i=0;

        while($info = mysqli_fetch_array( $check )) {

            $id_disp[$i] = $info["ID"];
            $i++;
        }
    }

echo ''.$id_disp["nmacchinedisp"];
    $num = 0;
    if ($id_disp != NULL) {

        $num=$id_disp[0];
        echo 'provo1 con  '.$num." ";

       // insertPrenotation($conn, $num, $dur, $data_inizio, $data_fine, $utente);
        echo " " . $num . " " . $dur . " " . $data_inizio . " " . $data_fine . " " . $utente;
        $hour = time() + 60;
        $sql = "INSERT INTO PRENOTAZIONI (MACCHINA,DURATA,DATA_INIZIO,DATA_FINE,UTENTE) VALUES  ('" . $num . "', '" . $dur . "', '" . $data_inizio . "', '" . $data_fine . "', '" . $utente . "')";
        $check = mysqli_query($conn, $sql) or die(mysqli_error());
        //Gives error if user dosen't exis
        //$check2 = mysqli_num_rows($check);
        if ($check == false) {
            $stringa="null";
            $text="Query non riuscita";
            setcookie("stringa",$stringa,$hour);
            setcookie("Popup",1,$hour);
            setcookie("text",$text,$hour);
            setcookie("sql",$sql,$hour);

        } else
        {
            $id=selecdIDPrenotazione($conn,$data_inizio,$data_fine,$utente,$num);
            // echo "ID INSERT : ".$id."---";

            setcookie($id, $id ,$hour);
            $stringa=mysqli_affected_rows($conn);

            if($stringa!=0)
            {
                $text="Numero rghe inserite con successo:".$stringa;
                setcookie("Popup",1,$hour);
                setcookie("stringa",$stringa,$hour);
                setcookie("text",$text,$hour);
                setcookie("sql",$sql,$hour);
            }else
            {
                $text="Query non corretta.Prenotazione non disponibile. Numero prenotazioni inserite:".$stringa;
                setcookie("Popup",1,$hour);
                setcookie("stringa",$stringa,$hour);
                setcookie("text",$text,$hour);
                setcookie("sql",$sql,$hour);
            }

            Redirect("LeMiePrenotazioni.php");
        }
                 mysqli_commit($conn);
    } else if ($id_disp == NULL) {
        $sql="Prenotazione non disponibile. Macchine tutte occupate";
        $hour = time() + 10;

        $stringa="10001";
        setcookie("stringa",$stringa,$hour);
        setcookie("Popup",1,$hour);
        setcookie("notaviable","OK",$hour);
        setcookie("sql",$sql,$hour);
        Redirect("LeMiePrenotazioni.php");
    }


}


function Validation($word)
{
    $word = trim($word);
    $word = stripslashes($word);
    $word = htmlspecialchars($word);
    return $word;
}


function Redirect($page)
{
    if($_SERVER["HTTPS"] != "on") {
        $pageURL = "Location: https://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"]. "/sitoExam/".$page;
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"]. "/sitoExam/".$page;
        }
        Header($pageURL);
    }else
    {

        $pageURL ="Location: https://". $_SERVER["SERVER_NAME"]. "/sitoExam/".$page;
        Header($pageURL);

    }




}

?>