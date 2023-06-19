<?php
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../../config/database.php';
    include_once 'Perfil.php';
    $database = new Database();
    $db = $database->getConnection();
    $items = new Perfil($db);
    $stmt = $items->getPerfil();
    $itemCount = $stmt->rowCount();

    if($itemCount > 0){
        $perfilArr = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "IDPerfil" => $IDPerfil,
                "Nickname" => $Nickname,
                "Email" => $Email,
                "Pass" => $Pass,
                "Companhia" => $Companhia,
                "Imagem" => $Imagem,
                "Perms" => $Perms
            );
            $perfilArr[] = $e;
        }
        echo json_encode($perfilArr);
    }
    else{
        http_response_code(404);
        echo json_encode(
            array("ID" => "No record found.")
        );
    }
