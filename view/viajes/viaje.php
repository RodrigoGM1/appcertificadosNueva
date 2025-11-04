<?php
    $pagina = "viajes";
    include "../../templates/header.php"; 
    include "../../controllers/bd.php";

    if($_SESSION['privilegios'] == 3 || $_SESSION['privilegios'] == 4 || $_SESSION['privilegios'] == 5){
        header("Location:../../controllers/cerrar.php");
    }

    $carpeta = $_GET['carpeta'];
    $destino = $_GET['nombreCarpeta'];

    $sentencias = $conexion->prepare("SELECT * FROM tabla_transporte WHERE :destino = destino ORDER BY fecha DESC");
    $sentencias->bindParam(":destino", $destino);
    $sentencias->execute();
    $viajes = $sentencias->fetchAll(PDO::FETCH_ASSOC);
    $datos_meses = array();
    foreach($viajes as $viaje){
        $meses = date('m', strtotime($viaje['fecha']));
        $datos_meses[$meses][] = $viaje;   
    }

    function conversionMeses(string $mes) : string {
        $mesRetorno = "";
        switch($mes){
            case '01' : $mesRetorno = "Enero"; break;
            case '02' : $mesRetorno = "Febrero"; break;
            case '03' : $mesRetorno = "Marzo"; break;
            case '04' : $mesRetorno = "Abril"; break;
            case '05' : $mesRetorno = "Mayo"; break;
            case '06' : $mesRetorno = "Junio"; break;
            case '07' : $mesRetorno = "Julio"; break;
            case '08' : $mesRetorno = "Agosto"; break;
            case '09' : $mesRetorno = "Septiembre"; break;
            case '10' : $mesRetorno = "Octubre"; break;
            case '11' : $mesRetorno = "Noviembre"; break;
            case '12' : $mesRetorno = "Diciembre"; break;
        }
        return $mesRetorno;
    }
?>
<main class="main_inicio">
    <div class="encabezadoViajes">
        <h1>Viajes <?php echo $destino; ?></h1>
        <a class="encabezadoViajes_a" href="todosViajes.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $destino; ?>">Todos los viajes</a>
    </div>
    <br>
    <?php foreach($datos_meses as $key => $dato_mes){ ?>
    <h3 class="mes">Mes <?php echo conversionMeses($key); ?></h3>
    <table class="tablaViajes">
        <thead>
            <tr>
                <td>Fecha</td>
                <td>Manifiesto</td>
                <td>Operador</td>
                <td>Placas</td>
                <td>Inicio Viaje</td>
                <td>Llegada Destino</td>
                <td>Salida Destino</td>
                <td>Fin Viaje</td>
                <td>Recepcion</td>
                <td>Transformacion</td>
                <td>Aprovechamiento</td>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($dato_mes as $dato) {?>
            <tr class="<?php if(($i % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">
                <td><?php echo $dato['fecha']; ?></td>
                <td><?php echo $dato['manifiesto']; ?></td>
                <td><?php echo $dato['operador']; ?></td>
                <td><?php echo $dato['placas']; ?></td>
                <td class="<?php if($dato['inicio_viaje'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($dato['inicio_viaje'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $dato['inicio_viaje']; } ?></td>
                <td class="<?php if($dato['llegada_destino'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($dato['llegada_destino'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $dato['llegada_destino']; } ?></td>
                <td class="<?php if($dato['salida_destino'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($dato['salida_destino'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $dato['salida_destino']; } ?></td>
                <td class="<?php if($dato['fin_viaje'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($dato['fin_viaje'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $dato['fin_viaje']; } ?></td>
                <?php if($dato['nota']){ ?>
                    <td>
                        <a target="_black" href="mostrarImagen.php?id=<?php echo $dato['id']; ?>&dato=nota">
                            <?php echo '<img class="imagenViajes" height="15" width="15" src="data:image/jpeg;base64,'.base64_encode($dato["nota"]).'"/>'; ?>
                        </a>
                    </td>
                <?php }else { ?>
                    <td class="registroTabla">No hay evidencia</td>
                <?php } ?>
                <?php if($dato['transformacion']){ ?>
                    <td>
                        <a target="_black" href="mostrarImagen.php?id=<?php echo $dato['id']; ?>&dato=transformacion">
                            <?php echo '<img class="imagenViajes" height="15" width="15" src="data:image/jpeg;base64,'.base64_encode($dato["transformacion"]).'"/>'; ?>
                        </a>
                    </td>
                <?php }else { ?>
                    <td class="registroTabla">No hay evidencia</td>
                <?php } ?>
                <?php if($dato['aprovechamiento']){ ?>
                    <td>
                        <a target="_black" href="mostrarImagen.php?id=<?php echo $dato['id']; ?>&dato=aprovechamiento">
                            <?php echo '<img class="imagenViajes" height="15" width="15" src="data:image/jpeg;base64,'.base64_encode($dato["aprovechamiento"]).'"/>'; ?>
                        </a>
                    </td>
                <?php }else { ?>
                    <td class="registroTabla">No hay evidencia</td>
                <?php } ?>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
    <br>
    <?php } ?>

</main>
<?php
    include "../../templates/footer.php"; 
?>