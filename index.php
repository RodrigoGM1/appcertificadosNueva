<?php
    include "controllers/bd.php";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        session_start();
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];

        $sentencias = $conexion->prepare("SELECT * FROM tabla_usuarios WHERE usuario = :usuario AND clave = :clave");
        $sentencias->bindParam(":usuario", $usuario);
        $sentencias->bindParam(":clave", $clave);
        $sentencias->execute();
        $registro = $sentencias->fetch(PDO::FETCH_LAZY);

        if(is_object($registro)){
            if($registro['idprivilegio'] == 1){
                $_SESSION['user'] = $registro['usuario'];
                $_SESSION['logueado'] = true;
                $_SESSION['privilegios'] = $registro['idprivilegio'];
                header('Location:view/inicio.php');

            }else if($registro['idprivilegio'] == 2){
                $_SESSION['user'] = $registro['usuario'];
                $_SESSION['logueado'] = true;
                $_SESSION['privilegios'] = $registro['idprivilegio'];

                $sentencias = $conexion->prepare("SELECT * FROM tabla_usuario_carpeta WHERE usuario_a_carpeta = :usuario_a_carpeta");
                $sentencias->bindParam(":usuario_a_carpeta", $_SESSION['user']);
                $sentencias->execute();
                $resultado = $sentencias->fetch(PDO::FETCH_LAZY);
                $idcarpeta = $resultado['idcarpeta'];
                $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
                $sentencias->bindParam(":id", $idcarpeta);
                $sentencias->execute();
                $carpeta = $sentencias->fetch(PDO::FETCH_LAZY);
    
                header("Location:view/verCertificado.php?carpeta=".$carpeta['id']."&nombreCarpeta=".$carpeta['nombre_carpeta']);
            }

            else if($registro['idprivilegio'] == 3){
                $_SESSION['user'] = $registro['usuario'];
                $_SESSION['logueado'] = true;
                $_SESSION['privilegios'] = $registro['idprivilegio'];

                $sentencias = $conexion->prepare("SELECT * FROM tabla_usuario_carpeta WHERE usuario_a_carpeta = :usuario_a_carpeta");
                $sentencias->bindParam(":usuario_a_carpeta", $_SESSION['user']);
                $sentencias->execute();
                $resultado = $sentencias->fetch(PDO::FETCH_LAZY);
                $idcarpeta = $resultado['idcarpeta'];
                $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
                $sentencias->bindParam(":id", $idcarpeta);
                $sentencias->execute();
                $carpeta = $sentencias->fetch(PDO::FETCH_LAZY);
    
                header("Location:view/administracion/administracion.php");
            }

            else if($registro['idprivilegio'] == 4){
                $_SESSION['user'] = $registro['usuario'];
                $_SESSION['logueado'] = true;
                $_SESSION['privilegios'] = $registro['idprivilegio'];

                $sentencias = $conexion->prepare("SELECT * FROM tabla_usuario_carpeta WHERE usuario_a_carpeta = :usuario_a_carpeta");
                $sentencias->bindParam(":usuario_a_carpeta", $_SESSION['user']);
                $sentencias->execute();
                $resultado = $sentencias->fetch(PDO::FETCH_LAZY);
                $idcarpeta = $resultado['idcarpeta'];
                $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
                $sentencias->bindParam(":id", $idcarpeta);
                $sentencias->execute();
                $carpeta = $sentencias->fetch(PDO::FETCH_LAZY);
    
                header("Location:view/administracion/choferes.php");
            }

            else if($registro['idprivilegio'] == 5){
                $_SESSION['user'] = $registro['usuario'];
                $_SESSION['logueado'] = true;
                $_SESSION['privilegios'] = $registro['idprivilegio'];

                $sentencias = $conexion->prepare("SELECT * FROM tabla_usuario_carpeta WHERE usuario_a_carpeta = :usuario_a_carpeta");
                $sentencias->bindParam(":usuario_a_carpeta", $_SESSION['user']);
                $sentencias->execute();
                $resultado = $sentencias->fetch(PDO::FETCH_LAZY);
                $idcarpeta = $resultado['idcarpeta'];
                $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
                $sentencias->bindParam(":id", $idcarpeta);
                $sentencias->execute();
                $carpeta = $sentencias->fetch(PDO::FETCH_LAZY);
    
                header("Location:view/administracion/aprobechamiento.php");
            }

        }else{
            $error = "El usuario o contraseña son incorrectas";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Certificados</title>

    <link rel="shortcut icon" href="img/logo.gif">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&family=Open+Sans:wght@300;500;700&family=Roboto&display=swap" rel="stylesheet">

</head>
<body class="body_login">
    <main class="main_login">
            <?php if(!empty($error)){ ?>
                <div class="errorIn"><p><?php echo $error; ?></p></div><br><br>
            <?php } ?>

            <img class="main_login_img" src="img/logo.gif" alt="">

            <form class="main_login_form" action="index.php" method="post">
                <h2 class="main_login_form_h2">Ingrese sus credenciales</h2>

                <div class="main_login_form_div">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Ingrese usuario">
                </div>
                <div class="main_login_form_div">
                    <label for="clave">Ingrece su contraseña</label>
                    <input type="password" id="clave" name="clave" placeholder="Ingrese su contraseña">
                </div>
                <div class="main_contenedor_boton">
                    <input type="submit" value="Ingresar">
                </div>
            </form>
    </main>
</body>
</html>