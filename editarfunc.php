<?php
session_start();
$titulopagina = "EPI Control - Editar Funcionário";
require_once "config/conexao.php";
$funcionario = null;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_funcionario = (int) $_POST['id_funcionario'];
    $nome_completo = $_POST['nome_completo'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $setor = $_POST['setor'];
    $acao = $_POST['acao'] ?? '';
    if ($acao == "salvar") {
        $con = conectar();
        $sql = "UPDATE tb_funcionarios SET nome_completo=?,email=?,cpf=?,setor=? WHERE id_funcionario=?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $nome_completo, $email, $cpf, $setor, $id_funcionario);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header("Location: informacoes.php?id=" . $id_funcionario);
        exit;
    }
}
if ($id <= 0 && isset($_POST['id_funcionario']))
    $id = (int) $_POST['id_funcionario'];
if ($id > 0) {
    $con = conectar();
    $sql = "SELECT * FROM tb_funcionarios WHERE id_funcionario=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $funcionario = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
if (!$funcionario) {
    header("Location: funcionarios.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo $titulopagina; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/informacoes.css">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <div>
                <div class="sidebar-logo"><img src="img/logohome.png" alt="EPI Control"></div>
                <div class="sidebar-titulo"><strong>EPI CONTROL</strong><span>Gestão de Segurança</span></div>
                <nav class="sidebar-menu">
                    <a href="home.php"><i class="fa-solid fa-house"></i><span>Início</span></a>
                    <a href="cadastro.php"><i class="fa-regular fa-user"></i><span>Cadastrar</span></a>
                    <a href="funcionarios.php" class="ativo"><i
                            class="fa-solid fa-users"></i><span>Funcionários</span></a>
                    <a href="relatorios.php"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
                    <a href="estoque.php"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
                    <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
                    <a href="movimentacoes.php"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver
                            movimentações</span></a>
                    <a href="andamento.php"><i class="fa-solid fa-spinner"></i><span>Em andamento</span></a>
                    <a href="funcionarios.php"><i class="fa-solid fa-arrow-left"></i><span>Voltar</span></a>
                </nav>
            </div>
            <div class="sidebar-footer"><i class="fa-solid fa-shield-halved"></i>
                <div><strong>Segurança em primeiro lugar</strong><span>Proteção para todos</span></div>
            </div>
        </aside>
        <div class="conteudo">
            <header class="topbar">
                <div><span class="topbar-label">EPI CONTROL</span>
                    <h1>Editar Funcionário</h1>
                </div>
               <div class="topbar-direita">
                    <div class="notificacao"><i class="fa-regular fa-bell"></i></div>
                    <div class="perfil">
                        <div class="perfil-icone"><i class="fa-solid fa-user"></i></div>
                        <div class="perfil-texto"><strong>Administrador</strong><span>Gestão</span></div>
                    </div>
                </div>
            </header>
            <main class="pagina-informacoes pagina-editar">
                <div class="cabecalho-pagina">
                    <div>
                        <h2><?php echo htmlspecialchars($funcionario['nome_completo']); ?></h2>
                        <p>Edite as informações deste funcionário.</p>
                    </div>
                    <a href="informacoes.php?id=<?php echo $id; ?>" class="btn-voltar"><i
                            class="fa-solid fa-arrow-left"></i>Voltar</a>
                </div>
                <div class="detalhes-container">
                    <div class="card-funcionario">
                        <div class="titulo-card">
                            <div class="icone-titulo"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h2>Dados do funcionário</h2><span>Informações cadastrais</span>
                            </div>
                        </div>
                        <form action="editarfunc.php?id=<?php echo $id; ?>" method="post">
                            <input type="hidden" name="id_funcionario"
                                value="<?php echo $funcionario['id_funcionario']; ?>">
                            <div class="perfil-funcionario">
                                <div class="foto-funcionario"><img
                                        src="<?php echo !empty($funcionario['foto_url']) ? htmlspecialchars($funcionario['foto_url']) : 'img/boy.png'; ?>"
                                        alt="Foto do funcionário"></div>
                                <div class="informacoes-funcionario">
                                    <div class="campo"><label>Nome completo</label><input type="text"
                                            name="nome_completo"
                                            value="<?php echo htmlspecialchars($funcionario['nome_completo']); ?>"
                                            required></div>
                                    <div class="campo"><label>E-mail</label><input type="email" name="email"
                                            value="<?php echo htmlspecialchars($funcionario['email']); ?>" required>
                                    </div>
                                    <div class="campo"><label>CPF</label><input type="text" name="cpf"
                                            value="<?php echo htmlspecialchars($funcionario['cpf'] ?? ''); ?>"></div>
                                    <div class="campo"><label>Setor</label><input type="text" name="setor"
                                            value="<?php echo htmlspecialchars($funcionario['setor'] ?? ''); ?>"></div>
                                </div>
                            </div>
                            <div class="acoes-funcionario">
                                <button type="submit" name="acao" value="salvar" class="btn-editar"><i
                                        class="fa-solid fa-floppy-disk"></i>Salvar</button>
                                <a href="informacoes.php?id=<?php echo $id; ?>" class="btn-cancelar-edicao"><i
                                        class="fa-solid fa-xmark"></i>Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="frase-seguranca"><i class="fa-solid fa-shield-halved"></i><span>Segurança em cada detalhe,
                        proteção em cada escolha.</span></div>
            </main>
        </div>
    </div>
</body>

</html>