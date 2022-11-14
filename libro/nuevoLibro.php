<?php

$id = $_GET['id'];
$titulo = $_GET['titulo'];
$autor = $_GET['autor'];
$paginas = $_GET['paginas'];



//Aquí se genera un string con una url dinámica en función de las variables y los errores
//La introducción de letras en los campos de números no genera una excepción por lo que se controla mediante is_numeric()
$url = "../pagina.php";
if (empty($_GET['id'])) {
    $url .= '?id=';
} else {
    $url .= "?id=$id";
}

if (!empty($_GET['id']) && !is_numeric($id)) {
    $url .= '&errorid=' . urlencode('El id debe ser un número');
}


if (empty($_GET['titulo'])) {
    $url .= '&titulo=';
} else {
    $url .= "&titulo=$titulo";
}
if (empty($_GET['autor'])) {
    $url .= '&autor=';
} else {
    $url .= "&autor=$autor";
}
if (empty($_GET['paginas'])) {
    $url .= '&paginas=';
} else {
    $url .= "&paginas=$paginas";
}

if (!empty($_GET['paginas']) && !is_numeric($paginas)) {
    $url .= '&errorpg=' . urlencode('Las páginas debe ser un número');
}
$url .= '&submit=Introducir+libro';


//Si esta la url correcta y tengo los valores INcorrectos
if (!empty($url)  && !is_numeric($id) || empty($_GET['titulo']) || empty($_GET['autor']) || !is_numeric($paginas)) {
    //retorna a la página principal con el estado del valor actual de las variables por GET
    header("location:../Vista/$url");
    exit();
} else {
    //Aquí se llega si esta todo correcto y se realiza el INSERT mediante el modelo
    require_once('conexion.php');
    $pdoObject = getConexion();
    //Prepara el INSERT
    $sql = "INSERT INTO libros (id, titulo, autor, paginas) VALUES (:id, :titulo, :autor, :paginas)";
    $sentencia = $pdoObject->prepare($sql);
    //Bindea los parámetros
    $sentencia->bindParam(':id', $id);
    $sentencia->bindParam(':titulo', $titulo);
    $sentencia->bindParam(':autor', $autor);
    $sentencia->bindParam(':paginas', $paginas);
    try {
        //Prueba a realizar el insert y si es correcto retornará 1(1 affected rows->1 libro insertado)
        $sentencia->execute();
        header('../pagina.php');
        exit();
    } catch (Exception $e) {
        //Si hay algún problema retorno un string con un error
        header("location:../pagina.php?errorbd=<span style='color:red;'>Error:¡Clave primaria duplicada!</span>");
        exit();
    }

}
