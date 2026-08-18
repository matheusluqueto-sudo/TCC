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

                    <h1>Digite seu email!</h1>

                    <p>Digite seu email para renovar senha e usar o EPI Control</p>

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

                        <span>Confirme seu Email</span>

                        <input
                            type="email"
                            name="email_confirm"
                            placeholder="@gmail.com"
                            required>


                    </div>

                    <br>
                    <!-- ========================================
                         CARGO
                    ========================================= -->
                

                    <!-- ========================================
                         BOTÃO CADASTRAR
                    ========================================= -->

                    <div class="input-box">

                        <input
                            type="submit"
                            name="acao"
                            value="Continuar" href="codigo.php">

                    </div>


                    <!-- ========================================
                         VOLTAR PARA LOGIN  
                    ========================================= -->

                </form>


                <!-- ========================================
                     LOGIN SOCIAL
                ========================================= -->



            </div>

        </div>

    </div>

</body>

</html>