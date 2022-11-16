<?php

//Si se ha pulsado submit, las paginas son un número y todas datos existen...
if (
    isset($_GET['submit']) && is_numeric($_GET['paginas']) &&
    !empty($_GET['id']) && !empty($_GET['titulo']) && !empty($_GET['autor']) && !empty($_GET['paginas'])
) {

    //Obtención de valores
    $id = $_GET['id'];
    $titulo = $_GET['titulo'];
    $autor = $_GET['autor'];
    $paginas = $_GET['paginas'];

    //Obtiene la conexión
    require_once('../libro/conexion.php');
    $conexion = getConexion();
    //Ejecuta instrucción
    $sql = "UPDATE libros SET titulo='$titulo', autor='$autor', paginas=$paginas WHERE id=$id";
    $resultado = $conexion->prepare($sql);
    $resultado->execute();
    //Redirijo a página principal
    header('location:../pagina.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4 - PDO</title>
</head>

<body>
    <h1>Modificar libro</h1>
    <form action="" method="get">
        <!--Se mantienen los values si se han introducido, errores si no-->
        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
        <?php
        if (isset($_GET['submit']) && empty($_GET['id'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir el id!</span>";
        }
        ?>
        <br>
        <label for="titulo">Título</label>
        <input type="text" name="titulo" value="<?php echo isset($_GET['titulo']) ? $_GET['titulo'] : "" ?>">
        <?php
        if (isset($_GET['submit']) && empty($_GET['titulo'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir un título!</span>";
        }
        ?>
        <br>
        <label for="autor">Autor</label>
        <input type="text" name="autor" value="<?php echo isset($_GET['autor']) ? $_GET['autor'] : "" ?>">
        <?php
        if (isset($_GET['submit']) && empty($_GET['autor'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir un autor!</span>";
        }
        ?>
        <br>
        <label for="paginas">Páginas</label>
        <input type="text" name="paginas" value="<?php echo isset($_GET['paginas']) ? $_GET['paginas'] : "" ?>">
        <?php
        if (isset($_GET['submit']) && empty($_GET['paginas'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Debes introducir el número de páginas!</span>";
        }
        //Se controla que sea un número, si no error
        if (isset($_GET['submit']) && !empty($_GET['paginas']) && !is_numeric($_GET['paginas'])) {
            echo "<span style='color:red;'> <code><--</code> ¡Las páginas debe ser un número!</span>";
        } ?>
        <br>
        <br>
        <input type="submit" name="submit" value="Modificar Libro">

    </form>
</body>

</html>