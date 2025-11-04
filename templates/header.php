<?php
    session_start();
    $url = "https://appcertificados.forrajeselcorral.com/";
    if(!isset($_SESSION['user'])){
        header("Location:".$url."index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificados</title>
    <link rel="shortcut icon" href="<?php echo $url; ?>img/logo.gif">
    <link rel="stylesheet" href="<?php echo $url; ?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo $url; ?>css/estilos.css">
    <link rel="stylesheet" href="<?php echo $url; ?>css/iconos/css/all.css">
    <link rel="stylesheet" href="<?php echo $url; ?>css/iconos/css/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $url; ?>css/iconos/css/solid.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&family=Open+Sans:wght@300;500;700&family=Roboto&display=swap" rel="stylesheet">
</head>
<body class="body">
    
    <header class="header_inicio">
        <div class="header_inicio_conte">
            <i id="iconoBarras" class="fa-solid fa-bars"></i>
            <img src="<?php echo $url; ?>img/logo.gif" alt="">
            <p>Industria circular</p>
        </div>
        <div class="header_inicio_conte">
            <i class="fa-solid fa-user"></i>
            <p><?php echo $_SESSION['user']; ?></p>
        </div>
    </header>
    
    <div id="menuInicio" class="menu_inicio">

        <?php if($_SESSION['privilegios'] == 1){ ?>

        <a href="<?php echo $url ?>view/inicio.php" class="<?php if($pagina == "inicio"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-house"></i>
            <span class="oculto">Inicio</span>
        </a>
        <a href="<?php echo $url ?>view/usuarios/index.php" class="<?php if($pagina == "usuario"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-users"></i>
            <span class="oculto">Usuarios</span>
        </a>
        <a href="<?php echo $url ?>view/carpeta/index.php" class="<?php if($pagina == "carpeta"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-folder-open"></i>
            <span class="oculto">Carpetas</span>
        </a>
        <a href="<?php echo $url ?>view/evidencia/index.php" class="<?php if($pagina == "evidencia"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-receipt"></i>
            <span class="oculto">Evidencias</span>
        </a>
        <a href="<?php echo $url ?>view/certificado/index.php" class="<?php if($pagina == "certificado"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-certificate"></i>
            <span class="oculto">Certificados</span>
        </a>
        <a href="<?php echo $url ?>view/reportes/index.php" class="<?php if($pagina == "reporte"){ echo "selecionada"; } ?>">
            <img style="width: 2rem" src="<?php echo $url ?>img/co2.png" alt="icono">
            <span class="oculto">Reportes</span>
        </a>
        <a href="<?php echo $url ?>view/viajes/viajes.php" class="<?php if($pagina == "viajes"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-route"></i>
            <span class="oculto">Transformacion</span>
        </a>
        <a href="<?php echo $url; ?>view/administracion/administracion.php" class="<?php if($pagina == "Administración"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-dolly"></i>
            <span class="oculto">Asignar viajes</span>
        </a>
        <a href="<?php echo $url; ?>view/administracion/aprobechamiento.php" class="<?php if($pagina == "aprovechamiento"){ echo "selecionada"; } ?>">
            <i class="fa-brands fa-accusoft"></i>
            <span class="oculto">Aprovechamiento</span>
        </a>
        <a href="<?php echo $url ?>controllers/cerrar.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="oculto">Salir</span>
        </a>

        <?php } if($_SESSION['privilegios'] == 2) { $carpeta = $_GET['carpeta']; $nombreCarpeta = $_GET['nombreCarpeta']; ?>
        <a href="<?php echo $url; ?>view/verCertificado.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>" class="<?php if($pagina == "inicio"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-house"></i>
            <span class="oculto">Inicio</span>
        </a>
        <a href="<?php echo $url; ?>view/reportes/subReporte.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>" class="<?php if($pagina == "reporte"){ echo "selecionada"; } ?>">
            <img style="width: 2rem" src="<?php echo $url ?>img/co2.png" alt="icono">
            <span class="oculto">Reportes</span>
        </a>
        <a href="<?php echo $url ?>view/viajes/viaje.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>" class="<?php if($pagina == "viajes"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-route"></i>
            <span class="oculto">Transformacion</span>
        </a>
        <a href="<?php echo $url ?>controllers/cerrar.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="oculto">Salir</span>
        </a>
        <?php } ?>

        <?php if($_SESSION['privilegios'] == 3) { ?>
        <a href="<?php echo $url; ?>view/administracion/administracion.php" class="<?php if($pagina == "Administración"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-dolly"></i>
            <span class="oculto">Asignar viajes</span>
        </a>
        <a href="<?php echo $url ?>controllers/cerrar.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="oculto">Salir</span>
        </a>
        <?php } ?>

        <?php if($_SESSION['privilegios'] == 4) { ?>
        <a href="<?php echo $url; ?>view/administracion/choferes.php" class="<?php if($pagina == "Viajes"){ echo "selecionada"; } ?>">
            <i class="fa-solid fa-dolly"></i>
            <span class="oculto">Viajes</span>
        </a>
        <a href="<?php echo $url ?>controllers/cerrar.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="oculto">Salir</span>
        </a>
        <?php } ?>

        <?php if($_SESSION['privilegios'] == 5) { ?>
            <a href="<?php echo $url; ?>view/administracion/aprobechamiento.php" class="<?php if($pagina == "aprovechamiento"){ echo "selecionada"; } ?>">
            <i class="fa-brands fa-accusoft"></i>
            <span class="oculto">Aprovechamiento</span>
        </a>
        <a href="<?php echo $url ?>controllers/cerrar.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="oculto">Salir</span>
        </a>
        <?php } ?>
    </div>