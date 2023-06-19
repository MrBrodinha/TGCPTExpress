<?php
    header("Content-Type: application/json; charset=UTF-8");
    include_once '../../config/database.php';
    include_once 'comentarios.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Comentarios($db);
    $item->idpub = isset($_GET['idpub']) ? $_GET['idpub'] : die();
    $stmt = $item->getSingleComentarios($item->idpub);
    $itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $ComArr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "IDCom" => $IDCom,
            "IDPerfil" => $IDPerfil,
            "IDPub" => $IDPub,
            "Comentario" => $Comentario
        );
        $ComArr[] = $e;
    }
    echo json_encode($ComArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("No record found.")
    );
}
