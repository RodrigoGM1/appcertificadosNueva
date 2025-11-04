<?php
    $pagina = "inicio";
    include "../templates/header.php"; 
    include '../controllers/bd.php';

    $carpeta = $_GET['carpeta'];
    $nombreCarpeta = $_GET['nombreCarpeta'];

    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 15;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

    $sentencias = $conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tabla_certificados WHERE id_carpeta = :id ORDER BY fecha DESC LIMIT $inicio,$regpagina");
    $sentencias->bindParam(":id", $carpeta);
    $sentencias->execute();
    $resultadoEvidencias = $sentencias->fetchAll(PDO::FETCH_ASSOC);

    $totalregistro = $conexion->query("SELECT FOUND_ROWS() AS total");
    $totalregistro = $totalregistro->fetch()['total'];
    $Numeropaginas = ceil($totalregistro / $regpagina);
?>

<main class="main_inicio">
    <h1>Certificados <?php echo $nombreCarpeta; ?></h1>
    <br>
    <table class="tabla">
        <thead>
            <tr>
                <td>Fecha de recolección</td>
                <td>Manifiesto</td>
                <td>Notas</td>
                <td>Evidencia</td>
                <td>Certificado</td>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; foreach($resultadoEvidencias as $resultadoEvidencia){ ?>
                <tr class="<?php if(($i % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">
                    <td><?php echo $resultadoEvidencia['fecha']; ?></td>

                    <?php if($resultadoEvidencia['manifiesto'] == ""){ ?>
                        <td>Sin manifiesto</td>
                    <?php } else { ?>
                        <td><a target="_black" href="EvidenciasSub/Manifiestos/<?php echo $nombreCarpeta ?>/<?php echo $resultadoEvidencia['manifiesto']; ?>"><i class="fa-regular fa-file-image estiloOjo"></i></a></td>
                    <?php }  ?>

                    <?php if($resultadoEvidencia['nota'] == ""){ ?>
                        <td>Sin nota</td>
                    <?php } else { ?>
                        <td><a target="_black" href="EvidenciasSub/Notas/<?php echo $nombreCarpeta ?>/<?php echo $resultadoEvidencia['nota']; ?>"><i class="fa-solid fa-clipboard estiloOjo"></i></a></td>
                    <?php }  ?>
                    
                    <?php if($resultadoEvidencia['n_evidencias'] == ""){ ?>
                        <td>Sin evidencia</td>
                    <?php } else { ?>
                        <td><a target="_black" href="EvidenciasDoc/<?php echo $nombreCarpeta ?>/<?php echo $resultadoEvidencia['n_evidencias']; ?>"><i class="fa-regular fa-file-pdf estiloOjo"></i></a></td>
                    <?php }  ?>
                    
                    <?php if($resultadoEvidencia['nombre_c'] == ""){ ?>
                        <td>Sin certificado</td>
                    <?php } else { ?>
                        <td><a target="_black" href="CertificadosDoc/<?php echo $nombreCarpeta ?>/<?php echo $resultadoEvidencia['nombre_c']; ?>"><i class="fa-regular fa-file-pdf estiloOjo"></i></a></td>
                    <?php }  ?>
                    
                </tr>
            <?php $i++; } ?>
                            
        </tbody>
    </table>
    <nav class="paginador">
        <ul>
            <?php if($pagina == 1){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-backward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="verCertificado.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $pagina-1; ?>"><i class="fa-solid fa-backward"></i></a></li>
            <?php } ?>
            <?php for($i=1; $i<=$Numeropaginas; $i++){ ?>
                <?php if($pagina == $i){ ?>
                    <li class="select"><a class="selectA" href="verCertificados.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a class="selectV" href="verCertificado.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <?php if($pagina == $Numeropaginas){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-forward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="verCertificado.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $pagina+1; ?>"><i class="fa-solid fa-forward"></i></a></li>
            <?php } ?>
        </ul>
    </nav>
</main>
<?php
    include "../templates/footer.php"; 
?>