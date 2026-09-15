<?php
session_start();
$titulopagina = "EPI Control - Confirmar Código";
$mensagemerro = "";
$classe_css = "";
$email = $_SESSION['email_recuperacao'] ?? "";

if (empty($email)) {
    header("Location: confirmaremail.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = "";
    for ($i = 1; $i <= 6; $i++) {
        $codigo .= $_POST["codigo$i"] ?? "";
    }

    if (strlen($codigo) !== 6 || !ctype_digit($codigo)) {
        $mensagemerro = "Digite o código de 6 números.";
        $classe_css = "erro";
    } else {
        require_once "config/conexao.php";
        $conexao = conectar();

        $stmt = mysqli_prepare($conexao, "SELECT id,codigo,expira_em FROM recuperacao_senha WHERE email=? ORDER BY id DESC LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) !== 1) {
            $mensagemerro = "Código não encontrado. Solicite um novo código.";
            $classe_css = "erro";
        } else {
            $dados = mysqli_fetch_assoc($resultado);

            if ($dados['codigo'] !== $codigo) {
                $mensagemerro = "Código incorreto.";
                $classe_css = "erro";
            } elseif (strtotime($dados['expira_em']) < time()) {
                $mensagemerro = "O código expirou. Solicite um novo código.";
                $classe_css = "erro";
            } else {
                $_SESSION['codigo_verificado'] = true;
                header("Location: esqueceusenha.php");
                exit;
            }
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
            <div class="titulo-login codigo-titulo">
                <h1>Verifique seu e-mail</h1>
                <p>Enviamos um código de confirmação para o seu e-mail.</p>
            </div>
            <?php if (!empty($mensagemerro)) { ?>
                <div class="mensagem-erro <?php echo $classe_css; ?>">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $mensagemerro; ?>
                </div>
            <?php } ?>
            <div class="form-box codigo-form">
                <div class="codigo-imagem">
                    <img src="img/o-email.png" alt="Confirmação de e-mail">
                </div>
                <form action="" method="POST">
                    <div class="codigo-container">
                        <input type="text" name="codigo1" maxlength="1" inputmode="numeric" autocomplete="one-time-code"
                            required>
                        <input type="text" name="codigo2" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="codigo3" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="codigo4" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="codigo5" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="codigo6" maxlength="1" inputmode="numeric" required>
                    </div>
                    <div class="reenviar-codigo">
                        <span>Não recebeu o código?</span>
                        <a href="#" id="reenviarCodigo">Reenviar código</a>
                    </div>
                    <div id="mensagemReenvio" class="mensagem-reenvio">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Novo código enviado para <?php echo htmlspecialchars($email); ?>.</span>
                    </div>
                    <div class="input-box codigo-botao">
                        <input type="submit" name="acao" value="Verificar código">
                    </div>
                    <div class="voltar-codigo">
                        <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Voltar para o login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/codigo.js"></script>
</body>

</html>