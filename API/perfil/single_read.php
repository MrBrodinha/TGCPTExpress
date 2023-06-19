<?php
    header("Content-Type: application/json; charset=UTF-8");
    include_once '../../config/database.php';
    include_once 'Perfil.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Perfil($db);
    $item->idperfil = isset($_GET['idperfil']) ? $_GET['idperfil'] : die();

    $item->getSinglePerfil();
    if($item->nickname != null){
        // create array
        $perfil_arr = array(
            "IDPerfil" =>  $item->idperfil,
            "Nickname" => $item->nickname,
            "Email" => $item->email,
            "Pass" => $item->pass,
            "Companhia" => $item->companhia,
            "Imagem" => $item->imagem,
            "Perms" => $item->perms
        );
      
        http_response_code(200);
        echo json_encode($perfil_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode("Perfil not found.");
    }
?>
