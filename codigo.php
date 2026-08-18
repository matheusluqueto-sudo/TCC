<?php

$titulopagina = "EPI Control - Confirmar Código";

$mensagemerro = "";
$classe_css = "";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $titulopagina; ?></title>

    <link rel="stylesheet" href="css/style.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="container-login">


        <!-- ========================================
             LADO ESQUERDO - LOGO
        ========================================= -->

        <div class="img-box">

            <img
                src="img/logo.png"
                alt="EPI Control - Gestão e Segurança">

        </div>


        <!-- ========================================
             LADO DIREITO
        ========================================= -->

        <div class="content-box">

            <div class="form-box codigo-form">


                <!-- ========================================
                     IMAGEM DE CONFIRMAÇÃO
                ========================================= -->

                <div class="codigo-imagem">

                    <!-- Troque pelo nome da imagem que você salvou na pasta img -->

                    <img
                        src="img/o-email.png"
                        alt="Confirmação de email">

                </div>


                <!-- ========================================
                     TÍTULO
                ========================================= -->

                <div class="titulo-login codigo-titulo">

                    <h1>Verifique seu email</h1>

                    <p>
                        Enviamos um código de confirmação
                        para o seu email.
                    </p>

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


                    <!-- ========================================
                         CÓDIGO
                    ========================================= -->

                    <div class="codigo-container">

                        <input
                            type="text"
                            name="codigo1"
                            maxlength="1"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            required>

                        <input
                            type="text"
                            name="codigo2"
                            maxlength="1"
                            inputmode="numeric"
                            required>

                        <input
                            type="text"
                            name="codigo3"
                            maxlength="1"
                            inputmode="numeric"
                            required>

                        <input
                            type="text"
                            name="codigo4"
                            maxlength="1"
                            inputmode="numeric"
                            required>

                        <input
                            type="text"
                            name="codigo5"
                            maxlength="1"
                            inputmode="numeric"
                            required>

                        <input
                            type="text"
                            name="codigo6"
                            maxlength="1"
                            inputmode="numeric"
                            required>

                    </div>


                    <!-- ========================================
                         REENVIAR CÓDIGO
                    ========================================= -->

                    <div class="reenviar-codigo">

                        <span>Não recebeu o código?</span>

                        <a href="#">
                            Reenviar código
                        </a>

                    </div>


                    <!-- ========================================
                         BOTÃO
                    ========================================= -->

                    <div class="input-box codigo-botao">

                        <input
                            type="submit"
                            name="acao"
                            value="Verificar código">

                    </div>


                    <!-- ========================================
                         VOLTAR
                    ========================================= -->

                    <div class="voltar-codigo">

                        <a href="login.php">
                            <i class="fa-solid fa-arrow-left"></i>
                            Voltar para o login
                        </a>

                    </div>


                </form>

            </div>

        </div>

    </div>


    <!-- ========================================
         JAVASCRIPT - AVANÇAR ENTRE OS CAMPOS
    ========================================= -->

    <script>

        const camposCodigo = document.querySelectorAll(
            '.codigo-container input'
        );

        camposCodigo.forEach((campo, index) => {

            campo.addEventListener('input', () => {

                campo.value = campo.value.replace(/\D/g, '');

                if (campo.value && index < camposCodigo.length - 1) {

                    camposCodigo[index + 1].focus();

                }

            });


            campo.addEventListener('keydown', (event) => {

                if (
                    event.key === 'Backspace' &&
                    !campo.value &&
                    index > 0
                ) {

                    camposCodigo[index - 1].focus();

                }

            });

        });

    </script>

</body>

</html>