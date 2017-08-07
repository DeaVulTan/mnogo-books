<?php
    header("Content-type:text/html; charset=koi8-r"); 
    session_start();
    $_SESSION['login']=$_POST['login'];
    $_SESSION['pass']=$_POST['pass'];
/*    if ( (md5($_POST['login'])==='ee2433259b0fe399b40e81d2c98a38b6')
    && 	(md5($_POST['pass'])==='634223c203e11f9b9fd090454bd69525') )
     echo "Yahoo!!!";
*/
    header("Location: book_form.php");
?>
