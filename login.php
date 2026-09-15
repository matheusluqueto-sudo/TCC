<?php
session_start();
$titulopagina = "EPI Control - Login";
require_once "config/conexao.php";
$mensagemerro = "";
$classe_css = "";
$usuario = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['usuario'], $_POST['senha'], $_POST['acao']) && $_POST['acao'] === 'Entrar') {
        $usuario = trim($_POST['usuario']);
        $senha = $_POST['senha'];
        if (!empty($usuario) && !empty($senha)) {
            $conexao = conectar();
            $sql = "SELECT * FROM tb_funcionarios WHERE email=?";
            $stmt = mysqli_prepare($conexao, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $usuario);
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) === 1) {
                    $usuarioPesquisado = mysqli_fetch_assoc($resultado);
                    if ($usuarioPesquisado['senha'] === $senha) {
                        $_SESSION['id_funcionario'] = $usuarioPesquisado['id_funcionario'];
                        $_SESSION['nome_completo'] = $usuarioPesquisado['nome_completo'];
                        $_SESSION['email'] = $usuarioPesquisado['email'];
                        $_SESSION['nivel_acesso'] = $usuarioPesquisado['nivel_acesso'];
                        if ($_SESSION['nivel_acesso'] === "Administrador") {
                            header("Location: home.php");
                            exit;
                        }
                        if ($_SESSION['nivel_acesso'] === "Funcionario") {
                            header("Location: homefunc.php");
                            exit;
                        }
                        header("Location: error.php");
                        exit;
                    } else {
                        $mensagemerro = "Usuário ou senha inválidos.";
                        $classe_css = "erro";
                    }
                } else {
                    $mensagemerro = "Usuário ou senha inválidos.";
                    $classe_css = "erro";
                }
                mysqli_stmt_close($stmt);
            } else {
                $mensagemerro = "Erro ao preparar a consulta.";
                $classe_css = "erro";
            }
            mysqli_close($conexao);
        } else {
            $mensagemerro = "Preencha o Usuário e a Senha.";
            $classe_css = "erro";
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body class="pagina-login">
    <div class="container-login tela-login">
        <div class="login-conteudo">
            <div class="login-logo">
                <img src="img/logohome.png" alt="EPI Control">
            </div>
            <div class="titulo-login">
                <h1>Entrar na sua conta</h1>
                <p>Acesse o EPI Control ou <a href="cadastro.php">faça seu cadastro</a></p>
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
                        <input type="email" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>"
                            placeholder="Digite seu e-mail" autocomplete="email" required>
                    </div>
                    <div class="input-box senha-box">
                        <span>Senha</span>
                        <div class="campo-senha">
                            <input type="password" name="senha" id="senha" placeholder="Digite sua senha" required>
                            <button type="button" id="mostrarSenha" aria-label="Mostrar senha">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="remember">
                        <span></span>
                        <a href="confirmaremail.php">Esqueceu a senha?</a>
                    </div>
                    <div class="input-box">
                        <input type="submit" name="acao" value="Entrar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const senha = document.getElementById("senha"), botao = document.getElementById("mostrarSenha");
        botao.addEventListener("click", function () { const visivel = senha.type === "text"; senha.type = visivel ? "password" : "text"; this.innerHTML = visivel ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>'; });
    </script>
</body>

</html>