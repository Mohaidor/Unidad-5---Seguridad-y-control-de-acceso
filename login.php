<?php
//Comienzo de session
session_start();

//Si se ha pulsado submit y no están vacíos el user y la contraseña...
if (isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['password'])) {
    //Guardo valores recibidos en variables
    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];
    try {
        //conecto a bbdd
        $conexion = new mysqli('localhost', 'root', '', 'bdsesiones');
        //Busco el usuario
        $select = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $consulta = $conexion->query($select);
        $resultado = $consulta->fetch_assoc();

        //Si el usuario existe...
        if ($resultado != null) {
            //verifico que el password dado es correcto con lo obtenido de la bbdd
            if (password_verify($pass, $resultado['contrasena'])) {
                // Guardamos los datos en la sesión
                $_SESSION['usuario'] = $resultado['usuario'];
                $_SESSION['lectura'] = $resultado['lectura'];
                $_SESSION['escritura'] = $resultado['escritura'];
                $_SESSION['admin'] = $resultado['admin'];
                //Una vez log correcto redirijo a página principal
                header("Location:pagina.php");
            } else {
                //En este else entrará si la verificación de password es false;Creo variable de session con error 
                $error = "<span style='color:red;'>&nbsp;La contraseña no es correcta.Prueba de nuevo</span><br>";
            }
        } else {
            //En este else entrará si el usuario no ha sido encontrado en la bbdd;Creo variable de session con error 
            $error = "<span style='color:red;'>&nbsp;Usuario no encontrado.Prueba de nuevo</span><br>";
        }
    } catch (mysqli_sql_exception $e) {
        //Si hay error muestro mensaje y error
        die("Se ha producido un Error: <br><code style=\"color: red;\">" . $e->getMessage() .  "</code>");
    }
} 

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>unidad05</title>
    </head>

    <body>
        <section>
            <h1>LOGIN</h1>
            <?php
            //Control de que existe usuario; si es así redirijo a la página principal
            if (isset($_SESSION['usuario'])) {
                header('location:./pagina.php');
            } else {
            ?>
                <form action="" method="POST">
                    <?php
                    //Funcionamiento de la primera linea del login. Si hay error muestro error; Si no muestro mensaje en azul
                    if (!empty($error)) {
                        echo $error;
                    } else {
                        echo "<span style='color:blue;'>&nbsp;Introduce tus credenciales para entrar</span><br>";
                    } ?>
                    <!--Para cada input se mantiene el valor si se ha introducido y si está vacío se muestra error-->
                    <label for="usuario">Usuario</label>
                    <input type="text" name="usuario" value="<?php echo isset($_POST['usuario']) ? $_POST['usuario'] : '' ?>">
                    <?php
                    if (isset($_POST['submit']) && empty($_POST['usuario'])) {
                        echo "<span style='color:red;'>&nbsp;¡Debe introducir un nombre de usuario!</span>";
                    }
                    ?>
                    <br>
                    <label for="password">Password</label>
                    <input type="password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>">
                    <?php
                    if (isset($_POST['submit']) && empty($_POST['password'])) {
                        echo "<span style='color:red;'>&nbsp;¡Debe introducir un password!</span>";
                    }
                    ?>
                    <br>
                    <input type="submit" name="submit" value="Entrar">
                    <br>
                    <p>¿Aún no te has registrado?<a href="./registro.php">¡Regístrate!</a></p>
                </form>
            <?php } ?>
        </section>
    </body>

    </html>