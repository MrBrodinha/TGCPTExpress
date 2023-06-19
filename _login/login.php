<?php
$email = $password = "";
$email_err = $password_err = "";

//Verifica se dados de login são válidos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["pass"]);

    $curl = curl_init("http://localhost/PWEB/TGCPTExpress/API/perfil/read.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($curl_response);

    foreach ($data as $row) {
        if ($row->Email == $email && password_verify($password, $row->Pass)) {
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["idperfil"] = $row->IDPerfil;
            $_SESSION["nickname"] = $row->Nickname;
            $_SESSION["email"] = $row->Email;
            $_SESSION["companhia"] = $row->Companhia;
            $_SESSION["perms"] = $row->Perms;
            $_SESSION["imagem"] = $row->Imagem;
            header("location: ../index.php");
        } else if ($row->Email != $email) {
            $email_err = "Email não está associado a nenhuma conta.";
        } else {
            $password_err = "Password incorreta.";
        }
    }

    $error = [$email_err, $password_err];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
</head>

<body>
    <a id="login" href="../index.php"><b>Página Principal</b></a>
    <div class="principal">
        <img src="../Imagens/Cavalo.png" id="imagem1">
        <img src="../Imagens/text.png" id="imagem2">
        <form method="POST" enctype="multipart/form-data">
            <div class="conteudo">
                <label for="email">E-mail</label></br>
                <input type="email" name="email" id="email" required></br>
                <label for="pass">Password</label></br>
                <input type="Password" name="pass" id="pass" required></br>
                <a href="registo.php" id="registo">Não tem conta? Faça registo agora mesmo!</a></td></br>
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
            <input type="submit" value="Login" id="botao">
        </form>
    </div>
</body>

</html>