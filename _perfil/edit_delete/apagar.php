<?php
session_start();

//elimina comentarios
$curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/comentarios/read.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
curl_close($curl);

$comentarios = json_decode($curl_response, true);

if ($comentarios[0] != "No record found.") {
    foreach ($comentarios as $comentario) {
        if ($comentario['IDPerfil'] == $_SESSION['idperfil']) {
            $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/comentarios/delete.php");
            $data = array(
                'idcom' => -1,
                'idperfil' => $comentario['IDPerfil'],
                'idpub' => -1
            );
            $data_string = json_encode($data);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curl);
            curl_close($curl);
        }
    }
}


//Ler likes e eliminar os que têm idperfil = idperfil da sessão
$curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/likes/read.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
curl_close($curl);
$likes = json_decode($curl_response, true);

if ($likes[0] != "No record found.") {
    foreach ($likes as $like) {
        if ($like['IDPerfil'] == $_SESSION['idperfil']) {
            $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/likes/delete.php");
            $data = array(
                'idperfil' => $like['IDPerfil'],
                'idpub' => -1
            );
            $data_string = json_encode($data);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curl);
            curl_close($curl);
        }
    }
}

$curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/publicacao/single_read.php?idperfil=".$_SESSION['idperfil']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
curl_close($curl);
$posts = json_decode($curl_response, true);
if ($posts[0] != "No record found.") {
    foreach ($posts as $post) {
        $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/publicacao/delete.php");
        $data = array(
            'idpub' => $post['IDPub']
        );
        $data_string = json_encode($data);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        curl_close($curl);

        //elimina imagens dos posts
        $folderPath = '../../_publicacoes/imagem/';
        $pattern = $folderPath . $_SESSION["idperfil"] . '*';
        $files = glob($pattern);
        foreach ($files as $file) {
            unlink($file);
        }
    }
}

//elimina pfp do utilizador
$folderPath = '../pfp/';
$pattern = $folderPath . $_SESSION['nickname'] . '.jpg';
$files = glob($pattern);
foreach ($files as $file) {
    unlink($file);
}


//elimina utilizador
$curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/delete.php");
$data = array(
    'idperfil' => $_SESSION['idperfil']
);
$data_string = json_encode($data);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_exec($curl);
curl_close($curl);

header("location: ../../_login/logout.php")
?>