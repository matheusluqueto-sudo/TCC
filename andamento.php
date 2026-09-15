<?php
session_start();
$titulopagina = "EPI Control - Em andamento";
require_once "config/conexao.php";
$con = conectar();
$sql = "SELECT s.id_solicitacao,s.status_solicitacao,s.data_solicitacao,s.justificativa,f.nome_completo,e.nome_epi,e.codigo FROM tb_solicitacoes s INNER JOIN tb_funcionarios f ON f.id_funcionario=s.id_funcionario INNER JOIN tb_epis e ON e.id_epi=s.id_epi WHERE s.status_solicitacao IN ('Pendente','Em andamento') ORDER BY s.data_solicitacao DESC";
$resultado = mysqli_query($con, $sql);
if (!$resultado)
    die("Erro ao buscar processos: " . mysqli_error($con));
$processos = [];
$totalProcessos = 0;
$pendentes = 0;
$emAndamento = 0;
$aguardando = 0;
while ($processo = mysqli_fetch_assoc($resultado)) {
    $processos[] = $processo;
    $totalProcessos++;
    if ($processo['status_solicitacao'] == "Pendente") {
        $pendentes++;
        $aguardando++;
    }
    if ($processo['status_solicitacao'] == "Em andamento")
        $emAndamento++;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
                    <a href="movimentacoes.php"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver
                            movimentações</span></a>
                    <a href="andamento.php" class="ativo"><i class="fa-solid fa-spinner"></i><span>Em
                            andamento</span></a>
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
                    <h1>Em andamento</h1>
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
                                <div class="icone-titulo"><i class="fa-solid fa-spinner"></i></div>
                                <div>
                                    <h2>Processos em andamento</h2>
                                    <p>Acompanhe solicitações que ainda precisam de atenção.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="linha-divisoria"></div>

                    <div class="andamento-resumo">
                        <div class="andamento-card">
                            <div class="andamento-card-icone"><i class="fa-solid fa-arrows-rotate"></i></div>
                            <div><span>Processos em andamento</span><strong><?php echo $totalProcessos; ?></strong>
                            </div>
                        </div>
                        <div class="andamento-card">
                            <div class="andamento-card-icone pendente"><i class="fa-solid fa-clock"></i></div>
                            <div><span>Pedidos pendentes</span><strong><?php echo $pendentes; ?></strong></div>
                        </div>
                        <div class="andamento-card">
                            <div class="andamento-card-icone andamento"><i class="fa-solid fa-spinner"></i></div>
                            <div><span>Em andamento</span><strong><?php echo $emAndamento; ?></strong></div>
                        </div>
                        <div class="andamento-card">
                            <div class="andamento-card-icone aguardando"><i
                                    class="fa-solid fa-triangle-exclamation"></i></div>
                            <div><span>Aguardando ação</span><strong><?php echo $aguardando; ?></strong></div>
                        </div>
                    </div>

                    <div class="andamento-graficos">
                        <div class="grafico-card">
                            <div class="grafico-topo">
                                <div>
                                    <h2>Distribuição dos processos</h2>
                                    <p>Visualização dos pedidos por status.</p>
                                </div>
                                <div class="grafico-icone"><i class="fa-solid fa-chart-pie"></i></div>
                            </div>
                            <div class="grafico-area"><canvas id="graficoRosca"></canvas></div>
                        </div>

                        <div class="grafico-card">
                            <div class="grafico-topo">
                                <div>
                                    <h2>Comparação de solicitações</h2>
                                    <p>Quantidade de pedidos em cada etapa.</p>
                                </div>
                                <div class="grafico-icone"><i class="fa-solid fa-chart-column"></i></div>
                            </div>
                            <div class="grafico-area"><canvas id="graficoBarras"></canvas></div>
                        </div>
                    </div>

                    <div class="andamento-painel">
                        <div class="andamento-topo">
                            <div>
                                <h2>Acompanhamento</h2>
                                <p>Confira os processos que ainda não foram finalizados.</p>
                            </div>
                            <div class="filtro-andamento">
                                <i class="fa-solid fa-filter"></i>
                                <select id="filtroAndamento">
                                    <option value="todos">Todos</option>
                                    <option value="Pendente">Pendentes</option>
                                    <option value="Em andamento">Em andamento</option>
                                </select>
                            </div>
                        </div>

                        <div class="lista-andamento" id="listaAndamento">
                            <?php if (!empty($processos)): ?>
                                <?php foreach ($processos as $processo): ?>
                                    <?php
                                    $pendente = $processo['status_solicitacao'] == "Pendente";
                                    $classeStatus = $pendente ? "status-pendente" : "status-andamento";
                                    $icone = $pendente ? "fa-clock" : "fa-spinner";
                                    $data = date("d/m/Y H:i", strtotime($processo['data_solicitacao']));
                                    ?>
                                    <div class="andamento-item"
                                        data-status="<?php echo htmlspecialchars($processo['status_solicitacao']); ?>">
                                        <div class="andamento-icone <?php echo $classeStatus; ?>">
                                            <i class="fa-solid <?php echo $icone; ?>"></i>
                                        </div>
                                        <div class="andamento-info">
                                            <h3><?php echo htmlspecialchars($processo['nome_epi']); ?></h3>
                                            <span><i class="fa-solid fa-barcode"></i> Código:
                                                <?php echo !empty($processo['codigo']) ? htmlspecialchars($processo['codigo']) : "Não informado"; ?></span>
                                        </div>
                                        <div class="andamento-funcionario">
                                            <span>Funcionário</span>
                                            <strong><i
                                                    class="fa-solid fa-user"></i><?php echo htmlspecialchars($processo['nome_completo']); ?></strong>
                                        </div>
                                        <div class="andamento-pedido">
                                            <span>Solicitação</span>
                                            <strong>#<?php echo (int) $processo['id_solicitacao']; ?></strong>
                                        </div>
                                        <div class="andamento-data">
                                            <span><i class="fa-regular fa-calendar"></i><?php echo $data; ?></span>
                                            <?php if (!empty($processo['justificativa'])): ?>
                                                <small><?php echo htmlspecialchars($processo['justificativa']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="andamento-status <?php echo $classeStatus; ?>">
                                            <i class="fa-solid <?php echo $icone; ?>"></i>
                                            <?php echo htmlspecialchars($processo['status_solicitacao']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="sem-andamento">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <h3>Nenhum processo em andamento</h3>
                                    <p>Quando houver uma nova solicitação, ela aparecerá aqui.</p>
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

    <script>
        window.dadosAndamento = {
            pendentes: <?php echo (int) $pendentes; ?>,
            emAndamento: <?php echo (int) $emAndamento; ?>
        };
    </script>
    <script src="js/andamento.js"></script>
</body>

</html>
<?php mysqli_close($con); ?>