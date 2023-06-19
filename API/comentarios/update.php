<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'comentarios.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Comentarios($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    // perfil values
    $item->idperfil = $data->idperfil;
    $item->idpub = $data->idpub;
    $item->comentario = $data->comentario;
    $item->idcom = $data->idcom;

    
    if($item->updateComentarios()){
        echo json_encode("Comentarios data updated.");
    } else{
        echo json_encode("Comentarios could not be updated");
    }
