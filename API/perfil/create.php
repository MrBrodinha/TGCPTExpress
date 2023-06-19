<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'perfil.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Perfil($db);
    $data = json_decode(file_get_contents("php://input"));
    $item->nickname = $data->nickname;
    $item->email = $data->email;
    $item->pass = $data->pass;
    $item->companhia = $data->companhia;
    $item->imagem = $data->imagem;
    $item->perms = $data->perms;
    
    if($item->createPerfil()){
        echo 'Perfil created successfully.';
    } else{
        echo 'Perfil could not be created.';
    }
?>