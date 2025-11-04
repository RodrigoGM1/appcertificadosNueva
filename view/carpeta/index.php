<?php
    $pagina = "carpeta";
    include "../../templates/header.php"; 
    include "../../controllers/bd.php";

    if($_SESSION['privilegios'] == 2){
        header("Location:../../controllers/cerrar.php");
    }

    $errores = [];
    $formulario = isset($_GET['form']) ? $_GET['form'] : '';
    $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if($formulario == 1){ // Registro de una nueva carpeta
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $carpeta = $_POST['carpeta'];
            if(!$carpeta){
                $errores[] = "Añada un nombre a la carpeta";
            }
            $sentencias = $conexion->prepare("SELECT nombre_carpeta FROM tabla_carpetas");
            $sentencias->execute();
            $registroCarpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);
            foreach($registroCarpetas as $evaluar){
                if($carpeta == $evaluar['nombre_carpeta']){
                    $errores[] = "La carpeta ya existe";
                }
            }

            if(empty($errores)){
                $sentencias = $conexion->prepare("INSERT INTO tabla_carpetas(nombre_carpeta) VALUES (:nombre_carpeta)");
                $sentencias->bindParam(":nombre_carpeta", $carpeta);
                $sentencias->execute();
                if($sentencias){
                    $direcionC = "../CertificadosDoc/".$carpeta;
                    $direcionE = "../EvidenciasDoc/".$carpeta;
                    $direcionR = "../ReportesDoc/".$carpeta;

                    $direcionM = "../EvidenciasSub/Manifiestos/".$carpeta;
                    $direcionN = "../EvidenciasSub/Notas/".$carpeta;
                    mkdir($direcionC, 0777, true);
                    mkdir($direcionE, 0777, true);
                    mkdir($direcionR, 0777, true);

                    mkdir($direcionM, 0777, true);
                    mkdir($direcionN, 0777, true);

                    header("Location: index.php");
                }
            }
        }
    }

    if($formulario == 2){ // Actualizar la carpeta
        $nuevaCarpeta = '';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $nuevaCarpeta = $_POST['nuevaCarpeta'];
            $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas");
            $sentencias->execute();
            $resultados = $sentencias->fetchAll(PDO::FETCH_ASSOC);
            if(!$nuevaCarpeta){
                $errores[] = "Añade un nuevo nombre a la carpeta";
            }
            foreach($resultados as $resultado){
                if($nuevaCarpeta == $resultado['nombre_carpeta']){
                    $errores[] = "La carpeta ya existe";
                }
            }
            if(empty($errores)){
                $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
                $sentencias->bindParam(":id", $id);
                $sentencias->execute();
                $rec = $sentencias->fetch(PDO::FETCH_LAZY);
                $viejaCarpeta = $rec['nombre_carpeta'];
                $direcionCAnt = "../CertificadosDoc/".$viejaCarpeta;
                $direcionEAnt = "../EvidenciasDoc/".$viejaCarpeta;
                $direcionRAnt = "../ReportesDoc/".$viejaCarpeta;
                $direcionMAnt = "../EvidenciasSub/Manifiestos/".$viejaCarpeta;
                $direcionNAnt = "../EvidenciasSub/Notas/".$viejaCarpeta;
                $direcionC = "../CertificadosDoc/".$nuevaCarpeta;
                $direcionE = "../EvidenciasDoc/".$nuevaCarpeta;
                $direcionR = "../ReportesDoc/".$nuevaCarpeta;
                $direcionM = "../EvidenciasSub/Manifiestos/".$nuevaCarpeta;
                $direcionN = "../EvidenciasSub/Notas/".$nuevaCarpeta;
                rename($direcionCAnt, $direcionC);
                rename($direcionEAnt, $direcionE);
                rename($direcionRAnt, $direcionR);
                rename($direcionMAnt, $direcionM);
                rename($direcionNAnt, $direcionN);
                $sentencias = $conexion->prepare("UPDATE tabla_carpetas SET nombre_carpeta = :nombre_carpeta  WHERE id = :id");
                $sentencias->bindParam(":id", $id);
                $sentencias->bindParam(":nombre_carpeta", $nuevaCarpeta);
                $sentencias->execute();
                if($sentencias){ 
                    header("Location: index.php");
                }
            }
        }
    }

    if($accion == 2){ // Eliminacion de la carpeta
        $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
        $sentencias->bindParam(":id", $id);
        $sentencias->execute();
        $borrar = $sentencias->fetch(PDO::FETCH_LAZY);
        $borrar = $borrar['nombre_carpeta'];
        $direcionC = "../CertificadosDoc/".$borrar;
        $direcionE = "../EvidenciasDoc/".$borrar;
        $direcionR = "../ReportesDoc/".$borrar;
        $direcionM = "../EvidenciasSub/Manifiestos/".$borrar;
        $direcionN = "../EvidenciasSub/Notas/".$borrar;
        rmdir($direcionC);
        rmdir($direcionE);
        rmdir($direcionR);
        rmdir($direcionM);
        rmdir($direcionN);
        if($sentencias){
            $sentencias = $conexion->prepare("DELETE FROM tabla_carpetas WHERE id = :id");
            $sentencias->bindParam(":id", $id);
            $sentencias->execute();
            header("Location: index.php");
        }
    }

    // Consultar todos los datos de la tabla tabla_carpetas
    $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas");
    $sentencias->execute();
    $consultaCarpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);

    // Instruciones para el paginador
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 10;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;
    $sentencias = $conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tabla_carpetas LIMIT $inicio,$regpagina");
    $sentencias->execute();
    $resCarpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);
    $totalregistro = $conexion->query("SELECT FOUND_ROWS() AS total");
    $totalregistro = $totalregistro->fetch()['total'];
    $Numeropaginas = ceil($totalregistro / $regpagina);
