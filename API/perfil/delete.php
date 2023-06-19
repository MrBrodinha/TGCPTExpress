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
    
    if($item->deletePerfil()){
        echo json_encode("Perfil deleted.");
    } else{
        echo json_encode("Perfil could not be deleted");
    }
