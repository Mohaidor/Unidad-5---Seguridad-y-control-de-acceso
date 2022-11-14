<?php

    //Retorna un array con todos los libros
    function cargarLibros()
    {
        //Obtiene la conexión
        require_once('conexion.php');
        $conexion = getConexion();
        //Prepara el SELECT
        $sql = "SELECT * FROM libros";
        $resultado = $conexion->prepare($sql);
        //resultado obtiene el objeto PDO con los datos
        $resultado->execute();
        $rows = null;
        //Recorrer el PDO y guardar datos en array
        while ($fila = $resultado->fetch()) {
            $rows[] = $fila;
        }
        return $rows;
    }

    //Retorna un string con los libros en formato tabla
    function tablaLibros()
    {
        //Guardo en variable los valores de los permisos de escritura y admin. Serán utilizados como booleanos para colocar los botones de eliminar y modificar libro
        $escritura = $_SESSION['escritura'];
        $admin = $_SESSION['admin'];

        $rows = cargarLibros(); //Aquí se obtiene el array de cargalibros() para hacer la tabla
        if (!$rows)
            $string = "No hay libros.";
        else {
            $string = "<table border>";
            $string .= "<tr><th>Id</th><th>titulo</th><th>Autor</th><th>Páginas</th></tr>";
            foreach ($rows as $row) {
                //eliminar pasa por GET a eliminarLibros.php el id del libro a eliminar
                $eliminar = "</td><td><a href=\"./libro/eliminarLibros.php?id=" . $row['id'] . "\">eliminar</a>";
                //modificar pasa por GET a modificarLibro.php con los valores del libro a modificar
                $modificar = "</td><td><a href=\"./libro/modificarLibro.php?id=" . $row['id'] . "&titulo=" . $row['titulo'] . "&autor=" . $row['autor'] . "&paginas=" . $row['paginas'] . "\">modificar</a>" . "</td>";

                $string .= "<tr><td>" . $row['id'] . "</td><td>" . $row['titulo'] . "</td><td>" . $row['autor'] . "</td><td>" . $row['paginas'];
                //Si los permisos de escritura y admin son 1(true) se añade el link de eliminar modificar 
                $string .=  $escritura && $admin ? $eliminar . $modificar : '';
            }
            $string .= "</table>";
        }
        return $string;
    }

?>