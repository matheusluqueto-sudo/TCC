<?php
session_start();
$titulopagina = "EPI Control - Informações";
require_once "config/conexao.php";
$con = conectar();
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Funcionário não informado.");
}
$idFuncionario = (int) $_GET['id'];
$stmt = mysqli_prepare($con, "SELECT * FROM tb_funcionarios WHERE id_funcionario=?");
mysqli_stmt_bind_param($stmt, "i", $idFuncionario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$funcionario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);
if (!$funcionario) {
    die("Funcionário não encontrado.");
}
$stmt = mysqli_prepare($con, "SELECT e.nome_epi,e.codigo FROM tb_saida s INNER JOIN tb_epis e ON e.id_epi=s.id_epi WHERE s.id_funcionario=? GROUP BY e.id_epi,e.nome_epi,e.codigo ORDER BY e.nome_epi ASC");
mysqli_stmt_bind_param($stmt, "i", $idFuncionario);
mysqli_stmt_execute($stmt);
$epis = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
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
            <div class="sidebar-logo">
                <img src="img/logohome.png" alt="EPI Control">
            </div>
            <div class="sidebar-titulo">
                <strong>EPI CONTROL</strong>
                <span>Sistema de Gestão</span>
            </div>
            <nav class="sidebar-menu">
                <a href="home.php"><i class="fa-solid fa-house"></i><span>Início</span></a>
                <a href="cadastro.php"><i class="fa-regular fa-user"></i><span>Cadastrar funcionário</span></a>
                <a href="funcionarios.php" class="ativo"><i class="fa-solid fa-users"></i><span>Funcionários</span></a>
                <a href="relatorios.php"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
                <a href="estoque.php"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
                <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
                <a href="movimentacoes.php"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver
                        movimentações</span></a>
                <a href="andamento.php"><i class="fa-solid fa-spinner"></i><span>Em andamento</span></a>
                <a href="admfunc.php"><i class="fa-solid fa-arrow-left"></i><span>Voltar</span></a>
            </nav>
            <div class="sidebar-footer">
                <i class="fa-solid fa-shield-halved"></i>
                <div><strong>Sistema seguro</strong><span>Dados protegidos</span></div>
            </div>
        </aside>
        <main class="conteudo">
            <header class="topbar">
                <div>
                    <span class="topbar-label">GESTÃO DE FUNCIONÁRIOS</span>
                    <h1>Informações do funcionário</h1>
                </div>
                 <div class="topbar-direita">
                    <div class="notificacao"><i class="fa-regular fa-bell"></i></div>
                    <div class="perfil">
                        <div class="perfil-icone"><i class="fa-solid fa-user"></i></div>
                        <div class="perfil-texto"><strong>Administrador</strong><span>Gestão</span></div>
                    </div>
                </div>
            </header>
            <section class="pagina-informacoes">
                <div class="cabecalho-pagina">
                    <div>
                        <h2><?php echo htmlspecialchars($funcionario['nome_completo']); ?></h2>
                        <p>Confira as informações e os EPIs vinculados a este funcionário.</p>
                    </div>
                    <a href="funcionarios.php" class="btn-voltar"><i class="fa-solid fa-arrow-left"></i>Voltar</a>
                </div>
                <div class="detalhes-container">
                    <div class="card-funcionario">
                        <div class="titulo-card">
                            <div class="icone-titulo"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h2>Dados do funcionário</h2><span>Informações cadastrais</span>
                            </div>
                        </div>
                        <div class="perfil-funcionario">
                            <div class="foto-funcionario">
                                <img src="<?php echo !empty($funcionario['foto_url']) ? htmlspecialchars($funcionario['foto_url']) : 'img/boy.png'; ?>"
                                    alt="Foto do funcionário">
                            </div>
                            <div class="informacoes-funcionario">
                                <div class="campo"><label>Nome completo</label>
                                    <div class="valor-campo">
                                        <?php echo htmlspecialchars($funcionario['nome_completo']); ?>
                                    </div>
                                </div>
                                <div class="campo"><label>E-mail</label>
                                    <div class="valor-campo">
                                        <?php echo htmlspecialchars($funcionario['email'] ?? ''); ?>
                                    </div>
                                </div>
                                <div class="campo"><label>CPF</label>
                                    <div class="valor-campo"><?php echo htmlspecialchars($funcionario['cpf'] ?? ''); ?>
                                    </div>
                                </div>
                                <div class="campo"><label>Setor</label>
                                    <div class="valor-campo">
                                        <?php echo htmlspecialchars($funcionario['setor'] ?? ''); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="acoes-funcionario">
                            <button type="button" class="btn-editar"
                                onclick="window.location.href='editarfunc.php?id=<?php echo $funcionario['id_funcionario']; ?>'"><i
                                    class="fa-solid fa-pen"></i>Editar</button>
                            <button type="button" class="btn-excluir"
                                onclick="if(confirm('Tem certeza que deseja excluir este funcionário? Essa ação não poderá ser desfeita.'))window.location.href='excluirfunc.php?id=<?php echo $funcionario['id_funcionario']; ?>';"><i
                                    class="fa-solid fa-trash"></i>Excluir</button>
                        </div>
                    </div>
                    <div class="card-epis">
                        <div class="titulo-card">
                            <div class="icone-titulo"><i class="fa-solid fa-shield-halved"></i></div>
                            <div>
                                <h2>EPIs do funcionário</h2><span>Equipamentos vinculados</span>
                            </div>
                        </div>
                        <div class="lista-epis">
                            <?php if (mysqli_num_rows($epis) > 0): ?>
                                <?php while ($epi = mysqli_fetch_assoc($epis)): ?>
                                    <?php
                                    $nomeEpi = strtolower($epi['nome_epi']);
                                    $icone = "fa-shield-halved";
                                    if (strpos($nomeEpi, "capacete") !== false || strpos($nomeEpi, "protetor facial") !== false) {
                                        $icone = "fa-helmet-safety";
                                    } elseif (strpos($nomeEpi, "óculos") !== false || strpos($nomeEpi, "oculos") !== false) {
                                        $icone = "fa-glasses";
                                    } elseif (strpos($nomeEpi, "auricular") !== false || strpos($nomeEpi, "ouvido") !== false) {
                                        $icone = "fa-headphones";
                                    } elseif (strpos($nomeEpi, "luva") !== false) {
                                        $icone = "fa-hand";
                                    } elseif (strpos($nomeEpi, "respirador") !== false || strpos($nomeEpi, "máscara") !== false || strpos($nomeEpi, "mascara") !== false) {
                                        $icone = "fa-mask-face";
                                    }
                                    ?>
                                    <div class="epi-item">
                                        <div class="icone-epi"><i class="fa-solid <?php echo $icone; ?>"></i></div>
                                        <div class="info-epi">
                                            <h3><?php echo htmlspecialchars($epi['nome_epi']); ?></h3>
                                            <span>Em uso</span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="sem-epis"><i class="fa-solid fa-box-open"></i>
                                    <p>Nenhum EPI registrado para este funcionário.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="adicionar-epi">
                            <button type="button" class="btn-adicionar"
                                onclick="window.location.href='adicionarepi.php?id=<?php echo $funcionario['id_funcionario']; ?>'"><i
                                    class="fa-solid fa-plus"></i>Adicionar EPI</button>
                        </div>
                    </div>
                </div>
                <div class="frase-seguranca"><i class="fa-solid fa-shield-halved"></i><span>Segurança em cada detalhe,
                        proteção em cada escolha.</span></div>
            </section>
        </main>
    </div>
</body>

</html>
<?php mysqli_close($con); ?>