?>
<main class="main_inicio">

    <h1>Gestion carpetas</h1>
    <br>

    <?php foreach($errores as $error){ ?>
        <div class="errorIn">
            <?php echo $error; ?>
        </div>
    <?php } ?>


    <?php 
        if($accion == 1){ 
            $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas WHERE id = :id");
            $sentencias->bindParam(":id", $id);
            $sentencias->execute();
            $resultado = $sentencias->fetch(PDO::FETCH_LAZY);
    ?>
        <h2>Cambiar nombre a la carpeta</h2>
        <form class="formActualizar" action="?form=2&id=<?php echo $resultado['id']; ?>" method="POST">
            <input type="text" name="nuevaCarpeta" placeholder="<?php echo $resultado['nombre_carpeta']; ?>">
            <button class="botonCarpetasG">Guardar</button>
        </form>
    <?php } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Nombre Carpeta</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr class="formTabla">    
                <form action="?form=1" method="POST">
                    <td><input type="text" name="carpeta"></td>
                    <td class="relleno"><button class="botonCarpetasG">Guardar</button></td>
                </form>
            </tr>
            <?php $i=0; foreach($consultaCarpetas as $consultaCarpeta){ ?>
            <tr class="<?php if(($i % 2) == 0){ echo "verdeObscuro"; }else{ echo "verdeClaro"; } ?> verdeObscuro">
                <td><?php echo $consultaCarpeta['nombre_carpeta']; ?></td>
                <td class="centrarIconos">
                    <a href="index.php?accion=1&id=<?php echo $consultaCarpeta['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="index.php?accion=2&id=<?php echo $consultaCarpeta['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
    <nav class="paginador">
        <ul>
            <?php if($pagina == 1){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-backward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="index.php?pagina=<?php echo $pagina-1; ?>"><i class="fa-solid fa-backward"></i></a></li>
            <?php } ?>
            <?php for($i=1; $i<=$Numeropaginas; $i++){ ?>
                <?php if($pagina == $i){ ?>
                    <li class="select"><a class="selectA" href="index.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a class="selectV" href="index.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <?php if($pagina == $Numeropaginas){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-forward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="index.php?pagina=<?php echo $pagina+1; ?>"><i class="fa-solid fa-forward"></i></a></li>
            <?php } ?>
        </ul>
    </nav>
</main>
<?php
    include "../../templates/footer.php"; 
?>