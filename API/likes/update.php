<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'Likes.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Likes($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    // perfil values
    $item->idperfil = $data->idperfil;
    $item->idpub = $data->idpub;
    $item->gosto = $data->gosto;
    $item->idlike = $data->idlike;

    
    if($item->updateLikes()){
        echo json_encode("Likes data updated.");
    } else{
        echo json_encode("Likes could not be updated");
    }
