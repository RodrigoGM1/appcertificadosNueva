<?php
    $pagina = "usuario";
    include "../../templates/header.php"; 
    include "../../controllers/bd.php"; 

    if($_SESSION['privilegios'] == 2 || $_SESSION['privilegios'] == 3 || $_SESSION['privilegios'] == 4 || $_SESSION['privilegios'] == 5){
        header("Location:../../controllers/cerrar.php");
    }

    $errores = [];
    $form = isset($_GET['form']) ? $_GET['form']: '';
    $accion = isset($_GET['accion']) ? $_GET['accion']: '';
    $id = isset($_GET['id']) ? $_GET['id']: '';

    $usuario = '';
    $clave = '';
    $privilegio = '';
    $carpeta = '';
    if($form == 1){ // Creacion de usuario
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            $privilegio = $_POST['privilegio'];
            $carpeta = $_POST['carpeta'];

            if(!$privilegio){
                $errores[] = "Añada los privilegios para el usuario";
            }

            if($privilegio == 1){ // Creacion de usuario Admin
                if(!$usuario){
                    $errores[] = "Añada un nombre para el usuario";
                }
                if(!$clave){
                    $errores[] = "Añada un contraseña para usuario";
                }
                if(empty($errores)){
                    $sentencias = $conexion->prepare("INSERT INTO tabla_usuarios(usuario, clave, idprivilegio) VALUES (:usuario, :clave, :idprivilegio)");
                    $sentencias->bindParam(":usuario", $usuario);
                    $sentencias->bindParam(":clave", $clave);
                    $sentencias->bindParam(":idprivilegio", $privilegio);
                    $sentencias->execute();
                    if($sentencias){
                        header("Location: index.php");
                    }   
                }
            }

            if($privilegio == 2){ // Creacion de usuario Normal
                echo "user";
                var_dump($_POST);
                if(!$usuario){
                    $errores[] = "Añade nombre del usuario";
                }
                if(!$clave){
                    $errores[] = "Añade una contraseña";
                }
                if(!$carpeta){
                    $errores[] = "Añade una carpeta";
                }
                if(empty($errores)){
                    $sentencias = $conexion->prepare("INSERT INTO tabla_usuarios(usuario, clave, idprivilegio) VALUES (:usuario, :clave, :idprivilegio); INSERT INTO tabla_usuario_carpeta(usuario_a_carpeta, idcarpeta) VALUES (:usuario_a_carpeta, :idcarpeta)");
                    $sentencias->bindParam(":usuario", $usuario);
                    $sentencias->bindParam(":clave", $clave);
                    $sentencias->bindParam(":idprivilegio", $privilegio);
                    $sentencias->bindParam(":usuario_a_carpeta", $usuario);
                    $sentencias->bindParam(":idcarpeta", $carpeta);
                    $sentencias->execute();
                    if($sentencias){
                        header("Location: index.php");
                    }
                }
            }
            if($privilegio == 3 || $privilegio == 4 || $privilegio == 5){
                $sentencias = $conexion->prepare("INSERT INTO tabla_usuarios(usuario, clave, idprivilegio) VALUES (:usuario, :clave, :idprivilegio)");
                    $sentencias->bindParam(":usuario", $usuario);
                    $sentencias->bindParam(":clave", $clave);
                    $sentencias->bindParam(":idprivilegio", $privilegio);
                    $sentencias->execute();
                    if($sentencias){
                        header("Location: index.php");
                    }  
            }
        }
    }

    if($form == 2){ // Actualizar usuario
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $nuevaClave = $_POST['nuevaClave'];
            if(!$nuevaClave){
                $errores[] = "Añada un contraseña para usuario";
            }
            if(empty($errores)){
                echo "No";
                $sentencias = $conexion->prepare("UPDATE tabla_usuarios SET clave = :clave WHERE id = :id");
                $sentencias->bindParam(":id", $id);
                $sentencias->bindParam(":clave", $nuevaClave);
                $sentencias->execute();
                if($sentencias){
                    header("Location: index.php");
                }
            }
        }
    }

    if($accion == 2){ // Eliminacion usuario
        $id = $_GET['id'];
        $sentencias = $conexion->prepare("SELECT * FROM tabla_usuarios WHERE id = :id");
        $sentencias->bindParam(":id", $id);
        $sentencias->execute();
        $resultado = $sentencias->fetch(PDO::FETCH_LAZY);
        $resultado = $resultado['usuario'];
        $sentencias = $conexion->prepare("DELETE FROM tabla_usuarios WHERE id = :id; DELETE FROM tabla_usuario_carpeta WHERE usuario_a_carpeta = :usuario_a_carpeta");
        $sentencias->bindParam(":id", $id);
        $sentencias->bindParam(":usuario_a_carpeta", $resultado);
        $sentencias->execute();
        if($sentencias){
            header("Location: index.php");
        }
    }

    $sentencias = $conexion->prepare("SELECT * FROM tabla_usuarios");
    $sentencias->execute();
    $usuarios = $sentencias->fetchAll(PDO::FETCH_ASSOC);
    
    $sentencias = $conexion->prepare("SELECT * FROM tabla_privilegios");
    $sentencias->execute();
    $tabPrivilegios = $sentencias->fetchAll(PDO::FETCH_ASSOC);

    $sentencias = $conexion->prepare("SELECT * FROM tabla_usuarios WHERE idprivilegio = 1");
    $sentencias->execute();
    $administradores = $sentencias->fetchAll(PDO::FETCH_ASSOC);

    $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas");
    $sentencias->execute();
    $carpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="main_inicio">
    <h1>Usuarios</h1>
    <div class="contemedotrUsuario">
        <h3>Administradores</h3>
        <a class="botonUsuarios">Nuevo Usuario</a>
    </div>

    <?php foreach($errores as $error){ ?>
        <div class="errorIn">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Usuario</td>
                <td>Privilegios</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($administradores as $administrador){  ?>
            <tr class="<?php if(($i % 2) == 0){ echo "verdeObscuro"; }else{ echo "verdeClaro"; } ?> verdeObscuro">
                <td><?php echo $administrador['usuario']; ?></td>
                <td>Administrador</td>
                <td>
                    <a href="?accion=1&id=<?php echo $administrador['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="?accion=2&id=<?php echo $administrador['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php $i++; }  ?>
        </tbody>
    </table>

    <div class="contemedotrUsuario">
        <h3>Usuario</h3>
    </div>
    <table class="tabla">
        <thead>
            <tr>
                <td>Usuario</td>
                <td>Privilegios</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($usuarios as $impUsuarios){ if($impUsuarios['idprivilegio'] == 2){ ?>
            <tr class="<?php if(($i % 2) == 0){ echo "verdeObscuro"; }else{ echo "verdeClaro"; } ?> verdeObscuro">
                <td><?php echo $impUsuarios['usuario']; ?></td>
                <td>Usuario</td>
                <td>
                    <a href="?accion=1&id=<?php echo $impUsuarios['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="?accion=2&id=<?php echo $impUsuarios['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php $i++; } } ?>
        </tbody>
    </table>

    <div id="ventanaModal" class="modalDialogo">
        <div class="contenedorModal">
            <a href="#" title="Cerrar" class="cerrar">x</a>
            <h2>Datos del usuario</h2>
            <form action="?form=1" class="formUsuarios" method="post">
                <input class="inputUsuario" type="text" placeholder="Usuario" name="usuario">
                <input class="inputUsuario" type="password" placeholder="Contraseña" name="clave">
                <div class="contSelectUsuario">
                    <label>Privilegios</label>
                    <select class="inputUsuario" name="privilegio">
                        <option value="">-- Privilegios --</option>
                        <?php foreach($tabPrivilegios as $tabPrivilegio){ ?>
                        <option value="<?php echo $tabPrivilegio['id']; ?>"><?php echo $tabPrivilegio['nombreprivilegio']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br><br><br>
                <div class="contSelectUsuario">
                    <p>En caso de usuario asignar una carpeta</p>
                    <label>Carpetas</label>
                    <select class="inputUsuario" name="carpeta">
                        <option value="">-- Carpetas --</option>
                        <?php foreach($carpetas as $carpeta) { ?>
                            <option value="<?php echo $carpeta['id']; ?>"><?php echo $carpeta['nombre_carpeta']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button class="botonModal">Guardar</button>
            </form>
        </div>
    </div>
    <br>
    <?php
        if($accion == 1){
            $sentencias = $conexion->prepare("SELECT * FROM tabla_usuarios WHERE id = :id");
            $sentencias->bindParam(":id", $id);
            $sentencias->execute();
            $resUsuario = $sentencias->fetch(PDO::FETCH_LAZY); 
    ?>
        <div class="border">
            <div>
                <h2>Datos del usuario:</h2>
                <form action="?form=2" class="formUsuarios" method="post">
                    <input class="inputUsuario" type="text" placeholder="Usuario" readonly="readonly" name="nuevoUsuario" value="<?php echo $resUsuario['usuario']; ?>">
                    <input class="inputUsuario" type="password" placeholder="Contraseña" name="nuevaClave" value="<?php echo $resUsuario['clave']; ?>">
                    <button class="botonModal">Guardar</button>
                </form>
            </div>
        </div>
    <?php } ?>
</main>
<?php
    include "../../templates/footer.php"; 
?>