<?php
session_start();
$titulopagina = "EPI Control - Início";
require_once "config/conexao.php";
if (!isset($_SESSION['id_funcionario'])) {
    header("Location: login.php");
    exit;
}
$con = conectar();
$id_funcionario = (int) $_SESSION['id_funcionario'];
$stmt = mysqli_prepare($con, "SELECT nome_completo,cargo,setor,foto_url FROM tb_funcionarios WHERE id_funcionario=?");
mysqli_stmt_bind_param($stmt, "i", $id_funcionario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$funcionario = mysqli_fetch_assoc($resultado);
if (!$funcionario) {
    header("Location: login.php");
    exit;
}
$nome = $funcionario['nome_completo'];
$primeiroNome = explode(" ", trim($nome))[0];
$foto = !empty($funcionario['foto_url']) ? $funcionario['foto_url'] : 'img/boy.png';
$stmtEpi = mysqli_prepare($con, "SELECT COUNT(DISTINCT id_epi) AS total FROM tb_saida WHERE id_funcionario=?");
mysqli_stmt_bind_param($stmtEpi, "i", $id_funcionario);
mysqli_stmt_execute($stmtEpi);
$resultadoEpi = mysqli_stmt_get_result($stmtEpi);
$totalEpis = (int) mysqli_fetch_assoc($resultadoEpi)['total'];
$stmtPed = mysqli_prepare($con, "SELECT COUNT(*) AS total FROM tb_solicitacoes WHERE id_funcionario=? AND status_solicitacao='Pendente'");
mysqli_stmt_bind_param($stmtPed, "i", $id_funcionario);
mysqli_stmt_execute($stmtPed);
$resultadoPed = mysqli_stmt_get_result($stmtPed);
$totalPedidos = (int) mysqli_fetch_assoc($resultadoPed)['total'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo $titulopagina; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                    <a href="homefunc.php" class="ativo"><i class="fa-solid fa-house"></i><span>Início</span></a>
                    <a href="meuepi.php"><i class="fa-solid fa-shield-halved"></i><span>Meu EPI</span></a>
                    <a href="solicitar.php"><i class="fa-solid fa-clipboard-list"></i><span>Solicitar EPI</span></a>
                    <a href="ocorrencias.php"><i
                            class="fa-solid fa-triangle-exclamation"></i><span>Ocorrências</span></a>
                    <a href="perfil.php"><i class="fa-solid fa-user"></i><span>Meu perfil</span></a>
                    <a href="login.php"><i class="fa-solid fa-right-from-bracket"></i><span>Sair</span></a>
                </nav>
            </div>
            <div class="sidebar-bottom">
                <div class="seguranca-menu">
                    <i class="fa-solid fa-lock"></i>
                    <div><strong>Sistema seguro</strong><span>Dados protegidos</span></div>
                </div>
            </div>
        </aside>
        <main class="conteudo">
            <header class="topbar">
                <div class="titulo-pagina">
                    <span>Painel do funcionário</span>
                    <h1>Início</h1>
                </div>
                <div class="topbar-direita">
                    <div class="notificacao"><i class="fa-regular fa-bell"></i></div>
                    <div class="perfil">
                        <div class="perfil-icone"><img src="<?php echo htmlspecialchars($foto); ?>" alt="Perfil"></div>
                        <div class="perfil-texto">
                            <strong><?php echo htmlspecialchars($primeiroNome); ?></strong><span><?php echo htmlspecialchars($funcionario['cargo']); ?></span>
                        </div>
                    </div>
                </div>
            </header>
            <section class="pagina">
                <div class="funcionarios-container">
                    <div class="cabecalho-conteudo">
                        <div class="descricao">
                            <div class="titulo-completo">
                                <div class="icone-titulo"><i class="fa-solid fa-house"></i></div>
                                <div>
                                    <h2>Olá, <?php echo htmlspecialchars($primeiroNome); ?>! 👋</h2>
                                    <p>Confira seus EPIs, solicitações e informações no sistema.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="linha-divisoria"></div>
                    <div class="cards-inicio">
                        <div class="card-inicio">
                            <div class="icone-card"><i class="fa-solid fa-shield-halved"></i></div>
                            <div class="dados-card">
                                <span>Meus EPIs</span>
                                <strong><?php echo $totalEpis; ?></strong>
                                <p>EPIs atribuídos</p>
                            </div>
                            <a href="meuepi.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="card-inicio">
                            <div class="icone-card"><i class="fa-solid fa-clipboard-list"></i></div>
                            <div class="dados-card">
                                <span>Solicitações</span>
                                <strong><?php echo $totalPedidos; ?></strong>
                                <p>Solicitações pendentes</p>
                            </div>
                            <a href="solicitar.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="card-inicio">
                            <div class="icone-card"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <div class="dados-card">
                                <span>Ocorrências</span>
                                <strong>+</strong>
                                <p>Registrar ocorrência</p>
                            </div>
                            <a href="ocorrencias.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="acoes-funcionario">
                        <a href="meuepi.php" class="acao-funcionario">
                            <div><i class="fa-solid fa-shield-halved"></i></div>
                            <section><strong>Meu EPI</strong><span>Consulte os EPIs que estão atribuídos a você.</span>
                            </section>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="solicitar.php" class="acao-funcionario">
                            <div><i class="fa-solid fa-clipboard-list"></i></div>
                            <section><strong>Solicitar EPI</strong><span>Faça uma nova solicitação de
                                    equipamento.</span></section>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="ocorrencias.php" class="acao-funcionario">
                            <div><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <section><strong>Registrar ocorrência</strong><span>Informe problemas relacionados a EPI ou
                                    máquinas.</span></section>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                    <div class="frase-seguranca">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Segurança em cada detalhe, proteção em cada escolha.</span>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
<?php mysqli_stmt_close($stmt);
mysqli_stmt_close($stmtEpi);
mysqli_stmt_close($stmtPed);
mysqli_close($con); ?>