<?php
$titulopagina = "EPI Control - Cadastro";
$mensagemerro = "";
$classe_css = "";
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

<body>
    <div class="container-login">
        <div class="img-box">
            <img src="img/logo.png" alt="EPI Control - Gestão e Segurança">
        </div>
        <div class="content-box">
            <div class="form-box cadastro-form">
                <div class="titulo-login">
                    <h1>Atualize sua Senha!</h1>
                    <p>Atualize sua senha para começar a usar o EPI Control</p>
                </div>
                <?php if (!empty($mensagemerro)) { ?>
                    <div class="mensagem-erro <?php echo $classe_css; ?>">
                        <?php echo $mensagemerro; ?>
                    </div>
                <?php } ?>
                <form action="" method="POST">
                    <div class="input-box">
                        <span>Senha</span>
                        <input type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>
                    <div class="input-box">
                        <span>Confirme sua Senha</span>
                        <input type="password" name="senha" placeholder="Confirme sua senha" required>
                    </div>
                    <br>
                    <div class="input-box">
                        <input type="submit" name="acao" value="Continuar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>