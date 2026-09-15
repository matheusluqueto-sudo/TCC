<?php
session_start();
$titulopagina = "EPI Control - Pedidos";
require_once "config/conexao.php";
$con = conectar();

$sql = "SELECT s.*,f.nome_completo,e.nome_epi,e.codigo FROM tb_solicitacoes s INNER JOIN tb_funcionarios f ON f.id_funcionario=s.id_funcionario INNER JOIN tb_epis e ON e.id_epi=s.id_epi ORDER BY s.data_solicitacao DESC";
$resultado = mysqli_query($con, $sql);
if (!$resultado)
    die("Erro ao buscar pedidos: " . mysqli_error($con));

$pedidos = [];
$totalPedidos = 0;
$pendentes = 0;
$aprovados = 0;
$recusados = 0;

while ($pedido = mysqli_fetch_assoc($resultado)) {
    $pedidos[] = $pedido;
    $totalPedidos++;
    $status = strtolower($pedido['status_solicitacao']);
    if ($status == "pendente")
        $pendentes++;
    elseif ($status == "aprovado")
        $aprovados++;
    elseif ($status == "recusado")
        $recusados++;
}
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
                    <a href="home.php"><i class="fa-solid fa-house"></i><span>Início</span></a>
                    <a href="cadastro.php"><i class="fa-solid fa-user-plus"></i><span>Cadastrar funcionário</span></a>
                    <a href="funcionarios.php"><i class="fa-solid fa-users"></i><span>Funcionários</span></a>
                    <a href="relatorios.php"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
                    <a href="estoque.php"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
                    <a href="pedidos.php" class="ativo"><i class="fa-solid fa-clipboard-list"></i><span>Ver
                            pedidos</span></a>
                    <a href="movimentacoes.php"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver
                            movimentações</span></a>
                    <a href="andamento.php"><i class="fa-solid fa-spinner"></i><span>Em andamento</span></a>
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
                    <span>Painel administrativo</span>
                    <h1>Pedidos</h1>
                </div>

                <div class="topbar-direita">
                    <div class="notificacao"><i class="fa-regular fa-bell"></i></div>
                    <div class="perfil">
                        <div class="perfil-icone"><i class="fa-solid fa-user"></i></div>
                        <div class="perfil-texto"><strong>Administrador</strong><span>Gestão</span></div>
                    </div>
                </div>
            </header>

            <section class="pagina">
                <div class="funcionarios-container">

                    <div class="cabecalho-conteudo">
                        <div class="descricao">
                            <div class="titulo-completo">
                                <div class="icone-titulo"><i class="fa-solid fa-clipboard-list"></i></div>
                                <div>
                                    <h2>Solicitações de EPI</h2>
                                    <p>Acompanhe e gerencie as solicitações de equipamentos.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="linha-divisoria"></div>

                    <div class="cards-grid pedidos-resumo">

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-clipboard-list"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $totalPedidos; ?></span>
                            <span class="info-card">Total de pedidos</span>
                        </div>

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-clock"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $pendentes; ?></span>
                            <span class="info-card">Pedidos pendentes</span>
                        </div>

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-circle-check"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $aprovados; ?></span>
                            <span class="info-card">Pedidos aprovados</span>
                        </div>

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-circle-xmark"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $recusados; ?></span>
                            <span class="info-card">Pedidos recusados</span>
                        </div>

                    </div>

                    <div class="pedidos-painel">

                        <div class="pedidos-topo">
                            <div>
                                <h2>Pedidos registrados</h2>
                                <p>Confira as solicitações realizadas pelos funcionários.</p>
                            </div>

                            <div class="filtro-pedidos">
                                <i class="fa-solid fa-filter"></i>
                                <select id="filtroStatus">
                                    <option value="todos">Todos</option>
                                    <option value="pendente">Pendentes</option>
                                    <option value="aprovado">Aprovados</option>
                                    <option value="recusado">Recusados</option>
                                </select>
                            </div>
                        </div>

                        <div class="lista-pedidos" id="listaPedidos">

                            <?php if (!empty($pedidos)): ?>

                                <?php foreach ($pedidos as $pedido): ?>

                                    <?php
                                    $status = strtolower($pedido['status_solicitacao']);
                                    $classeStatus = "badge-amarelo";
                                    $iconeStatus = "fa-clock";
                                    if ($status == "aprovado") {
                                        $classeStatus = "badge-verde";
                                        $iconeStatus = "fa-circle-check";
                                    } elseif ($status == "recusado") {
                                        $classeStatus = "badge-vermelho";
                                        $iconeStatus = "fa-circle-xmark";
                                    }
                                    $data = date("d/m/Y H:i", strtotime($pedido['data_solicitacao']));
                                    ?>

                                    <div class="pedido-card pedido-item" data-status="<?php echo htmlspecialchars($status); ?>">

                                        <div class="pedido-info">

                                            <div class="pedido-icone">
                                                <i class="fa-solid fa-shield-halved"></i>
                                            </div>

                                            <div class="pedido-dados">
                                                <h3><?php echo htmlspecialchars($pedido['nome_epi']); ?></h3>
                                                <span><i
                                                        class="fa-solid fa-user"></i><?php echo htmlspecialchars($pedido['nome_completo']); ?></span>
                                                <span><i class="fa-solid fa-hashtag"></i>Pedido
                                                    <?php echo $pedido['id_solicitacao']; ?></span>
                                            </div>

                                        </div>

                                        <div class="pedido-centro">
                                            <span class="pedido-data"><i
                                                    class="fa-regular fa-calendar"></i><?php echo $data; ?></span>
                                            <?php if (!empty($pedido['justificativa'])): ?>
                                                <span
                                                    class="pedido-justificativa"><?php echo htmlspecialchars($pedido['justificativa']); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="pedido-status">
                                            <span class="badge <?php echo $classeStatus; ?>"><i
                                                    class="fa-solid <?php echo $iconeStatus; ?>"></i><?php echo htmlspecialchars($pedido['status_solicitacao']); ?></span>
                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="sem-pedidos">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                    <h3>Nenhum pedido registrado</h3>
                                    <p>Quando um funcionário solicitar um EPI, o pedido aparecerá aqui.</p>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="frase-seguranca">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Segurança em cada detalhe, proteção em cada escolha.</span>
                    </div>

                </div>
            </section>
        </main>
    </div>

    <script src="js/pedidos.js"></script>
</body>

</html>
<?php mysqli_close($con); ?>