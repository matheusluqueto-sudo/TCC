<?php

$titulopagina = "EPI Control - Cadastro";

$mensagemerro = "";
$classe_css = "";

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
             LADO DIREITO - CADASTRO
        ========================================= -->

        <div class="content-box">

            <div class="form-box cadastro-form">


                <!-- ========================================
                     TÍTULO
                ========================================= -->

                <div class="titulo-login">

                    <h1>Cadastre a Conta!</h1>

                    <p>Cadastre-se para começar a usar o EPI Control</p>

                </div>

                <!-- ========================================
                     MENSAGEM
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


                    <!-- ========================================
                         NOME
                    ========================================= -->

                    <div class="input-box">

                        <span>Nome</span>

                        <input
                            type="text"
                            name="nome"
                            placeholder="Digite seu nome"
                            required>

                    </div>


                    <!-- ========================================
                         EMAIL
                    ========================================= -->

                    <div class="input-box">

                        <span>Email</span>

                        <input
                            type="email"
                            name="email"
                            placeholder="@gmail.com"
                            required>
                    </div>


                    <!-- ========================================
                         SENHA
                    ========================================= -->

                    <div class="input-box">
                        <span>Senha/Temporária</span>

                        <input
                            type="password"
                            name="senha"
                            placeholder="Digite sua senha"
                            required>
                    </div>

                      <div class="input-box">
                        <span>CPF</span>

                        <input
                            type="text"
                            name="cpf"
                            placeholder="Digite seu CPF"
                            required>
                    </div>



                    <!-- ========================================
                         CARGO
                    ========================================= -->

                    <div class="input-box">

                        <span>Cargo</span>
                        <select
                            name="cargo"
                            required>

                            <option value="" disabled selected>
                                Selecione seu cargo
                            </option>

                            <option value="Funcionario">
                                Funcionário
                            </option>

                            <option value="Supervisor">
                                Supervisor
                            </option>

                            <option value="Gerente">
                                Gerente
                            </option>

                            <option value="Administrador">
                                Administrador
                            </option>

                        </select>

                    </div>


                    <!-- ========================================
                         BOTÃO CADASTRAR
                    ========================================= -->

                    <div class="input-box">
                        <input
                            type="submit"
                            name="acao"
                            value="Cadastrar">
                    </div>

                    <!-- ========================================
                         VOLTAR PARA LOGIN
                    ========================================= -->

                    <div class="input-box cadastro">
                        <p>
                            Já possui uma conta?
                            <a href="login.php">
                                Entrar
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>