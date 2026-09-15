<?php
session_start();
$titulopagina = "EPI Control - Nova Senha";
$mensagemerro = "";
$classe_css = "";
$email = $_SESSION['email_recuperacao'] ?? "";

if (empty($email)) {
    header("Location: confirmaremail.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senha = trim($_POST['senha'] ?? "");
    $senha_confirm = trim($_POST['senha_confirm'] ?? "");

    if (empty($senha) || empty($senha_confirm)) {
        $mensagemerro = "Preencha os dois campos de senha.";
        $classe_css = "erro";
    } elseif (strlen($senha) < 6) {
        $mensagemerro = "A senha deve ter pelo menos 6 caracteres.";
        $classe_css = "erro";
    } elseif ($senha !== $senha_confirm) {
        $mensagemerro = "As senhas não são iguais.";
        $classe_css = "erro";
    } else {
        require_once "config/conexao.php";
        $conexao = conectar();

        $stmt = mysqli_prepare($conexao, "UPDATE tb_funcionarios SET senha=? WHERE email=?");
        mysqli_stmt_bind_param($stmt, "ss", $senha, $email);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            $delete = mysqli_prepare($conexao, "DELETE FROM recuperacao_senha WHERE email=?");
            mysqli_stmt_bind_param($delete, "s", $email);
            mysqli_stmt_execute($delete);
            mysqli_stmt_close($delete);

            unset($_SESSION['email_recuperacao']);
            mysqli_close($conexao);

            header("Location: login.php");
            exit;
        } else {
            $mensagemerro = "Não foi possível alterar a senha.";
            $classe_css = "erro";
            mysqli_stmt_close($stmt);
            mysqli_close($conexao);
        }
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
                <h1>Crie uma nova senha</h1>
                <p>Digite sua nova senha para recuperar o acesso à sua conta.</p>
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
                        <span><i class="fa-solid fa-lock"></i> Nova senha</span>
                        <div class="campo-senha">
                            <input type="password" id="senha" name="senha" placeholder="Digite sua nova senha"
                                autocomplete="new-password" required>
                            <button type="button" id="mostrarSenha" aria-label="Mostrar senha"><i
                                    class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="input-box">
                        <span><i class="fa-solid fa-lock"></i> Confirmar nova senha</span>
                        <div class="campo-senha">
                            <input type="password" id="senha_confirm" name="senha_confirm"
                                placeholder="Confirme sua nova senha" autocomplete="new-password" required>
                            <button type="button" id="mostrarSenhaConfirm" aria-label="Mostrar senha"><i
                                    class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="remember">
                        <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Voltar para o login</a>
                    </div>
                    <div class="input-box">
                        <input type="submit" name="acao" value="Alterar senha">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/esqueceusenha.js"></script>
</body>

</html>