<?php
    $pagina = "Administración";
    include "../../templates/header.php"; 
    include "../../controllers/bd.php";

    if($_SESSION['privilegios'] == 2 || $_SESSION['privilegios'] == 4 || $_SESSION['privilegios'] == 5){
        header("Location:../../controllers/cerrar.php");
    }
?>
<main class="main_inicio">
    <h1>Asignar viajes</h1>

    <?php
        $errores = [];

        $fechaRecolecion = "";
        $manifiesto = "";
        $destino = "";
        $operador = "";
        $placas = "";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $fechaRecolecion = $_POST['fechaRecolecion'];
            $manifiesto = $_POST['manifiesto'];
            $destino = $_POST['destino'];
            $operador = $_POST['operador'];
            $placas = $_POST['placas'];

            if(!$fechaRecolecion){
                $errores[] = "Agregar la fecha de recolección";
            }
            if(!$manifiesto){
                $errores[] = "Agregar el número del manifiesto";
            }
            if(!$destino){
                $errores[] = "Agregar el destino";
            }
            if(!$operador){
                $errores[] = "Agregar el operador";
            }
            if(!$placas){
                $errores[] = "Agregar las placas de la unidad";
            }

            if(empty($errores)){
                $sentencias = $conexion->prepare("INSERT INTO tabla_transporte(id , fecha, manifiesto, destino, operador, placas) VALUES (NULL , :fecha, :manifiesto, :destino, :operador, :placas)");
                $sentencias->bindParam(":fecha", $fechaRecolecion);
                $sentencias->bindParam(":manifiesto", $manifiesto);
                $sentencias->bindParam(":destino", $destino);
                $sentencias->bindParam(":operador", $operador);
                $sentencias->bindParam(":placas", $placas);
                $sentencias->execute();
                if($sentencias){
                    header("Location: administracion.php");
                }
            }
        }

        $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas");
        $sentencias->execute();
        $carpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);

        $sentencias = $conexion->prepare("SELECT usuario FROM tabla_usuarios WHERE idprivilegio = 4");
        $sentencias->execute();
        $chofres = $sentencias->fetchAll(PDO::FETCH_ASSOC); 
    ?>

    <?php foreach($errores as $error){ ?>
        <div class="errorIn">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <div class="contenedorAdministrador">
        <form class="formViaje" method="post">
            <div class="labelViaje">
                <label>Fecha de recolección</label>
                <input type="date" name="fechaRecolecion">
            </div>
            <div class="labelViaje">
                <label>Número de manifiesto</label>
                <input id="text" type="text" name="manifiesto">
            </div>
            <div class="labelViaje">
                <select class="inputUsuario" name="destino">
                    <option value="">-- Destinos --</option>
                    <?php foreach($carpetas as $carpeta) { ?>
                        <option value="<?php echo $carpeta['nombre_carpeta']; ?>"><?php echo $carpeta['nombre_carpeta']; ?></option>
                    <?php } ?>
                </select>

            </div>
            <div class="labelViaje">
                <label>Nombre del chófer</label>
                <select class="inputUsuario" name="operador">
                    <option value="">-- --------- --</option>
                    <?php foreach($chofres as $chofre) { ?>
                        <option value="<?php echo $chofre['usuario']; ?>"><?php echo $chofre['usuario']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="labelViaje">
                <label>Placas</label>
                <input type="text" name="placas">
            </div>
            <button>Guardar</button>
        </form>
    
        <div class="contenedorQr">
            <h2>QR Manifiesto</h2>
            <br>
            <div id="qrcode"></div>
        </div>
    </div>
    <br><br>
    <hr>
    <br><br>
    <h1>Viajes pendientes</h1>
    <br>
    <?php 
        $sentencias = $conexion->prepare("SELECT * FROM tabla_transporte WHERE fin_viaje = ''");
        $sentencias->execute();
        $viajes = $sentencias->fetchAll(PDO::FETCH_ASSOC);
        if(empty($viajes)){
            echo "<br><h2 style='text-align: center; font-size: 2rem;'>No hay viajes</h2>";
        }
    ?>
    <?php if(!empty($viajes)){ ?>
        <table class="tablaViajes">
            <thead>
                <tr>
                    <td>Fecha</td>
                    <td>Destino</td>
                    <td>Manifiesto</td>
                    <td>Operador</td>
                    <td>Placas</td>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; foreach($viajes as $viaje) {?>
                <tr class="<?php if(($i % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">
                    <td><?php echo $viaje['fecha']; ?></td>
                    <td><?php echo $viaje['destino']; ?></td>
                    <td><?php echo $viaje['manifiesto']; ?></td>
                    <td><?php echo $viaje['operador']; ?></td>
                    <td><?php echo $viaje['placas']; ?></td>
                </tr>
                <?php $i++; } ?>
            </tbody>
        </table>

    <?php } ?>
</main>
<script></script>
<?php
    include "../../templates/footer.php"; 
?>
<script src="<?php echo $url; ?>js/qr.js"></script>