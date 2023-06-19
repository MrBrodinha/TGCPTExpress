<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'likes.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Likes($db);
    $data = json_decode(file_get_contents("php://input"));
    $item->idperfil = $data->idperfil;
    $item->idpub = $data->idpub;
    $item->gosto = $data->gosto;
    
    if($item->createLikes()){
        echo 'Likes.';
    } else{
        echo 'Not Likes.';
    }
?>