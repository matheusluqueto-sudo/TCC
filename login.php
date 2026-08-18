<?php

$titulopagina = "EPI Control - Login";

require_once "config/conexao.php";

$mensagemerro = "";
$classe_css = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST['usuario'], $_POST['senha'], $_POST['acao']) &&
        $_POST['acao'] == 'Entrar'
    ) {

        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        if (!empty($usuario) && !empty($senha)) {

            $conexao = conectar();

            $sql = 'SELECT * FROM usuario WHERE nomeUsuario = ?';

            $stmt = mysqli_prepare($conexao, $sql);

            mysqli_stmt_bind_param($stmt, "s", $usuario);

            mysqli_stmt_execute($stmt);

            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) == 1) {

                $usuarioPesquisado = mysqli_fetch_assoc($resultado);

                if ($usuarioPesquisado['senhaUsuario'] === $senha) {

                    session_start();

                    $_SESSION['usuariologado'] = $usuario;

                    header('Location: home.php');

                    exit;
                } else {

                    $mensagemerro = "Usuário/Senha inválidos.";

                    $classe_css = "erro";
                }
            } else {

                $mensagemerro = "Usuário/Senha inválidos.";

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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $titulopagina; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="container-login">

        <!-- ========================================
             LADO ESQUERDO - IMAGEM
        ========================================= -->

        <div class="img-box">

            <img
                src="img/logo.png"
                alt="EPI Control - Gestão e Segurança">

        </div>


        <!-- ========================================
             LADO DIREITO - LOGIN
        ========================================= -->

        <div class="content-box">

            <div class="form-box">


                <!-- ========================================
                     TÍTULO
                ========================================= -->

                <div class="titulo-login">

                    <h1>Bem-vindo!</h1>

                    <p>Acesse sua conta para continuar</p>

                </div>


                <!-- ========================================
                     MENSAGEM DE ERRO
                ========================================= -->

                <?php if (!empty($mensagemerro)) { ?>

                    <div class="mensagem-erro <?php echo $classe_css; ?>">

                        <?php echo $mensagemerro; ?>

                    </div>

                <?php } ?>


                <!-- ========================================
                     FORMULÁRIO
                ========================================= -->

                <form action="" method="POST">


                    <!-- USUÁRIO -->

                    <div class="input-box">

                        <span>Usuário</span>

                        <input
                            type="email"
                            name="usuario"
                            placeholder="@gmail.com"
                            required>

                    </div>


                    <!-- SENHA -->

                    <div class="input-box">

                        <span>Senha</span>

                        <input
                            type="password"
                            name="senha"
                            placeholder="Digite sua senha"
                            required>

                    </div>


                    <!-- ========================================
                         LEMBRAR / ESQUECEU
                    ========================================= -->

                    <div class="remember">

                        <label>

                            <input
                                type="checkbox"
                                name="lembrar">

                            Lembre-me

                        </label>


                        <a href="esqueceusenha.php">
                            Esqueceu a senha?
                        </a>

                    </div>


                    <!-- ========================================
                         BOTÃO ENTRAR
                    ========================================= -->

                    <div class="input-box">

                        <input
                            type="submit"
                            name="acao"
                            value="Entrar">

                    </div>


                    <!-- ========================================
                         CADASTRO
                    ========================================= -->

                </form>
            </div>

        </div>

    </div>

</body>

</html>