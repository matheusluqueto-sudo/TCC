<?php
session_start();
$titulopagina = "EPI Control - Início";
require_once "config/conexao.php";
$con = conectar();

$totalFuncionarios = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) total FROM tb_funcionarios"))['total'];
$totalEpis = mysqli_fetch_assoc(mysqli_query($con, "SELECT COALESCE(SUM(quantidade_estoque),0) total FROM tb_epis"))['total'];
$pedidosPendentes = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) total FROM tb_solicitacoes WHERE status_solicitacao='Pendente'"))['total'];
$processosAndamento = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) total FROM tb_solicitacoes WHERE status_solicitacao IN ('Pendente','Em andamento')"))['total'];

$estoque = [];
$resultado = mysqli_query($con, "SELECT nome_epi,quantidade_estoque FROM tb_epis ORDER BY nome_epi ASC");
while ($epi = mysqli_fetch_assoc($resultado))
    $estoque[] = $epi;

$pedidos = [];
$resultado = mysqli_query($con, "SELECT status_solicitacao,COUNT(*) total FROM tb_solicitacoes GROUP BY status_solicitacao");
while ($pedido = mysqli_fetch_assoc($resultado))
    $pedidos[] = $pedido;

$movimentacoes = [];
$sqlMov = "SELECT e.id_entrada id_movimentacao,e.quantidade,e.data,e.motivo,e2.nome_epi,NULL nome_funcionario,'Entrada' tipo FROM tb_entrada e INNER JOIN tb_epis e2 ON e2.id_epi=e.id_epi UNION ALL SELECT s.id_saida id_movimentacao,s.quantidade,s.data,s.motivo,e.nome_epi,f.nome_completo nome_funcionario,'Saída' tipo FROM tb_saida s INNER JOIN tb_epis e ON e.id_epi=s.id_epi INNER JOIN tb_funcionarios f ON f.id_funcionario=s.id_funcionario ORDER BY data DESC,id_movimentacao DESC LIMIT 5";
$resultado = mysqli_query($con, $sqlMov);
while ($mov = mysqli_fetch_assoc($resultado))
    $movimentacoes[] = $mov;

