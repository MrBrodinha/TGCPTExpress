<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'Perfil.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Perfil($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->idperfil = $data->idperfil;
    
    // perfil values
    $item->nickname = $data->nickname;
    $item->email = $data->email;
    $item->pass = $data->pass;
    $item->companhia = $data->companhia;
    $item->imagem = $data->imagem;
    $item->perms = $data->perms;

    
    if($item->updatePerfil()){
        echo json_encode("Perfil data updated.");
    } else{
        echo json_encode("Perfil could not be updated");
    }
