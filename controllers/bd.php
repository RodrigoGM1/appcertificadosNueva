<?php
    $servidor = 'localhost';
    $baseDatos = 'forraje1_base_elcorral';
    $usuario = 'root';
    $clave = 'root';

    try{
        $conexion = new PDO("mysql:host=$servidor; dbname=$baseDatos",$usuario,$clave);
    }catch(Exception $ex){
         echo $ex->getMessage();
    }