<!DOCTYPE html>
<html>

<head>
    <title>TGC PT Express</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="perfil.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="perfil.js"></script>
</head>

<body>
    <a id="login" href="../index.php"><b>Página Principal</b></a>
    <div class="centro">
        <img src="../Imagens/Cavalo.png" id="imagem1">
        <img src="../Imagens/text.png" id="imagem2">

        <div id="conteudo">
            <?php
            session_start();
            echo "<h1 id=\"centrar\">Olá " . $_SESSION["nickname"] . " :)</h1>
                  <img src='pfp/" . $_SESSION["imagem"] . "' id=\"pfp\">";
            echo "<b>E-mail:</b></br><input type=\"text\" value=\"" . $_SESSION["email"] . "\" readonly id=\"text-field\"></br></br>
                  <b>Companhia:</b></br><input type=\"text\" value=\"" . $_SESSION["companhia"] . "\" readonly id=\"text-field\"></br></br>";
            ?>
        </div>
        <div id="botoes">
            <a href="edit_delete/editar.php">Editar</a>
            <a href="../_login/logout.php">Logout</a>
            <a href="edit_delete/apagar.php">Eliminar</a>
        </div>
        <?php
        $id = $_SESSION["idperfil"];

        $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/publicacao/single_read.php?idperfil=" . $id . "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $pub = json_decode($response, true);
        $pub = array_reverse($pub);

        echo "<div id=\"publicacoes\">";

        $IDB = array();

        if ($pub[0] != "No record found.") {
            foreach ($pub as $row) {
                echo "<div class=\"publicacao\">";
                echo '<input type="button" onclick="eliminar(' . $row['IDPub'] . ', \'' . $row['Imagem'] . '\')" style="float: right;" id="eliminar" value="Eliminar">';
                echo "<b style=\"font-size: 25px\">" . $row['Titulo'] . "</b><br>";
                echo "<span>Publicado em " . $row['Data'] . "</span><br><br>";
                echo "<b>Descrição: </b><br>" . $row['Descricao'] . "<br>";
                echo "<img src=\"../_publicacoes/imagem/" . $row['Imagem'] . "\" class=\"imagem\">";
                echo "</div>";
            }
        } else {
            echo "<div class=\"publicacao\">";
            echo "<b style=\"font-size: 25px\">Não existem publicações.</b>";
            echo "</div>";
        }

        echo "</div>";
        ?>
        <script src="perfil.js"></script>
    </div>
</body>

</html>