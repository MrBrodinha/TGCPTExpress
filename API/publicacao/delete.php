<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
include_once '../../config/database.php';
include_once 'publicacao.php';

$database = new Database();
$db = $database->getConnection();

$item = new Publicacao($db);

$data = json_decode(file_get_contents("php://input"));

$item->idpub = $data->idpub;

if ($item->deletePublicacao()) {
    $files = glob('../../../_publicacoes/imagem/' . $data->idpub. '_*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    echo json_encode("Publicação deleted.");
} else {
    echo json_encode("Publicação could not be deleted");
}
