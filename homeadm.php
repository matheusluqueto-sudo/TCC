<?php

session_start();

$titulopagina = "EPI Control - Funcionários";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $titulopagina; ?></title>

    <link rel="stylesheet" href="css/homeadm.css">

</head>

<body>

    <div class="container-home">


        <!-- ========================================
             LADO ESQUERDO
        ========================================= -->

        <div class="lado-esquerdo">

            <div class="logo-box">

                <img
                    src="img/logo.png"
                    alt="EPI Control - Gestão e Segurança">

            </div>

        </div>


        <!-- ========================================
             LADO DIREITO
        ========================================= -->

        <div class="lado-direito">

            <div class="conteudo-home">


                <!-- TÍTULO -->
                <div class="menu-funcionarios">

                <div class="titulo-home">

                    <h1>ABA DE ADMINISTRAÇÃO</h1>

                    <p>FUNCIONÁRIOS</p>

                </div>


                <!-- ========================================
                     CAIXA DOS BOTÕES
                ========================================= -->

              


                    <!-- BOTÃO 1 -->

                    <a href="cadastro.php"
                       class="botao">

                        Cadastrar novo funcionário

                    </a>


                    <!-- BOTÃO 2 -->

                    <a href="funcionarios.php"
                       class="botao">

                        Ver funcionários

                    </a>

                    <a href="relatorios.php"
                       class="botao">

                        Ver relatórios

                    </a>


                

 
                <!-- VOLTAR --> 
 
                <div class="voltar"> 
 
                    <a href="home.php"> 
 
                        Voltar
                    </a>

                </div>
            </div>

            </div>

        </div>


    </div>

</body>

</html>