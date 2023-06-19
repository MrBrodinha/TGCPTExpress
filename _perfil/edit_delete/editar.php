<?php
session_start();

$id = $_SESSION["idperfil"];
$stop = false;

$nickname = $password = $email = "";
$nickname_err = $password_err = $email_err = "";

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
        CURLOPT_POSTFIELDS => "censor-character=*&content=" . $_POST["nick"] . " " . $_POST["companhia"]. "",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: neutrinoapi-bad-word-filter.p.rapidapi.com",
            "X-RapidAPI-Key: f3fb2928b9mshea1141e9e9a087ep199939jsnebe0f303bf2c",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $isbadnick = json_decode($response, true);

    //Nickname
    $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/read.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($curl_response);


    if (!$isbadnick["is-bad"]) {
        foreach ($data as $row) {
            if ($row->IDPerfil != $id) {
                if ($row->Nickname == $_POST["nick"]) {
                    $nickname_err = "Este Nickname já foi escolhido.";
                    $stop = true;
                }

                if ($row->Email == $_POST["email"]) {
                    $email_err = "Esta conta de email já está associada a uma conta.";
                    $stop = true;
                }
            }
        }
    } else {
        $nickname_err = "Não pode inserir palavras não permitidas.";
        $stop = true;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["nick"]))) {
        $nickname_err = "Nickname apenas pode conter Letras, Numeros e _.";
        $stop = true;
    }


    if (strlen(trim($_POST["pass"])) < 6) {
        $pass_err = "Password tem que ter pelo menos 6 caracteres.";
        $stop = true;
    } else {
        $pass = password_hash(trim($_POST["pass"]), PASSWORD_DEFAULT);
    }

    if(empty(trim($_POST["companhia"]))){
        $companhia = "";
    }
    else {
        $companhia = trim($_POST["companhia"]);
    }


    //Imagem
    if(!$stop) {
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $folderPath = '../pfp/';
            $pattern = $folderPath . $_SESSION['nickname'] . '.jpg';
            $files = glob($pattern);
            foreach ($files as $file) {
                unlink($file);
            }

            $filename = $_POST['nick'] . ".jpg";

            $tempname = $_FILES["imagem"]["tmp_name"];
            $folder = "../pfp/" . $filename;
            $imagem_stop = false;
        } else {
            rename("../pfp/" . $_SESSION["imagem"], "../pfp/" . $_POST["nick"] . ".jpg");
            $filename = $_POST["nick"] . ".jpg";
            $imagem_stop = true;
        }
    }

    if (!$stop) {
        $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/update.php");

        $data = array
        ('idperfil' => $id, 
        'nickname' => $_POST["nick"], 
        'email' => $_POST["email"], 
        'pass' => $pass, 
        'companhia' => $companhia,
        'perms' => $_SESSION["perms"], 
        'imagem' => $filename);

        $data = json_encode($data);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_response = curl_exec($curl);
        curl_close($curl);

        echo $curl_response;

        //move image
        if (!$imagem_stop) {
            move_uploaded_file($tempname, $folder);
        }

        $curl_response = json_decode($curl_response, true);

        if ($curl_response == "Perfil data updated.") {
            $_SESSION["nickname"] = $_POST["nick"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["companhia"] = $companhia;
            $_SESSION["imagem"] = $filename;
            header("Location: ../perfil.php");
        } else {
            $nickname_err = "Erro ao atualizar perfil.";
        }
    }
}

$err = [$nickname_err, $password_err, $email_err];
?>
<!DOCTYPE html>
<html>

<head>
    <title>TGC PT Express</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
    <div class="centro">
        <img src="../../Imagens/Cavalo.png" id="imagem1">
        <img src="../../Imagens/text.png" id="imagem2">
        <form method="POST" enctype="multipart/form-data">
            <div id="conteudo">
                <b>Nickname:</b></br><input type="text" value="<?php echo $_SESSION["nickname"] ?>" id="nick" name="nick" required></br></br>
                <b>Password:</b></br><input type="password" value="" id="pass" name="pass" required></br></br>
                <b>E-mail:</b></br><input type="email" value="<?php echo $_SESSION["email"] ?>" id="email" name="email" required></br></br>
                <b>Companhia:</b></br><input type="text" value="<?php echo $_SESSION["companhia"] ?>" id="companhia" name="companhia"></br></br>
                <b>Imagem:</b></br><input type="file" id="imagem" name="imagem"></br></br>

                <?php
                if (!empty($err)) {
                    foreach ($err as $erro) {
                        if (!empty($erro))
                            echo "<p id=\"erro\">" . $erro . "</p>";
                    }
                }
                ?>
            </div>
            <div id="botoes">
                <a href="../perfil.php">Voltar Atrás</a>
                <input type="submit" value="Confirmar" id="botao">
            </div>
        </form>
    </div>
</body>
</html>