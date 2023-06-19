<?php
    header("Content-Type: application/json; charset=UTF-8");
    include_once '../../config/database.php';
    include_once 'Likes.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Likes($db);
    $item->idpub = isset($_GET['idpub']) ? $_GET['idpub'] : die();
    $stmt = $item->getLikesDePub($item->idpub);
    $itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $likesArr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "IDLike" => $IDLike,
            "IDPerfil" => $IDPerfil,
            "IDPub" => $IDPub,
            "Gosto" => $Gosto
        );
        $likesArr[] = $e;
    }
    echo json_encode($likesArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("No record found.")
    );
}
?>
