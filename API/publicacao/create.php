<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
    include_once '../../config/database.php';
    include_once 'publicacao.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Publicacao($db);
    $data = json_decode(file_get_contents("php://input"));
    $item->titulo = $data->titulo;
    $item->descricao = $data->descricao;
    $item->imagem = $data->imagem;
    $item->data = $data->data;
    $item->idperfil = $data->idperfil;
    
    if($item->createPublicacao()){
        echo 'Publicação created successfully.';
    } else{
        echo 'Publicação could not be created.';
    }
?>