$estoqueBaixo = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) total FROM tb_epis WHERE quantidade_estoque>0 AND quantidade_estoque<=estoque_minimo"))['total'];
$semEstoque = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) total FROM tb_epis WHERE quantidade_estoque<=0"))['total'];
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
                    <a href="home.php" class="ativo"><i class="fa-solid fa-house"></i><span>Início</span></a>
                    <a href="cadastro.php"><i class="fa-solid fa-user-plus"></i><span>Cadastrar funcionário</span></a>
                    <a href="funcionarios.php"><i class="fa-solid fa-users"></i><span>Funcionários</span></a>
                    <a href="relatorios.php"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
                    <a href="estoque.php"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
                    <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
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
                    <h1>Início</h1>
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
                <div class="home-container">

                    <div class="home-boas-vindas">
                        <div>
                            <span class="home-label">PAINEL ADMINISTRATIVO</span>
                            <h2>Olá, Administrador! <span>👋</span></h2>
                            <p>Acompanhe os principais dados do sistema de gestão de EPIs.</p>
                        </div>
                        <div class="home-boas-icone"><i class="fa-solid fa-shield-halved"></i></div>
                    </div>

                    <div class="home-resumo">

                        <div class="home-card">
                            <div class="home-card-icone funcionarios"><i class="fa-solid fa-users"></i></div>
                            <div><span>Funcionários
                                    cadastrados</span><strong><?php echo (int) $totalFuncionarios; ?></strong></div>
                            <a href="funcionarios.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>

                        <div class="home-card">
                            <div class="home-card-icone estoque"><i class="fa-solid fa-box"></i></div>
                            <div><span>EPIs em estoque</span><strong><?php echo (int) $totalEpis; ?></strong></div>
                            <a href="estoque.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>

                        <div class="home-card">
                            <div class="home-card-icone pedidos"><i class="fa-solid fa-clipboard-list"></i></div>
                            <div><span>Pedidos pendentes</span><strong><?php echo (int) $pedidosPendentes; ?></strong>
                            </div>
                            <a href="pedidos.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>

                        <div class="home-card">
                            <div class="home-card-icone andamento"><i class="fa-solid fa-spinner"></i></div>
                            <div><span>Processos em
                                    andamento</span><strong><?php echo (int) $processosAndamento; ?></strong></div>
                            <a href="andamento.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>

                    </div>

                    <div class="home-graficos">

                        <div class="home-grafico-card">
                            <div class="home-grafico-topo">
                                <div>
                                    <h2>Estoque de EPIs</h2>
                                    <p>Quantidade disponível de cada equipamento.</p>
                                </div>
                                <div class="home-grafico-icone"><i class="fa-solid fa-boxes-stacked"></i></div>
                            </div>
                            <div class="home-grafico-area"><canvas id="graficoEstoque"></canvas></div>
                        </div>

                        <div class="home-grafico-card">
                            <div class="home-grafico-topo">
                                <div>
                                    <h2>Status dos pedidos</h2>
                                    <p>Acompanhe a situação das solicitações.</p>
                                </div>
                                <div class="home-grafico-icone"><i class="fa-solid fa-chart-pie"></i></div>
                            </div>
                            <div class="home-grafico-area"><canvas id="graficoPedidos"></canvas></div>
                        </div>

                    </div>

                    <div class="home-inferior">

                        <div class="home-atividades">
                            <div class="home-secao-topo">
                                <div>
                                    <h2>Atividades recentes</h2>
                                    <p>Últimas movimentações registradas no sistema.</p>
                                </div>
                                <a href="movimentacoes.php">Ver todas <i class="fa-solid fa-arrow-right"></i></a>
                            </div>

                            <div class="home-lista-mov">
                                <?php if (!empty($movimentacoes)): ?>
                                    <?php foreach ($movimentacoes as $mov): ?>
                                        <div class="home-mov">
                                            <div
                                                class="home-mov-icone <?php echo $mov['tipo'] == 'Entrada' ? 'entrada' : 'saida'; ?>">
                                                <i
                                                    class="fa-solid <?php echo $mov['tipo'] == 'Entrada' ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                                            </div>
                                            <div class="home-mov-info">
                                                <strong><?php echo htmlspecialchars($mov['nome_epi']); ?></strong>
                                                <span><?php echo htmlspecialchars($mov['tipo']); ?><?php echo !empty($mov['nome_funcionario']) ? ' • ' . htmlspecialchars($mov['nome_funcionario']) : ''; ?></span>
                                            </div>
                                            <div class="home-mov-qtd <?php echo $mov['tipo'] == 'Entrada' ? 'entrada' : 'saida'; ?>">
                                                <?php echo $mov['tipo'] == 'Entrada' ? '+' : '-'; ?>        <?php echo (int) $mov['quantidade']; ?>
                                            </div>
                                            <div class="home-mov-data">
                                                <?php echo !empty($mov['data']) ? date("d/m/Y", strtotime($mov['data'])) : "Sem data"; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="home-vazio"><i class="fa-solid fa-inbox"></i><span>Nenhuma movimentação
                                            registrada.</span></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="home-alertas">
                            <div class="home-secao-topo">
                                <div>
                                    <h2>Alertas</h2>
                                    <p>Atenção aos itens abaixo.</p>
                                </div>
                                <i class="fa-solid fa-bell"></i>
                            </div>

                            <?php if ($estoqueBaixo > 0): ?>
                                <div class="home-alerta">
                                    <div class="home-alerta-icone amarelo"><i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div><strong>Estoque baixo</strong><span><?php echo (int) $estoqueBaixo; ?> EPI(s)
                                            precisam de reposição.</span></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($semEstoque > 0): ?>
                                <div class="home-alerta">
                                    <div class="home-alerta-icone vermelho"><i class="fa-solid fa-box-open"></i></div>
                                    <div><strong>Sem estoque</strong><span><?php echo (int) $semEstoque; ?> EPI(s) estão sem
                                            estoque.</span></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($pedidosPendentes > 0): ?>
                                <div class="home-alerta">
                                    <div class="home-alerta-icone roxo"><i class="fa-solid fa-clock"></i></div>
                                    <div><strong>Pedidos pendentes</strong><span><?php echo (int) $pedidosPendentes; ?>
                                            pedido(s) aguardam análise.</span></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($estoqueBaixo == 0 && $semEstoque == 0 && $pedidosPendentes == 0): ?>
                                <div class="home-alerta-ok">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <div><strong>Tudo em ordem!</strong><span>Não existem alertas no momento.</span></div>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="home-acessos">
                        <div class="home-acessos-topo">
                            <div>
                                <h2>Acessos rápidos</h2>
                                <p>Encontre rapidamente as principais funções.</p>
                            </div>
                        </div>
                        <div class="home-acessos-grid">
                            <a href="cadastro.php"><i class="fa-solid fa-user-plus"></i><span><strong>Cadastrar
                                        funcionário</strong><small>Adicionar novo funcionário</small></span><i
                                    class="fa-solid fa-arrow-right"></i></a>
                            <a href="estoque.php"><i class="fa-solid fa-box"></i><span><strong>Ver
                                        estoque</strong><small>Consultar EPIs disponíveis</small></span><i
                                    class="fa-solid fa-arrow-right"></i></a>
                            <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span><strong>Ver
                                        pedidos</strong><small>Gerenciar solicitações</small></span><i
                                    class="fa-solid fa-arrow-right"></i></a>
                            <a href="movimentacoes.php"><i
                                    class="fa-solid fa-arrow-right-arrow-left"></i><span><strong>Movimentações</strong><small>Consultar
                                        entradas e saídas</small></span><i class="fa-solid fa-arrow-right"></i></a>
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
        window.dadosHome = {
            estoque: <?php echo json_encode($estoque, JSON_UNESCAPED_UNICODE); ?>,
            pedidos: <?php echo json_encode($pedidos, JSON_UNESCAPED_UNICODE); ?>
        };
    </script>
    <script src="js/home.js"></script>
</body>

</html>
<?php mysqli_close($con); ?>