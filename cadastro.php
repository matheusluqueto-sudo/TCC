<?php
$titulopagina = "EPI Control - Cadastro";
require_once "config/conexao.php";
$mensagemerro = "";
$classe_css = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['cpf'], $_POST['cargo'], $_POST['setor'], $_POST['acao']) && $_POST['acao'] == 'Cadastrar') {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $cpf = trim($_POST['cpf']);
        $cargo = trim($_POST['cargo']);
        $setor = trim($_POST['setor']);
        if (!empty($nome) && !empty($email) && !empty($senha) && !empty($cpf) && !empty($cargo) && !empty($setor)) {
            $conexao = conectar();
            $sql = "SELECT id_funcionario FROM tb_funcionarios WHERE email=?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($resultado) > 0) {
                $mensagemerro = "Este email já está cadastrado.";
                $classe_css = "erro";
            } else {
                $sql = "INSERT INTO tb_funcionarios(nome_completo,cpf,cargo,setor,email,senha,nivel_acesso) VALUES(?,?,?,?,?,?,?)";
                $stmt = mysqli_prepare($conexao, $sql);
                $nivel_acesso = "Funcionario";
                mysqli_stmt_bind_param($stmt, "sssssss", $nome, $cpf, $cargo, $setor, $email, $senha, $nivel_acesso);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_close($conexao);
                    header("Location: funcionarios.php?cadastro=sucesso");
                    exit;
                } else {
                    $mensagemerro = "Erro ao realizar o cadastro.";
                    $classe_css = "erro";
                }
            }
            mysqli_close($conexao);
        } else {
            $mensagemerro = "Preencha todos os campos.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/funcionarios.css">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-top">
                <div class="logo-box"><img src="img/logohome.png" alt="EPI Control"></div>
                <div class="empresa">
                    <div class="icone-empresa"><i class="fa-solid fa-shield-halved"></i></div>
                    <div><strong>EPI CONTROL</strong><span>Sistema de Gestão</span></div>
                </div>
                <nav class="menu">
                    <a href="home.php"><i class="fa-solid fa-house"></i><span>Início</span></a>
                    <a href="cadastro.php" class="ativo"><i class="fa-regular fa-user"></i><span>Cadastrar
                            funcionário</span></a>
                    <a href="funcionarios.php"><i class="fa-solid fa-users"></i><span>Funcionários</span></a>
                    <a href="relatorios.php"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
                    <a href="estoque.php"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
                    <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
                    <a href="movimentacoes.php"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver
                            movimentações</span></a>
                    <a href="andamento.php"><i class="fa-solid fa-spinner"></i><span>Em andamento</span></a>
                    <a href="admfunc.php"><i class="fa-solid fa-arrow-left"></i><span>Voltar</span></a>
                </nav>
            </div>
            <div class="sidebar-bottom">
                <div class="seguranca-menu">
                    <i class="fa-solid fa-lock"></i>
                    <div><strong>Sistema seguro</strong><span>Dados protegidos</span></div>
                </div>
            </div>
        </aside>
        <div class="conteudo">
            <header class="topbar">
                <div class="titulo-pagina"><span>EPI CONTROL</span>
                    <h1>Cadastrar funcionário</h1>
                </div>
                <div class="topbar-direita">
                    <div class="notificacao"><i class="fa-regular fa-bell"></i></div>
                    <div class="perfil">
                        <div class="perfil-icone"><i class="fa-solid fa-user"></i></div>
                        <div class="perfil-texto"><strong>Administrador</strong><span>Gestor do sistema</span></div>
                    </div>
                </div>
            </header>
            <main class="pagina">
                <section class="cadastro-container">
                    <div class="cabecalho-cadastro">
                        <div class="titulo-completo">
                            <div class="icone-titulo"><i class="fa-solid fa-user-plus"></i></div>
                            <div>
                                <h2>Novo funcionário</h2>
                                <p>Preencha os dados abaixo para cadastrar um funcionário.</p>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($mensagemerro)) { ?>
                        <div class="mensagem-erro <?php echo $classe_css; ?>">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span><?php echo htmlspecialchars($mensagemerro); ?></span>
                        </div>
                    <?php } ?>
                    <div class="linha-divisoria"></div>
                    <form method="POST" class="formulario-cadastro">
                        <div class="campo">
                            <label><i class="fa-solid fa-user"></i>Nome completo</label>
                            <input type="text" name="nome" placeholder="Digite o nome completo" required>
                        </div>
                        <div class="campo">
                            <label><i class="fa-solid fa-envelope"></i>Email</label>
                            <input type="email" name="email" placeholder="Digite o email" required>
                        </div>
                        <div class="campo">
                            <label><i class="fa-solid fa-lock"></i>Senha temporária</label>
                            <input type="password" name="senha" placeholder="Digite a senha" required>
                        </div>
                        <div class="campo">
                            <label><i class="fa-solid fa-id-card"></i>CPF</label>
                            <input type="text" name="cpf" placeholder="Digite o CPF" required>
                        </div>
                        <div class="campo campo-completo">
                            <label><i class="fa-solid fa-briefcase"></i>Cargo</label>
                            <select name="cargo" required>
                                <option value="" disabled selected>Selecione o cargo</option>
                                <option value="Funcionario">Funcionário</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="campo campo-completo">
                            <label><i class="fa-solid fa-building"></i>Setor</label>
                            <select name="setor" required>
                                <option value="" disabled selected>Selecione o setor</option>
                                <option value="Almoxarifado">Almoxarifado</option>
                                <option value="Caldeiraria">Caldeiraria</option>
                                <option value="Controle de Qualidade">Controle de Qualidade</option>
                                <option value="Manutenção Industrial">Manutenção Industrial</option>
                                <option value="Operação de Empilhadeira">Operação de Empilhadeira</option>
                                <option value="Produção">Produção</option>
                                <option value="Segurança do Trabalho">Segurança do Trabalho</option>
                                <option value="TI">TI</option>
                                <option value="Usinagem">Usinagem</option>
                            </select>
                        </div>
                        <div class="acoes-formulario">
                            <a href="funcionarios.php" class="btn-cancelar"><i
                                    class="fa-solid fa-xmark"></i>Cancelar</a>
                            <button type="submit" name="acao" value="Cadastrar" class="btn-cadastrar"><i
                                    class="fa-solid fa-user-plus"></i>Cadastrar funcionário</button>
                        </div>
                    </form>
                </section>
                <div class="frase-seguranca"><i class="fa-solid fa-shield-halved"></i><span>Segurança em cada detalhe,
                        proteção em cada escolha.</span></div>
            </main>
        </div>
    </div>
</body>

</html>