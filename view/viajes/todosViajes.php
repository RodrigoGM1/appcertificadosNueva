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
?>
<main class="main_inicio">
    <h1>Todos los viajes <?php echo $destino; ?></h1>
    <br><br>
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
            <?php $i=0; foreach($viajes as $viaje) {?>
            <tr class="<?php if(($i % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">
                <td><?php echo $viaje['fecha']; ?></td>
                <td><?php echo $viaje['manifiesto']; ?></td>
                <td><?php echo $viaje['operador']; ?></td>
                <td><?php echo $viaje['placas']; ?></td>
                <td class="<?php if($viaje['inicio_viaje'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($viaje['inicio_viaje'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $viaje['inicio_viaje']; } ?></td>
                <td class="<?php if($viaje['llegada_destino'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($viaje['llegada_destino'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $viaje['llegada_destino']; } ?></td>
                <td class="<?php if($viaje['salida_destino'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($viaje['salida_destino'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $viaje['salida_destino']; } ?></td>
                <td class="<?php if($viaje['fin_viaje'] == '0000-00-00 00:00:00') { echo "registroTabla"; } ?>" ><?php if($viaje['fin_viaje'] == '0000-00-00 00:00:00') { echo "No hay registro"; }else { echo $viaje['fin_viaje']; } ?></td>
                <?php if($viaje['nota']){ ?>
                    <td>
                        <a target="_black" href="mostrarImagen.php?id=<?php echo $viaje['id']; ?>">
                            <?php echo '<img class="imagenViajes" height="15" width="15" src="data:image/jpeg;base64,'.base64_encode($viaje["nota"]).'"/>'; ?>
                        </a>
                    </td>
                <?php }else { ?>
                    <td class="registroTabla">No hay evidencia</td>
                <?php } ?>
                <?php if($viaje['transformacion']){ ?>
                    <td>
                        <a target="_black" href="mostrarImagen.php?id=<?php echo $viaje['id']; ?>">
                            <?php echo '<img class="imagenViajes" height="15" width="15" src="data:image/jpeg;base64,'.base64_encode($viaje["transformacion"]).'"/>'; ?>
                        </a>
                    </td>
                <?php }else { ?>
                    <td class="registroTabla">No hay evidencia</td>
                <?php } ?>
                <?php if($viaje['aprovechamiento']){ ?>
                    <td>
                        <a target="_black" href="mostrarImagen.php?id=<?php echo $viaje['id']; ?>">
                            <?php echo '<img class="imagenViajes" height="15" width="15" src="data:image/jpeg;base64,'.base64_encode($viaje["aprovechamiento"]).'"/>'; ?>
                        </a>
                    </td>
                <?php }else { ?>
                    <td class="registroTabla">No hay evidencia</td>
                <?php } ?>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
</main>
<?php
    include "../../templates/footer.php"; 
?>