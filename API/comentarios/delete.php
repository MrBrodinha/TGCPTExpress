<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'comentarios.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Comentarios($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->idperfil = $data->idperfil;
    $item->idpub = $data->idpub;
    $item->idcom = $data->idcom;
    
    if($item->deleteComentarios()){
        echo json_encode("Comentarios deleted.");
    } else{
        echo json_encode("Comentarios could not be deleted");
    }
