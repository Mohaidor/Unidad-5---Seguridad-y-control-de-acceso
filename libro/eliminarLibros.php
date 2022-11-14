<?php
//Elimina un libro en base del id
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    //Elimina un libro mediante el id
    require_once('conexion.php');
    $conexion = getConexion();
    //Ejecuta la instrucción
    $sql = "DELETE FROM libros WHERE id=$id";
    $resultado = $conexion->prepare($sql);
    $resultado->execute();
    //Redirijo a la página principal que al recargar ya no mostrará el libro eliminado
    header('location:../pagina.php');
    exit();
}



