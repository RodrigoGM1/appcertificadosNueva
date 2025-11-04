<?php
    $pagina = "aprovechamiento";
    include "../../controllers/bd.php";
    include "../../templates/header.php"; 

    if($_SESSION['privilegios'] == 2 || $_SESSION['privilegios'] == 3 || $_SESSION['privilegios'] == 4){
        header("Location:../../controllers/cerrar.php");
    }
?>
<main class="main_inicio">
    <h1>Aprovechamiento</h1>

    <?php
        $existeRegistro = false;
        $formulario = isset($_GET['form']) ? $_GET['form'] : '';
        $manifiestoQR = isset($_GET['manifiesto']) ? $_GET['manifiesto'] : '';

        if($manifiestoQR){
            $sentencias = $conexion->prepare("SELECT * FROM tabla_transporte WHERE manifiesto = :manifiesto");
            $sentencias->bindParam(":manifiesto", $manifiestoQR);
            $sentencias->execute();
            $registro = $sentencias->fetch(PDO::FETCH_LAZY);

            if($registro){
                $existeRegistro = true;
                $id = $registro['id'];
                $transformacion = $registro['transformacion'];
                $aprovechamiento = $registro['aprovechamiento'];
                $llegada = $registro['nota'];

                if($formulario == 2){
                    $registro = array_key_first($_FILES);

                    // $imagenData = file_get_contents($_FILES[$registro]['tmp_name']);
                    $imagenNueva = 'mi_foto_resize.jpg';
                    $ancho = 400;
                    $alto = 400;
                    $imagenOriginal = $_FILES[$registro]['tmp_name'];
                    $imagen = imagecreatefromjpeg($imagenOriginal); 
                    $x = imagesx($imagen);
                    $y = imagesy($imagen);
                    $img = imagecreatetruecolor($ancho, $ancho);
                    imagecopyresized($img, $imagen, 0, 0, 0, 0, $ancho, $alto, $x, $y);
                    imagejpeg($img, $imagenNueva);

                    $imagenData = file_get_contents($imagenNueva);



                    $sentencias = $conexion->prepare("UPDATE tabla_transporte SET $registro = :nota  WHERE id = :id");
                    $sentencias->bindParam(":nota", $imagenData);
                    $sentencias->bindParam(":id", $id);
                    $sentencias->execute(); 
                    header("Location:aprobechamiento.php?manifiesto=$manifiestoQR");
    
                }
            }
            else{
                header("Location:aprobechamiento.php");
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
    <?php if($existeRegistro){ ?>
        <form class="formularioNota" action="?manifiesto=<?php echo $manifiestoQR; ?>&form=2" method="post" enctype="multipart/form-data">
            <?php if(!$llegada) { ?>
                <input type="file" name="nota" required>
                <button type="submit" name="upload">LLegada</button>
            <?php } ?>

            <?php if(!$transformacion && $llegada) { ?>
                <input type="file" name="transformacion" required>
                <button type="submit" name="upload">Transformacion</button>
            <?php } ?>
            
            <?php if(!$aprovechamiento && $transformacion){ ?>
                <input type="file" name="aprovechamiento" required>
                <button type="submit" name="upload">Aprovechamiento	</button>
            <?php } ?>
        </form>
    <?php } ?>
</main>
<?php
    include "../../templates/footer.php"; 
?>
<script src="<?php echo $url; ?>js/leer.js"></script>