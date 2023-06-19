<?php
$nickname = $pass = $email =  "";
$nickname_err = $pass_err = $email_err  = $imagem_err = "";

//guarda idperfil para depois usar no nome da imagem
$idperfil = 0;
$stop = false;

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
        CURLOPT_POSTFIELDS => "censor-character=*&content=" . $_POST["nick"] . "",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: neutrinoapi-bad-word-filter.p.rapidapi.com",
            "X-RapidAPI-Key: f3fb2928b9mshea1141e9e9a087ep199939jsnebe0f303bf2c",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $isbadnick = json_decode($response, true);

    $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/read.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($curl_response);


    if (!$isbadnick["is-bad"]) {
        foreach ($data as $row) {
            if ($row->Nickname == $_POST["nick"]) {
                $nickname_err = "Este Nickname já foi escolhido.";
                $stop = true;
            }

            if ($row->Email == $_POST["email"]) {
                $email_err = "Esta conta de email já está associada a uma conta.";
                $stop = true;
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

    $filename = $_POST["nick"] . ".jpg";
    $tempname = $_FILES['imagem']['tmp_name'];
    $folder = "../_perfil/pfp/" . $filename;

    if (!move_uploaded_file($tempname, $folder)) {
        $imagem_err = "Erro ao enviar imagem";
        $stop = true;
    }

    if (!$stop) {
        $url = 'http://localhost/PWEB/TGCPTExpress/API/perfil/create.php';

        // Data to be sent in the request body
        $data = array(
            'nickname' => $_POST["nick"],
            'email' => $_POST["email"],
            'pass' => $pass,
            'companhia' => $_POST["companhia"],
            'imagem' => $filename,
            'perms' => 2
        );

        $jsonData = json_encode($data);

        // Create a new cURL resource
        $curl = curl_init($url);

        // Set the cURL options
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        // Close cURL resource
        curl_close($curl);

        header("Location: login.php");
    }

    $error = [$nickname_err, $email_err, $pass_err, $imagem_err];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registo</title>
    <link rel="stylesheet" href="registo.css">
    <meta charset="UTF-8">
</head>

<body>
    <a id="login" href="../index.php"><b>Página Principal</b></a>
    <div class="principal">
        <img src="../Imagens/Cavalo.png" id="imagem1">
        <img src="../Imagens/text.png" id="imagem2">
        <form method="POST" enctype="multipart/form-data">
            <div class="conteudo">
                <label for="nick">Nickname</label></br>
                <input type="text" name="nick" id="nick" maxlength="10" required></br>
                <label for="email">E-mail</label></br>
                <input type="email" name="email" id="email" required></br>
                <label for="pass">Password</label></br>
                <input type="Password" name="pass" id="pass" required></br>
                <label for="companhia">Companhia (opcional)</label></br>
                <input type="text" name="companhia" id="companhia"></br>
                <label for="imagem">Imagem de Perfil</label></br>
                <input type="file" name="imagem" id="imagem" required></br>
                <a href="login.php" id="registo">Já tem conta? Faça Login agora mesmo!</a></td></br>
                <?php
                if (!empty($error)) {
                    foreach ($error as $erro) {
                        if (!empty($erro))
                            echo "<span id=\"erro\">" . $erro . "</span></br>";
                    }
                }
                ?>
            </div>
            </br>
            <input type="submit" value="Registar" id="botao">
        </form>
    </div>
</body>

</html>