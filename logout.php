<?php
session_start(); // Permite continuar la sesión.
//Si existe usuario...
if (isset($_SESSION['usuario'])) {
    //elimina los valores del la variable global $_SESSION
    $_SESSION = array();
    //Elimina la session
    session_destroy();
    //Redirijo a login
    header("Location:login.php");
}
