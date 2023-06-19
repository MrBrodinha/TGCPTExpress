<?php
session_start();
$idpub = $_GET['idpub'];
$idperfil = $_SESSION['idperfil'];
$gosto = 0;
$existe = false;
$totalGosto = 0;

$url = 'http://localhost/PWEB/TGCPTExpress/API/likes/read.php';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
curl_close($curl);
$data = json_decode($curl_response);

if ($data[0] != "No record found.") {
    foreach ($data as $row) {
        if ($row->IDPub == $idpub && $row->IDPerfil == $idperfil) {
            $gosto = $row->Gosto;
            $existe = true;
        }

    if ($row->IDPub == $idpub && $row->Gosto == 1)
        $totalGosto++;
    else if ($row->IDPub == $idpub && $row->Gosto == -1)
        $totalGosto--;
    }
}

if (!$existe) {
    //creates table in create.php in likes with Gosto = 0 and IDPerfil and IDPub
    $url = 'http://localhost/PWEB/TGCPTExpress/API/likes/create.php';

    $data = array(
        'idperfil' => $idperfil,
        'idpub' => $idpub,
        'gosto' => 0
    );

    $data = json_encode($data);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $curl_response = curl_exec($curl);
    curl_close($curl);
}

//post dos comentarios, primeiro verificar se não é uma bad word e dps criar tabela
if( $_SERVER['REQUEST_METHOD'] == "POST") {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://neutrinoapi-bad-word-filter.p.rapidapi.com/bad-word-filter",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "censor-character=*&content=" . $_POST["comentario"] . "",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: neutrinoapi-bad-word-filter.p.rapidapi.com",
            "X-RapidAPI-Key: f3fb2928b9mshea1141e9e9a087ep199939jsnebe0f303bf2c",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $isbadnick = json_decode($response, true);

    if (!$isbadnick["is-bad"]) {
        $url = 'http://localhost/PWEB/TGCPTExpress/API/comentarios/create.php';

        $data = array(
            'idperfil' => $idperfil,
            'idpub' => $idpub,
            'comentario' => $_POST["comentario"]
        );

        $data = json_encode($data);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $curl_response = curl_exec($curl);
        curl_close($curl);

        header("location: single-pub.php?idpub=" . $idpub . "");
    }
                
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TGC PT Express</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="pub.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="perfil">
        <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            echo
            "<a id=\"login\" href=\"../_perfil/perfil.php\">
            <img src=\"../_perfil/pfp/" . $_SESSION["imagem"] . "\">
        </a>";
        } else {
            echo
            "<a id=\"login\" href=\"../_login/login.php\">
            <img src=\"../Imagens/login.png\">
        </a>";
        }
        ?>
    </div>
    <div class="centro">
        <img src="../Imagens/Cavalo.png" id="imagem1">
        <img src="../Imagens/text.png" id="imagem2">

        <nav id="navegacao">
            <li>
                <lu class="item"><a href=" ../index.php"><b>Página Inicial</b></a></lu>
                <lu class="selected item"><b>Publicações</b></lu>
                <!--<lu id="item"><a href=""><b>Recrutamento</b></a></lu>-->
                <lu class="item"><a href="../_sobrenos/sobrenos.php"><b>Sobre Nós</b></a></lu>
            </li>
        </nav>

        <div id="conteudo">
            <?php
            echo '<script src="pub.js"></script>';
            $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/publicacao/read.php");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $pub = json_decode($response, true);

            $pub = array_reverse($pub);

            echo "<div id=\"publicacoes\">";

            if ($pub[0] != "No record found.") {
                foreach ($pub as $row) {
                    if ($row['IDPub'] == $idpub) {
                        $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/single_read.php?idperfil=" . $row['IDPerfil'] . "");
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $perfil = json_decode($response, true);

                        echo "<div class=\"publicacao\">";
                        //creates an eliminate button if the user id is the same as the logged in or if the user admin perms is 0 or 1
                        if (($_SESSION["idperfil"] == $row['IDPerfil'] || $_SESSION['perms'] <= 1)) {
                            echo '<input type="button" onclick="eliminar(' . $row['IDPub'] . ', \'' . $row['Imagem'] . '\')" style="float: right;" id="eliminar" value="Eliminar">';
                        }
                        echo "<a href=\"pub.php\" id=\"verpost\" style=\"float: right\">Voltar atrás</a>";
                        echo "<b style=\"font-size: 25px\">" . $row['Titulo'] . "</b></br>";
                        echo "<span>Publicado por <b style=\"color: white;\">" . $perfil['Nickname'] . "</b> em " . $row['Data'] . "</span></br></br>";
                        echo "<b>Descrição: </b><br>" . $row['Descricao'] . "<br>";
                        echo "<img src=\"imagem/" . $row['Imagem'] . "\" class=\"imagem\">";

                        echo '<button id="like" class="toggle-button" onclick="toggleButton(this, ' . $idperfil . ', ' . $idpub . ')"><i class="fa fa-thumbs-up"></i></button>';
                        echo '<span id="totalGosto">' . $totalGosto . '</span>';
                        echo '<button id="dislike" class="toggle-button" onclick="toggleButton(this, ' . $idperfil . ', ' . $idpub . ')"><i class="fa fa-thumbs-down"></i></button>';

                        echo '<script>if ('.$gosto. ' == 1)
                                     {
                                        document.getElementById("like").classList.toggle("select");
                                     } else if (' . $gosto . ' == -1)
                                     {
                                        document.getElementById("dislike").classList.toggle("select");
                                     }</script>';
                        echo "</div>";
                    }
                }
            }

            echo "</div>";
            echo "<div class=\"publicacao\">";
            echo "<b style=\"font-size: 25px\">Comentários</b></br>";
            echo "<form method=\"post\">";
                echo "<input type=\"text\" id=\"comentario\" name=\"comentario\" placeholder=\"Escreva um comentário...\">";
                echo "<input type=\"submit\" value=\"Comentar\" id=\"comentar\">";
            echo "</form>";

            //print all comentarios from this pub
            $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/comentarios/single_read.php?idpub=" . $idpub . "");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $comentarios = json_decode($response, true);
            $comentarios = array_reverse($comentarios);

            if ($comentarios[0] != "No record found.") {
                foreach ($comentarios as $row) {
                    $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/single_read.php?idperfil=" . $row['IDPerfil'] . "");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $perfil = json_decode($response, true);

                    //creates an eliminate button if the user id is the same as the logged in or if the user admin perms is 0 or 1
                    echo "</br></br><b id=\"nome\" style=\"color: gray;\">" . $perfil['Nickname'] . "</b></br>";
                    echo "<span style=\"color: white\">".$row['Comentario']."</span></br></br>";
                    if (($_SESSION["idperfil"] == $row['IDPerfil'] || $_SESSION['perms'] <= 1)) {
                        echo '<input type="button" onclick="eliminarComentario(' . $row['IDCom'] . ')" id="eliminar" value="Eliminar">';
                    }
                }
            }
            echo "</div>";

            ?>
        </div>
    </div>
</body>

</html>