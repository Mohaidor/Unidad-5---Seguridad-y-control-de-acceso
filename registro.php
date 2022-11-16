<?php
session_start();
//Si se pulsa registrarme y son correctos los valores...
if (isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['password']) && !empty($_POST['password2']) && $_POST['password'] == $_POST['password2']) {

    //Obtención de datos
    $usuario = $_POST['usuario'];
    //La contraseña se guarda cifrada 
    $contrasenaHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        //conexión a bd
        $conexion = new mysqli('localhost', 'root', '', 'bdsesiones');
        //insert del user
        $insert = "INSERT INTO usuarios(usuario, contrasena, nombre, lectura, escritura, admin) VALUES ('$usuario', '$contrasenaHash', '', 0, 0, 0)";
        $conexion->query($insert);
        //Redirecciono a Login
        header("Location:login.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        //Si hay error muestro mensaje y error
        die("Se ha producido un Error: <br><code style=\"color: red;\">" . $e->getMessage() .  "</code>");
    }
    //Si no muestro el form
} else {
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
            <h1>Registrarme</h1>
            <!--Para cada input se mantiene el valor si se ha introducido y si está vacío se muestra error-->
            <form action="" method="POST">
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
                <label for="password2">Repite password</label>
                <input type="password" name="password2" value="<?php echo isset($_POST['password2']) ? $_POST['password2'] : '' ?>">
                <?php
                if (isset($_POST['submit']) && empty($_POST['password2'])) {
                    echo "<span style='color:red;'>&nbsp;¡Debe introducir un password!</span>";
                    //Aquí se controla que los password sean iguales
                } else if (isset($_POST['submit']) && $_POST['password'] != $_POST['password2']) {
                    echo "<span style='color:red;'> *Los password no coinciden</span>";
                }
                ?>
                <br>
                <input type="submit" name="submit" value="Registrarme">
            </form>
        </section>
    </body>

    </html>
<?php } ?>