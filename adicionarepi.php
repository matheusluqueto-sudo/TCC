<?php
session_start();
$titulopagina = "EPI Control - Adicionar EPI";
require_once "config/conexao.php";
$con = conectar();
$idFuncionario = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($idFuncionario <= 0) {
    header("Location: funcionarios.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $idFuncionario = (int) $_POST['id_funcionario'];
    $idEpis = isset($_POST['id_epi']) && is_array($_POST['id_epi']) ? $_POST['id_epi'] : [];
    $quantidade = (int) $_POST['quantidade'];
    if ($idFuncionario > 0 && !empty($idEpis) && $quantidade > 0) {
        $sql = "INSERT INTO tb_saida(id_funcionario,id_epi,solicitacao,quantidade,data,motivo) VALUES(?,?,?,?,CURDATE(),?)";
        $stmt = mysqli_prepare($con, $sql);
        $solicitacao = 0;
        $motivo = "Entrega de EPI";
        foreach ($idEpis as $idEpi) {
            $idEpi = (int) $idEpi;
            if ($idEpi > 0)
                mysqli_stmt_bind_param($stmt, "iiiis", $idFuncionario, $idEpi, $solicitacao, $quantidade, $motivo) && mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header("Location: informacoes.php?id=" . $idFuncionario);
        exit;
    }
}
$epis = mysqli_query($con, "SELECT id_epi,nome_epi,codigo,ca FROM tb_epis ORDER BY id_epi ASC LIMIT 4");
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
                    <h1>Adicionar EPI</h1>
                </div>
                <div class="perfil-admin"><i class="fa-solid fa-user-shield"></i><span>Administrador</span></div>
            </header>
            <main class="pagina-informacoes">
                <div class="detalhes-container adicionar-container">
                    <div class="card-funcionario card-adicionar">
                        <div class="titulo-card">
                            <div class="icone-titulo"><i class="fa-solid fa-shield-halved"></i></div>
                            <div>
                                <h2>Adicionar EPI</h2><span>Selecione um ou mais equipamentos</span>
                            </div>
                        </div>
                        <form action="adicionarepi.php?id=<?php echo $idFuncionario; ?>" method="post">
                            <input type="hidden" name="id_funcionario" value="<?php echo $idFuncionario; ?>">
                            <div class="lista-epis lista-adicionar">
                                <?php while ($epi = mysqli_fetch_assoc($epis)): ?>
                                    <?php
                                    $nomeEpi = strtolower($epi['nome_epi']);
                                    $icone = "fa-shield-halved";
                                    if (strpos($nomeEpi, "capacete") !== false || strpos($nomeEpi, "protetor facial") !== false)
                                        $icone = "fa-helmet-safety";
                                    elseif (strpos($nomeEpi, "óculos") !== false || strpos($nomeEpi, "oculos") !== false)
                                        $icone = "fa-glasses";
                                    elseif (strpos($nomeEpi, "auricular") !== false || strpos($nomeEpi, "ouvido") !== false)
                                        $icone = "fa-headphones";
                                    elseif (strpos($nomeEpi, "luva") !== false)
                                        $icone = "fa-hand";
                                    elseif (strpos($nomeEpi, "respirador") !== false || strpos($nomeEpi, "máscara") !== false || strpos($nomeEpi, "mascara") !== false)
                                        $icone = "fa-mask-face";
                                    ?>
                                    <label class="epi-item epi-opcao">
                                        <input type="checkbox" name="id_epi[]" value="<?php echo $epi['id_epi']; ?>">
                                        <div class="icone-epi"><i class="fa-solid <?php echo $icone; ?>"></i></div>
                                        <div class="info-epi">
                                            <h3><?php echo htmlspecialchars($epi['nome_epi']); ?></h3>
                                            <span>Código:
                                                <?php echo htmlspecialchars($epi['codigo'] ?? 'Não informado'); ?></span>
                                            <span>CA: <?php echo htmlspecialchars($epi['ca'] ?? 'Não informado'); ?></span>
                                        </div>
                                        <div class="check-epi"><i class="fa-solid fa-check"></i></div>
                                    </label>
                                <?php endwhile; ?>
                            </div>
                            <div class="quantidade-adicionar">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" id="quantidade" name="quantidade" value="1" min="1" required>
                            </div>
                            <div class="acoes-funcionario acoes-adicionar">
                                <button type="submit" class="btn-editar"><i
                                        class="fa-solid fa-paper-plane"></i>Adicionar EPI</button>
                                <button type="button" class="btn-excluir"
                                    onclick="window.location.href='informacoes.php?id=<?php echo $idFuncionario; ?>'"><i
                                        class="fa-solid fa-xmark"></i>Cancelar</button>
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
<?php mysqli_close($con); ?>