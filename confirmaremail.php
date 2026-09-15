<?php
session_start();
$titulopagina = "EPI Control - Recuperar senha";
$mensagemerro = "";
$classe_css = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? "");
    $email_confirm = trim($_POST['email_confirm'] ?? "");

    if (empty($email) || empty($email_confirm)) {
        $mensagemerro = "Preencha os dois campos de e-mail.";
        $classe_css = "erro";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagemerro = "Digite um e-mail válido.";
        $classe_css = "erro";
    } elseif ($email !== $email_confirm) {
        $mensagemerro = "Os e-mails não são iguais.";
        $classe_css = "erro";
    } else {
        require_once "config/conexao.php";
        $conexao = conectar();

        $stmt = mysqli_prepare($conexao, "SELECT id_funcionario FROM tb_funcionarios WHERE email=?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) !== 1) {
            $mensagemerro = "E-mail não encontrado.";
            $classe_css = "erro";
        } else {
            $codigo = str_pad((string) random_int(0, 999999), 6, "0", STR_PAD_LEFT);
            $expira_em = date("Y-m-d H:i:s", time() + 600);

            $delete = mysqli_prepare($conexao, "DELETE FROM recuperacao_senha WHERE email=?");
            mysqli_stmt_bind_param($delete, "s", $email);
            mysqli_stmt_execute($delete);
            mysqli_stmt_close($delete);

            $insert = mysqli_prepare($conexao, "INSERT INTO recuperacao_senha(email,codigo,expira_em) VALUES(?,?,?)");
            mysqli_stmt_bind_param($insert, "sss", $email, $codigo, $expira_em);
            mysqli_stmt_execute($insert);
            mysqli_stmt_close($insert);

            $_SESSION['email_recuperacao'] = $email;
            header("Location: codigo.php");
            exit;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexao);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo $titulopagina; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="pagina-login">
    <div class="container-login tela-login">
        <div class="login-conteudo">
            <div class="login-logo">
                <img src="img/logohome.png" alt="EPI Control">
            </div>
            <div class="titulo-login">
                <h1>Recuperar sua senha</h1>
                <p>Digite seu e-mail para renovar sua senha.</p>
            </div>
            <?php if (!empty($mensagemerro)) { ?>
                <div class="mensagem-erro <?php echo $classe_css; ?>">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $mensagemerro; ?>
                </div>
            <?php } ?>
            <div class="form-box">
                <form action="" method="POST">
                    <div class="input-box">
                        <span><i class="fa-solid fa-envelope"></i> E-mail</span>
                        <input type="email" name="email" placeholder="Digite seu e-mail" autocomplete="email" required>
                    </div>
                    <div class="input-box">
                        <span><i class="fa-solid fa-envelope-circle-check"></i> Confirmar e-mail</span>
                        <input type="email" name="email_confirm" placeholder="Confirme seu e-mail" autocomplete="email"
                            required>
                    </div>
                    <div class="remember">
                        <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Voltar para o login</a>
                    </div>
                    <div class="input-box">
                        <input type="submit" name="acao" value="Continuar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>