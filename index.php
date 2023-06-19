<!DOCTYPE html>
<html>

<head>
    <title>TGC PT Express</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="index.js"></script>
</head>

<body>
    <div id="perfil">
    <?php
    session_start();
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        echo
        "<a id=\"login\" href=\"_perfil/perfil.php\">
            <img src=\"_perfil/pfp/" . $_SESSION["imagem"] . "\">
        </a>";
    } else {
        echo
        "<a id=\"login\" href=\"_login/login.php\">
            <img src=\"Imagens/login.png\">
        </a>";
    }
    ?>
    </div>
    <div class="centro">
        <img src="Imagens/Cavalo.png" id="imagem1">
        <img src="Imagens/text.png" id="imagem2">

        <nav id="navegacao">
            <li>
                <lu class="selected item"><b>Página Inicial</b></lu>
                <lu class="item"><a href="_publicacoes/pub.php"><b>Publicações</b></a></lu>
                <!--<lu id="item"><a href=""><b>Recrutamento</b></a></lu>-->
                <lu class="item"><a href="_sobrenos/sobrenos.php"><b>Sobre Nós</b></a></lu>
            </li>
        </nav>

        <div id="conteudo">
            <p>Bem-vindo à página oficial da TGC PT Express!</p>
            <p>Somos uma Virtual Trucking Company, que opera no Euro Truck Simulator 2 e no American Truck Simulator.</p>
            <p>Para mais informações, visita a nossa página de Sobre Nós.</p>
            <p>Se queres partilhar as tuas viagens, partilha na nossa página de Publicações.</p>
            </br>
            <hr>
            <p>Nossas redes sociais:</p>
            <a href="https://www.facebook.com/TGCPTExpress" target="_blank"><img src="Imagens/facebook.png" id="icon"></a>
            <a href="https://www.instagram.com/tgcptexpress/" target="_blank"><img src="Imagens/instagram.png" id="icon"></a>
            <br>
            <p>Junta-te a nós!</p>
            <a href="https://pickupvtm.com/company/70"><img src="Imagens/pickup.png" id="icon"></a>
            <a href="https://trucksbook.eu/company/58433"><img src="Imagens/truckbook.png" id="icon"></a>
        </div>
    </div>
</body>

</html>