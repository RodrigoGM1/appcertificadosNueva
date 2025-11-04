<?php
    include "../../controllers/bd.php";
    $id = $_GET['id'];
    $dato = $_GET['dato'];
    $sentencias = $conexion->prepare("SELECT " .$dato. " FROM tabla_transporte WHERE :id = id");
    $sentencias->bindParam(":id", $id);
    $sentencias->execute();
    $imagen = $sentencias->fetch(PDO::FETCH_LAZY);
    echo '<img style="width: 100%" src="data:image/jpeg;base64,'.base64_encode($imagen[$dato]).'"/>';
?>