<?php
    $pagina = "Viajes";
    include "../../templates/header.php"; 
    include "../../controllers/bd.php";

    if($_SESSION['privilegios'] == 2 || $_SESSION['privilegios'] == 3 || $_SESSION['privilegios'] == 5){
        header("Location:../../controllers/cerrar.php");
    }

    date_default_timezone_set('America/Mexico_City');
?>
<main class="main_inicio">
    <h1>Viaje pendiente</h1>

    <?php
        $existeRegistro = false;
        $formulario = isset($_GET['form']) ? $_GET['form'] : '';
        $manifiestoQR = isset($_GET['manifiesto']) ? $_GET['manifiesto'] : '';
        $numeroRegistro = 0;

        if($manifiestoQR){
            $sentencias = $conexion->prepare("SELECT * FROM tabla_transporte WHERE manifiesto = :manifiesto");
            $sentencias->bindParam(":manifiesto", $manifiestoQR);
            $sentencias->execute();
            $registro = $sentencias->fetch(PDO::FETCH_LAZY);
            if($registro){
                $existeRegistro = true;
                $id = $registro['id'];
                $fecha = $registro['fecha'];
                $manifiesto = $registro['manifiesto'];
                $destino = $registro['destino'];
                $operador = $registro['operador'];

                $inicio = $registro['inicio_viaje'];
                $llegada = $registro['llegada_destino'];
                $salida = $registro['salida_destino'];
                $fin = $registro['fin_viaje'];
                if($inicio == "0000-00-00 00:00:00"){ $numeroRegistro++; }
                if($llegada == "0000-00-00 00:00:00"){ $numeroRegistro++; }
                if($salida == "0000-00-00 00:00:00"){ $numeroRegistro++; }
                if($fin == "0000-00-00 00:00:00"){ $numeroRegistro++; }   

                if($formulario == 1){
                    $registro = array_key_first($_POST);
                    $valor = $_POST[$registro];
                    $sentencias = $conexion->prepare("UPDATE tabla_transporte SET " . $registro . " = :valor WHERE manifiesto = :manifiesto");
                    $sentencias->bindParam(":valor", $valor);
                    $sentencias->bindParam(":manifiesto", $manifiesto);
                    $sentencias->execute();
                    header("Location:choferes.php?manifiesto=$manifiesto");
                }
            }
            else{
                header("Location:choferes.php");
            }
        }
    ?>

    <br>
    <?php if(!$existeRegistro){ ?>
    <div style="width: auto" id="reader"></div>
    <br>
    <form action="" method="get" id="formResultado" class="formResultado"></form>
    <?php } ?>
    <br>
    <?php if($existeRegistro) { ?>
        <div class="tablaCompuesta">
            <div class="tablaCompuesta_apartado">
                <div class="tablaCompuesta_componente colorCabezera"><p>Fecha</p></div>
                <div class="tablaCompuesta_componente colorBody"><p><?php echo $fecha; ?></p></div>
            </div>
            <div class="tablaCompuesta_apartado">
                <div class="tablaCompuesta_componente colorCabezera"><p>Manifiesto</p></div>
                <div class="tablaCompuesta_componente colorBody"><p><?php echo $manifiesto; ?></p></div>
            </div>
            <div class="tablaCompuesta_apartado">
                <div class="tablaCompuesta_componente colorCabezera"><p>Destino</p></div>
                <div class="tablaCompuesta_componente colorBody"><p><?php echo $destino; ?></p></div>
            </div>
            <div class="tablaCompuesta_apartado">
                <div class="tablaCompuesta_componente colorCabezera"><p>Operador</p></div>
                <div class="tablaCompuesta_componente colorBody"><p><?php echo $operador; ?></p></div>
            </div>
        </div>
        <br>
        <br>
        <form action="?manifiesto=<?php echo $manifiestoQR; ?>&form=1" method="post">
            <?php if($numeroRegistro == 4) { ?>
                <input style="display: none;" class="fechaViajes" type="datetime" name="inicio_viaje" value="<?php echo date('Y-m-d') . " " . date("H:i:s"); ?>">
                <button class="botonPantalla">Iniciar Viaje</button>
            <?php } else if($numeroRegistro == 3) { ?>
                <input style="display: none;" class="fechaViajes" type="datetime" name="llegada_destino" value="<?php echo date('Y-m-d') . " " . date("H:i:s"); ?>">
                <button class="botonPantalla">Llegada destino</button>
            <?php } else if($numeroRegistro == 2) { ?>
                <input style="display: none;" class="fechaViajes" type="datetime" name="salida_destino" value="<?php echo date('Y-m-d') . " " . date("H:i:s"); ?>">
                <button class="botonPantalla">Salida destino</button>
            <?php } else if($numeroRegistro == 1) { ?>
                <input style="display: none;" class="fechaViajes" type="datetime" name="fin_viaje" value="<?php echo date('Y-m-d') . " " . date("H:i:s"); ?>">
                <button class="botonPantalla">Fin del viaje</button>
            <?php } ?>
        </form>
        <br>
    <?php }?>
</main>
<?php
    include "../../templates/footer.php"; 
?>
<script src="<?php echo $url; ?>js/leer.js"></script>