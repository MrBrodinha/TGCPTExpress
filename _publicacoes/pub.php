<?php
session_start();
$titulo_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://neutrinoapi-bad-word-filter.p.rapidapi.com/bad-word-filter",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "censor-character=*&content=" . $_POST["titulo"] . " " . $_POST["texto"] . "",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: neutrinoapi-bad-word-filter.p.rapidapi.com",
            "X-RapidAPI-Key: f3fb2928b9mshea1141e9e9a087ep199939jsnebe0f303bf2c",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $isbadnick = json_decode($response, true);

    $filename = $_SESSION["idperfil"] . "_" . date("Y-m-d-H-i-s") . ".jpg";
    $tempname = $_FILES['imagem']['tmp_name'];
    $folder = "imagem/" . $filename;

    if (!$isbadnick["is-bad"]) {
        $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/publicacao/create.php");

        $data = array(
            'titulo' => $_POST["titulo"],
            'descricao' => $_POST["texto"],
            'imagem' => $filename,
            'data' => date("Y-m-d H:i:s"),
            'idperfil' => $_SESSION["idperfil"]
        );

        $jsonData = json_encode($data);
        // Create a new cURL resource
        // Set the cURL options
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Execute the request and get the response
        curl_exec($curl);
        // Close cURL resource
        curl_close($curl);
        move_uploaded_file($tempname, $folder);
        header("Location: pub.php");
    } else {
        $titulo_err = "Titulo ou Texto contem palavras não permitidas.";
    }
}
$err = [$titulo_err];
?>

<!DOCTYPE html>
<html>

<head>
    <title>TGC PT Express</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="pub.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
        <?php
        foreach ($err as $erro) {
            if (!empty($erro)) {
                echo "alert('" . $erro . "');";
            }
        }
        ?>
    </script>
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

        <dialog id="publicacao">
            <form method="POST" enctype="multipart/form-data">
                <div class="popup">
                    <label for="titulo"><b>Título</b></label></br>
                    <input type="text" id="titulo" name="titulo" placeholder="Título" required></br>
                    <label for="texto"><b>Descrição</b></label></br>
                    <textarea id="texto" name="texto" placeholder="Descrição"></textarea></br>
                    <label for="imagem"><b>Imagem</b></label></br>
                    <input type="file" id="imagem" name="imagem" required>
                </div>
                <input type="submit" value="Publicar" class="botao" id="1">
                <input type="button" value="Voltar Atrás" class="botao" id="2">
            </form>
        </dialog>
        <div id="conteudo">
            <?php
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
                echo "<a id=\"publicar\">Fazer Publicação</a>";


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
                    $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/single_read.php?idperfil=" . $row['IDPerfil'] . "");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $perfil = json_decode($response, true);

                    echo "<div class=\"publicacao\">";
                    //creates an eliminate button if the user id is the same as the logged in or if the user admin perms is 0 or 1
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && ($_SESSION["idperfil"] == $row['IDPerfil'] || $_SESSION['perms'] <= 1)) {
                        echo '<input type="button" onclick="eliminar(' . $row['IDPub'] . ', \'' . $row['Imagem'] . '\')" style="float: right;" id="eliminar" value="Eliminar">';
                    }
                    //creates a button that is titled "Ver post" and goes to single-pub.php with the id of the post
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
                        echo "<a href=\"single-pub.php?idpub=" . $row['IDPub'] . "\" id=\"verpost\" style=\"float: right\">Ver Post</a>";

                    echo "<b style=\"font-size: 25px\">" . $row['Titulo'] . "</b></br>";
                    echo "<span>Publicado por <b style=\"color: white;\">" . $perfil['Nickname'] . "</b> em " . $row['Data'] . "</span></br></br>";
                    echo "<b>Descrição: </b><br>" . $row['Descricao'] . "<br>";
                    echo "<img src=\"imagem/" . $row['Imagem'] . "\" class=\"imagem\">";
                    echo "</div>";
                }
            } else {
                echo "<div class=\"publicacao\">";
                echo "<b style=\"font-size: 25px\">Não existem publicações.</b>";
                echo "</div>";
            }

            echo "</div>";
            ?>
        </div>
    </div>
</body>

</html>