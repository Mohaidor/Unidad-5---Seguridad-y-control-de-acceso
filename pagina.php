<?php
//Continuo la session
session_start();

//Esta función retorna un string con el contenido de la cabecera de la pagina.php(Bienvenida,permisos,ultimo log)
function muestraCabecera()
{
    //Creo array que en función de los permisos de user será el texto del permiso o cadena vacía
    $permisos[] = $_SESSION['lectura'] ? 'ver' : '';
    $permisos[] = $_SESSION['escritura'] ? 'añadir' : '';
    $permisos[] = $_SESSION['admin'] ? 'administrar' : '';
    $usuario = $_SESSION['usuario'];

    //Muestro los valores del usuario y sus permisos
    $cabecera =  "<p>Bienvenido, " . $_SESSION['usuario'] . "! En esta página puedes:(" . implode(', ', array_filter($permisos)) . ')<a href="./logout.php">[Logout]</a></p>';
    //Creo una cookie con key conteniendo el nombre del user.Para cada user se crea una cookie con su usuario
    //La cookie como valor tiene la fecha actual y tiene una validez de 1 año
    setcookie("ultimoLog_$usuario", time(), time() + 365 * 24 * 60 * 60);
    //String con el texto y los valores
    return $cabecera;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4 - PDO</title>
</head>

<body>
    <!--Control de que existe el usuario. Si se carga esta página por primera vez y no estas logueado redirige a login-->
    <?php if (!isset($_SESSION['usuario'])) {
        header('location:./login.php');
        exit();
    }
    //muestra la cabecera con los valores de $_SESSION
    echo muestraCabecera();
    //Si existe la cookie 'ultimoLog . usuario'...
    if (isset($_COOKIE["ultimoLog_" . $_SESSION['usuario']])) {
        //Imprime este texto con la hora de ultima conexión
        echo "<p>Tu última conexión fue el " . date("d/m/y \a \l\a\s H:i", $_COOKIE["ultimoLog_" . $_SESSION['usuario']]) . '</p>';
    } else {
        //Si no existe la cookie es la primera vez que se loguea
        echo '<p>Bienvenido por primera vez';
    }
    ?>
    <form action="./libro/nuevoLibro.php" method="GET">
        <!--Para todos los inputs si el valor se ha establecido se mantiene y si se envía vacío muestra un error-->
        <!--Para todos los inputs se controla si el usuario sólo dispone de permisos de lectura para desactivar los inputs-->
        <label for="id">Id:&nbsp;</label>
        <input type="text" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : "" ?>" <?php echo !$_SESSION['escritura'] && !$_SESSION['admin'] ? 'disabled' : '' ?>>
        <?php
        if (isset($_GET['submit']) && empty($_GET['id'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir un id!</span>";
        }
        //Aquí se muestra error si se ha introducido el id no numérico
        if (isset($_GET['errorid'])) {
            echo "<span style='color:red;'> <code><--</code> ¡" .  $_GET['errorid']  . "!</span>";
        }
        ?>
        <br>
        <label for="titulo">Titulo:&nbsp;</label>
        <input type="text" name="titulo" value="<?php echo isset($_GET['titulo']) ? $_GET['titulo'] : "" ?>" <?php echo !$_SESSION['escritura'] && !$_SESSION['admin'] ? 'disabled' : '' ?>>
        <?php
        if (isset($_GET['submit']) && empty($_GET['titulo'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir un título!</span>";
        }
        ?>
        <br>
        <label for="autor">Autor</label>
        <input type="text" name="autor" value="<?php echo isset($_GET['autor']) ? $_GET['autor'] : "" ?>" <?php echo !$_SESSION['escritura'] && !$_SESSION['admin'] ? 'disabled' : '' ?>>
        <?php
        if (isset($_GET['submit']) && empty($_GET['autor'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir un autor!</span>";
        }
        ?>
        <br>
        <label for="paginas">Páginas</label>
        <input type="text" name="paginas" value="<?php echo isset($_GET['paginas']) ? $_GET['paginas'] : "" ?>" <?php echo !$_SESSION['escritura'] && !$_SESSION['admin'] ? 'disabled' : '' ?>>
        <?php
        if (isset($_GET['submit']) && empty($_GET['paginas'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir el número de páginas!</span>";
        }
        //Aquí se muestra error si se ha introducido las páginas no numérico
        if (isset($_GET['errorpg'])) {
            echo "<span style='color:red;'> <code><--</code> ¡" . $_GET['errorpg'] . "!</span>";
        }
        ?>
        <br>
        <br>
        <!--Se controla si el usuario sólo dispone de permisos de lectura para desactivar el botón-->
        <input type="submit" value="Introducir libro" name="submit" <?php echo !$_SESSION['escritura'] && !$_SESSION['admin'] ? 'disabled' : '' ?> />
        <hr>
        <br>
    </form>
    <?php
    //muestra la tabla retornada por la función tablaLibros()
    require_once('./Libro/cargarLibros.php');
    echo tablaLibros();
    ?>
    <br>
    <?php
    //Aquí se muestran los errores de la bbdd(PK duplicada,...)
    if (isset($_GET['errorbd'])) {
        echo  $_GET['errorbd'];
    } ?>
</body>

</html>