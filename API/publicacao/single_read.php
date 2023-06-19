<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../../config/database.php';
include_once 'publicacao.php';
$database = new Database();
$db = $database->getConnection();
$item = new Publicacao($db);
$idperfil = isset($_GET['idperfil']) ? $_GET['idperfil'] : die();
$stmt = $item->getPerfilPublicacao($idperfil);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $PublicacaoArr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "IDPub" => $IDPub,
            "Titulo" => $Titulo,
            "Descricao" => $Descricao,
            "Imagem" => $Imagem,
            "Data" => $Data,
            "IDPerfil" => $IDPerfil
        );
        $PublicacaoArr[] = $e;
    }
    echo json_encode($PublicacaoArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("No record found.")
    );
}
