<?php
    $pagina = "reporte";
    include "../../templates/header.php";
    include "../../controllers/bd.php";

    $carpeta = $_GET['carpeta'];    
    $nombreCarpeta = $_GET['nombreCarpeta'];
    $form = isset($_GET['form']) ? $_GET['form']: '';
    $accion = isset($_GET['accion']) ? $_GET['accion']: '';
    $id = isset($_GET['id']) ? $_GET['id']: '';
    $errores = [];

    if($form == 1){ // Crear reporte
        $fecha = '';
        $reporte = '';
        $cantidad = '';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $fecha = $_POST['fecha'];
            $reporte = $_FILES['reporte']['name'];
            $cantidad = $_POST['cantidad'];
            $direcionE = "../ReportesDoc/".$nombreCarpeta."/".$reporte;
            
            if(!$fecha){
                $errores[] = "Añada la fecha";
            }
            if(!$cantidad){
                $errores[] = "Añada la cantidad CO2";
            }
            if(!$reporte){
                $errores[] = "Añada el reporte mensual";
            }
            if(!move_uploaded_file($_FILES['reporte']['tmp_name'], $direcionE)){
                $errores[] = "No se pudo subir el archivo";
            }

            if(empty($errores)){
                $sentencias = $conexion->prepare("INSERT INTO tabla_reportes(fecha, reporte, cantidad, id_carpeta) VALUES (:fecha, :reporte, :cantidad, :id_carpeta)");
                $sentencias->bindParam(":fecha", $fecha);
                $sentencias->bindParam(":reporte", $reporte);
                $sentencias->bindParam(":cantidad", $cantidad);
                $sentencias->bindParam(":id_carpeta", $carpeta);
                $sentencias->execute();
                if($sentencias){
                    header("Location:subReporte.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                }     
            }
        }
    }

    if($form == 2){ // actualizar
        $fechaNuevo = '';
        $cantidadNuevo = '';
        $reporteNuevo = '';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $fechaNuevo = $_POST['fechaNuevo'];
            $cantidadNuevo = $_POST['cantidadNuevo'];
            $reporteNuevo = $_FILES['reporteNuevo']['name'];
            $direcionE = "../ReportesDoc/".$nombreCarpeta."/".$reporteNuevo;


            if(empty($reporteNuevo)){
                $sentencias = $conexion->prepare("UPDATE tabla_reportes SET fecha = :fecha, cantidad = :cantidad WHERE id = :id");
                $sentencias->bindParam(":fecha", $fechaNuevo);
                $sentencias->bindParam(":cantidad", $cantidadNuevo);
                $sentencias->bindParam(":id", $id);
                $sentencias->execute();
                if($sentencias){
                    header("Location:reporte.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                }   
            }
            else{
                $sentencias = $conexion->prepare("SELECT * FROM tabla_reportes WHERE id = :id");
                $sentencias->bindParam(":id", $id);
                $sentencias->execute();
                $selecionarRep = $sentencias->fetch(PDO::FETCH_LAZY);
                $direcionEAn = "../ReportesDoc/".$nombreCarpeta."/".$selecionarRep['reporte'];

                if(file_exists($direcionEAn)){
                    unlink($direcionEAn);
                    if(!move_uploaded_file($_FILES['reporteNuevo']['tmp_name'], $direcionE)){
                        $errores[] = "No se pudo subir el archivo";
                    }
                }else{
                    $errores[] = "No se pudo borrar el archivo";
                }

                if(empty($errores)){

                    $sentencias = $conexion->prepare("UPDATE tabla_reportes SET fecha = :fecha, reporte = :reporte, cantidad = :cantidad WHERE id = :id");
                    $sentencias->bindParam(":fecha", $fechaNuevo);
                    $sentencias->bindParam(":reporte", $reporteNuevo);
                    $sentencias->bindParam(":cantidad", $cantidadNuevo);
                    $sentencias->bindParam(":id", $id);
                    $sentencias->execute();
                    if($sentencias){
                        header("Location:subReporte.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                    } 
                }

            }
        }
    }

    if($accion == 2){ // Eliminar
        $sentencias = $conexion->prepare("SELECT * FROM tabla_reportes WHERE id = :id");
        $sentencias->bindParam(":id", $id);
        $sentencias->execute();
        $selecionarEvi = $sentencias->fetch(PDO::FETCH_LAZY);
        $direcionEAn = "../ReportesDoc/".$nombreCarpeta."/".$selecionarEvi['reporte'];

        if(file_exists($direcionEAn)){
            unlink($direcionEAn);
        }else{
            $errores[] = "No se pudo borrar el archivo";
        }

        if(empty($errores)){
            $sentencias = $conexion->prepare("DELETE FROM tabla_reportes WHERE id = :id");
            $sentencias->bindParam(":id", $id);
            $sentencias->execute();
            header("Location:subReporte.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
        }
    }

    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 6;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

    $sentencias = $conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tabla_reportes WHERE id_carpeta = :id_carpeta ORDER BY fecha DESC LIMIT $inicio,$regpagina");
    $sentencias->bindParam(":id_carpeta", $carpeta);
    $sentencias->execute();
    $resultado = $sentencias->fetchAll(PDO::FETCH_ASSOC);

    $totalregistro = $conexion->query("SELECT FOUND_ROWS() AS total");
    $totalregistro = $totalregistro->fetch()['total'];
    $Numeropaginas = ceil($totalregistro / $regpagina);
?>
<main class="main_inicio">
    <h1>Reportes <?php echo $nombreCarpeta; ?></h1>
    <br>

    <?php foreach($errores as $error){ ?>
        <div class="errorIn">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <?php if($accion == 1){
        $sentencias = $conexion->prepare("SELECT * FROM tabla_reportes WHERE id = :id");
        $sentencias->bindParam(":id", $id);
        $sentencias->execute();
        $reporteAnt = $sentencias->fetch(PDO::FETCH_LAZY);
    ?>
        <form class="formActualizar" action="?carpeta=<?php echo $carpeta;?>&form=2&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <input class="evidenciaFecha" type="date" name="fechaNuevo" value="<?php echo $reporteAnt['fecha']; ?>">
            <input class="evidenciaArchivos" type="number" step="0.01" name="cantidadNuevo" value="<?php echo $reporteAnt['cantidad']; ?>">
            <input class="evidenciaArchivos" type="file" name="reporteNuevo">

            <button class="botonCarpetasA">Guardar</button>
        </form>
    <?php } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Fecha</td>
                <td>CO2 Equivalente</td>
                <td>Evidencia</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php if($_SESSION['privilegios'] == 1){ ?>
            <tr class="formTabla">    
                <form action="?carpeta=<?php echo $carpeta;?>&form=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>" method="post" enctype="multipart/form-data">
                    <td><input type="date" name="fecha"></td>
                    <td><input type="number" step="0.01" name="cantidad"></td>
                    <td><input type="file" name="reporte"></td>
                    <td class="relleno"><button class="botonCarpetasG">Guardar</button></td>
                </form>
            </tr>
            <?php } ?>
            <?php $i=0; foreach($resultado as $resultadoTab) { ?>
            <tr class="<?php if(($i % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">
                <td><?php echo $resultadoTab['fecha']; ?></td>
                <td><?php echo $resultadoTab['cantidad']; ?></td>
                <td><?php echo $resultadoTab['reporte']; ?></td>
                <td class="centrarIconos">
                    <a target="_black" href="../ReportesDoc/<?php echo $nombreCarpeta ?>/<?php echo $resultadoTab['reporte']; ?>"><i class="fa-regular fa-eye estiloOjo"></i></a>
                    <?php if($_SESSION['privilegios'] == 1){ ?>
                    <a href="?carpeta=<?php echo $carpeta;?>&accion=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoTab['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="?carpeta=<?php echo $carpeta;?>&accion=2&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoTab['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                    <?php } ?>
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
                <li><a class="selectV" href="subReporte.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $pagina-1; ?>"><i class="fa-solid fa-backward"></i></a></li>
            <?php } ?>
            <?php for($i=1; $i<=$Numeropaginas; $i++){ ?>
                <?php if($pagina == $i){ ?>
                    <li class="select"><a class="selectA" href="subReporte.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a class="selectV" href="subReporte.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <?php if($pagina == $Numeropaginas){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-forward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="subReporte.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $pagina+1; ?>"><i class="fa-solid fa-forward"></i></a></li>
            <?php } ?>
        </ul>
    </nav>
    
    <br>
    <div class="contenedorReporte">
        <div class="contenedorGrafica">
            <h2>Reduccion de CO<small>2</small> Equivalente</h2>
            <canvas width="400" height="100" id="grafica"></canvas>
        </div>
    </div>
</main>


<?php
    include "../../templates/footer.php"; 
    $sentencias = $conexion->prepare("SELECT fecha, cantidad FROM tabla_reportes WHERE id_carpeta = :id_carpeta ORDER BY fecha DESC LIMIT 12");
    $sentencias->bindParam(":id_carpeta", $carpeta);
    $sentencias->execute();
    $graficas = $sentencias->fetchAll(PDO::FETCH_ASSOC);
?>

<script>    
    const ctx = document.getElementById('grafica');

    new Chart(ctx,{
        type: 'bar',
        data: {
            labels: [<?php foreach($graficas as $grafica){echo "'".$grafica['fecha']."',";} ?>], 
            datasets: [{ 
                label: 'CO2 Equivalente Ton', 
                data: [<?php foreach($graficas as $grafica){echo "'".$grafica['cantidad']."',";} ?>], 
                borderWidth: 2,
                borderColor: '#356823',
                backgroundColor: '#64b34385',
                order: 1
            }]
        },
        options: {
            animations:{
                animations:{
                    duration: 1000,
                    easing: 'linear',
                    from: 1,
                    to: 0,
                    loop: true
                }
            }
        }
    });

</script>