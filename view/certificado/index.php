<?php
    $pagina = "certificado";
    include "../../templates/header.php"; 
    include "../../controllers/bd.php";

    if($_SESSION['privilegios'] == 2){
        header("Location:../../controllers/cerrar.php");
    }
    
    $sentencias = $conexion->prepare("SELECT * FROM tabla_carpetas");
    $sentencias->execute();
    $carpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="main_inicio">
    <h1>Certificados</h1>
    <?php foreach($carpetas as $carpeta) { ?>
    <a href="subCertificados.php?carpeta=<?php echo $carpeta['id']; ?>&nombreCarpeta=<?php echo $carpeta['nombre_carpeta']; ?>" class="contenedor_carpeta">
        <i class="fa-regular fa-folder-open"></i>
        <p><?php echo $carpeta['nombre_carpeta']; ?></p>
    </a>
    <?php } ?>
</main>
<?php
    include "../../templates/footer.php"; 
?>