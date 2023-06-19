<!DOCTYPE html>
<html>

<head>
    <title>TGC PT Express</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="sobrenos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src=" https://player.twitch.tv/js/embed/v1.js"></script>
    <script src="sobrenos.js"></script>
</head>

<body>
    <div id="perfil">
        <?php
        session_start();
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
                <lu class="item"><a href="../_publicacoes/pub.php"><b>Publicações</b></a></lu>
                <!--<lu id="item"><a href=""><b>Recrutamento</b></a></lu>-->
                <lu class="selected item"><b>Sobre Nós</b></a></lu>
            </li>
        </nav>

        <div id="conteudo">
            <h1>Quem somos?</h1>
            <hr>
            <p>A TGC PT Express é uma VTC (Virtual Trucking Company) Portuguesa que está no TrucksBook desde Novembro 2019 e no Pickup deste Setembro 2020.</p>
            <p>Somos a continuação do projecto TGC Logistics Portugal iniciado em 2017 pelo streamer LadSon Lair que nascera do projecto Team Gang do Careca criado em 2013 pelo grande Tuga Daves.</p>
            <p>Queremos ser muito mais que uma simples VTC para registo de Kms. O nosso objectivo é agregar todos aqueles de língua Portuguesa que queiram apoiar a divulgação dos jogos da SCS Software
                (criadora do ETS2 entre outros) e ao mesmo tempo procurem uma empresa para fazer Kms e divertirem-se. Se te revês nisto então a TGC PT Express é a tua melhor opção. Vai à secção de Recrutamento
                e um dos nossos Recrutadores de empresa entrará em contacto contigo para dares inicio ao processo de recrutamento.
            </p>
            <p>Esperamos que te divirtas, assim como nós nos esperamos divertir. Bons Kms!</p>
            <p><b>Born To Truck! Forever Trucking!</b></p>
            </br>
            </br>
            </br>
            <h1>Membros da Companhia TGC PT Express</h1>
            <hr>
            <table id="membros">
                <tr style="background-color: #333333;">
                    <td><b>Nickname</b></td>
                    <td><b>Cargo</b></td>
                </tr>
                <?php
                //Vai buscar HTML do website do Truckbooks da TGC
                $html = file_get_contents('https://trucksbook.eu/company/58433');

                $doc = new DOMDocument();
                @$doc->loadHTML($html);

                //Os nicknames estão guardados com esta tag
                $links = $doc->getElementsByTagName('a');

                //Os cargos estão guardados com esta tag
                $spanElements = $doc->getElementsByTagName('span');

                //Guardo os cargos numa array
                foreach ($spanElements as $span) {
                    $class = $span->getAttribute('class');
                    if (strpos($class, 'text-end flex-grow-1') !== false) {
                        $textContentElements[] = $span->nodeValue;
                    }
                }

                //Imprimo os nicknames e os cargos com cores alternadas
                $style = "style = \"background-color: #555555;\"";
                $i = 0;
                foreach ($links as $link) {
                    $href = $link->getAttribute('href');
                    if (strpos($href, '/profile/') !== false) {
                        echo "<tr " . $style . "><td>" . $link->nodeValue . "</td><td>" . $textContentElements[$i] . "</td></tr>";
                        $i++;

                        if ($i % 2 == 0) {
                            $style = "style = \"background-color: #555555;\"";
                        } else {
                            $style = "style = \"background-color: #808080;\"";
                        }
                    }
                }
                ?>
                <tr style="border-bottom: 0;">
                    <td colspan=" 2" style="text-align: center;"><input type="button" id="pdf" value="Gerar PDF" onclick="generatePDF()" style="text-align: center;"></td>
                </tr>
            </table>

            </br>
            </br>
            </br>
            <h1>História TGC</h1>
            <hr>
            <button id="musica" value="Play" onclick="playMusic()">
                <i id="icon" class="fa fa-play"></i>
            </button>
            <i id="autor"> uma musiquinha para acompanhar a leitura ;)</i>
            </br>
            <p id="autor">(Texto escrito pelo owner atual, Gonçalo_Gaming)</p>
            <p>Querem um texto bonito sobre tudo que se passa na TGC?</p>
            <p>Muito podia dizer sobre isto, coisas boas, coisas más, mas tudo se resume a uma palavra... SONHOS!</p>
            <p>A vida é feita de sonhos e constroi-se sobre esses sonhos.
                O TugaDaves teve um sonho lindo, juntar as pessoas que se identificavam com o ETS2 e este tipo de jogos e ao mesmo tempo
                levantar os ânimos que tão maltratados andam nestes últimos anos em Portugal. Muitos tiveram que emigrar à procura de condições
                melhores no estrangeiro por terras estranhas, outros passam dificuldades atrozes em Portugal, mas algo une estes 2 grupos, o sonho
                de uma vida melhor, os do Estrangeiro para proteger os filhos ou outros, os que ficaram porque acham que não devemos nunca abandonar
                os sonhos, que neste caso é Portugal.</p>
            <p>Nascemos como nação em 1143 e desde então temos tido um dos vizinhos mais poderosos e bélicos do
                mundo, mas conseguimos sempre, umas vezes melhor, outras pior, sobreviver e chegar onde estamos hoje.</p>
            <p>Não somos muitos, não somos grandes
                e acima de tudo nos tempos que correm não somos ricos nem poderosos... mas somos Portugueses. Algo tão estranho de explicar que ninguém
                consegue explicar, como vivemos, como sobrevivemos e porque continuamos a apoiar os nossos sonhos pequeninos mas enormes de valor.</p>
            <p>Todos que já passaram na TGC, atual ou no passado acharam que iriam ter algo melhor ao sair, e tiveram, pois os sonhos devem reger a nossa
                vida e não é por isso que nos vamos acanhar nem diminuir, e para esses eu desejo todas as felicidades do mundo.</p>
            <p>Para os que ficam ou que entrem no futuro o que podem contar é que o Sonho que fez esta empresa nascer e crescer não vai morrer nunca... pois esse sonho existe
                dentro de cada um de nós, quando sentimos aquele sentimento único nosso, a SAUDADE de Portugal e de alguém ou algo que possamos ter neste
                jardim à beira-mar plantado. Eu tenho relações muito grandes com muita gente no estrangeiro, profissionais e de amizade, mas não consigo,
                por muito que já tenha tentado deixar de apoiar a Comunidade Portuguesa.</p>
            <p>Posso ter muitos defeitos como qualquer um, mas acho que o meu maior defeito é o Amor que sinto a Portugal e acreditem que a TGC é muito mais que uma VTC, é um grupo de pessoas que acreditam que podemos
                ser melhores como pessoas, como grupo, como nação.</p>
            <p>Quando querem os Portugueses são ENORMES!!! Esta é apenas uma pequena dissertação sobre
                algo que já falei muito em Lives mas que de certeza alguns se identificam senão não estariam aqui... em meu nome e em nome do TugDaves um
                MUITO OBRIGADO por acreditarem! Abraço!</p>
            <p><b>BORN TO TRUCK, FOREVER TRUCKING!</b></p>
            <p>...e já agora, façam sempre alguém Feliz!!!</p>
            </br>
            </br>
            <i id="autor">(Mais um video sobre as origens da TGC PT Express)</i>
            <div id="twitch-embed"></div>
            <script type="text/javascript">
                new Twitch.Player("twitch-embed", {
                    video: "638013750",
                    autoplay: false,
                    width: 854,
                    height: 480
                });
            </script>
        </div>
    </div>
</body>

</html>