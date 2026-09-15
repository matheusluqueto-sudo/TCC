<?php
session_start();
$titulopagina = "EPI Control - Movimentações";
require_once "config/conexao.php";
$con = conectar();
$sql = "SELECT e.id_entrada AS id_movimentacao,e.id_epi,e.quantidade,e.data,e.motivo,NULL AS id_funcionario,NULL AS nome_funcionario,e2.nome_epi,e2.codigo,'Entrada' AS tipo FROM tb_entrada e INNER JOIN tb_epis e2 ON e2.id_epi=e.id_epi UNION ALL SELECT s.id_saida AS id_movimentacao,s.id_epi,s.quantidade,s.data,s.motivo,s.id_funcionario,f.nome_completo AS nome_funcionario,e.nome_epi,e.codigo,'Saída' AS tipo FROM tb_saida s INNER JOIN tb_epis e ON e.id_epi=s.id_epi INNER JOIN tb_funcionarios f ON f.id_funcionario=s.id_funcionario ORDER BY data DESC,id_movimentacao DESC";
$resultado = mysqli_query($con, $sql);
if (!$resultado)
    die("Erro ao buscar movimentações: " . mysqli_error($con));
$movimentacoes = [];
$totalMovimentacoes = 0;
$totalEntradas = 0;
$totalSaidas = 0;
$movimentacoesHoje = 0;
while ($mov = mysqli_fetch_assoc($resultado)) {
    $movimentacoes[] = $mov;
    $totalMovimentacoes++;
    if ($mov['tipo'] == "Entrada")
        $totalEntradas++;
    if ($mov['tipo'] == "Saída")
        $totalSaidas++;
    if (!empty($mov['data']) && $mov['data'] == date("Y-m-d"))
        $movimentacoesHoje++;
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
                    <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
                    <a href="movimentacoes.php" class="ativo"><i
                            class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver movimentações</span></a>
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
                    <h1>Movimentações</h1>
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
                                <div class="icone-titulo"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                                <div>
                                    <h2>Movimentações de estoque</h2>
                                    <p>Acompanhe todas as entradas e saídas de EPIs do sistema.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="linha-divisoria"></div>
                    <div class="movimentacoes-resumo">
                        <div class="mov-card">
                            <div class="mov-card-icone"><i class="fa-solid fa-arrows-rotate"></i></div>
                            <div><span>Total de movimentações</span><strong><?php echo $totalMovimentacoes; ?></strong>
                            </div>
                        </div>
                        <div class="mov-card">
                            <div class="mov-card-icone entrada"><i class="fa-solid fa-arrow-down"></i></div>
                            <div><span>Entradas</span><strong><?php echo $totalEntradas; ?></strong></div>
                        </div>
                        <div class="mov-card">
                            <div class="mov-card-icone saida"><i class="fa-solid fa-arrow-up"></i></div>
                            <div><span>Saídas</span><strong><?php echo $totalSaidas; ?></strong></div>
                        </div>
                        <div class="mov-card">
                            <div class="mov-card-icone hoje"><i class="fa-regular fa-calendar"></i></div>
                            <div><span>Movimentações hoje</span><strong><?php echo $movimentacoesHoje; ?></strong></div>
                        </div>
                    </div>
                    <div class="movimentacoes-painel">
                        <div class="movimentacoes-topo">
                            <div>
                                <h2>Histórico de movimentações</h2>
                                <p>Confira as entradas e saídas registradas no estoque.</p>
                            </div>
                            <div class="filtro-movimentacoes">
                                <i class="fa-solid fa-filter"></i>
                                <select id="filtroMovimentacao">
                                    <option value="todos">Todas</option>
                                    <option value="Entrada">Entradas</option>
                                    <option value="Saída">Saídas</option>
                                </select>
                            </div>
                        </div>
                        <div class="lista-movimentacoes" id="listaMovimentacoes">
                            <?php if (!empty($movimentacoes)): ?>
                                <?php foreach ($movimentacoes as $mov): ?>
                                    <?php
                                    $entrada = $mov['tipo'] == "Entrada";
                                    $classe = $entrada ? "mov-entrada" : "mov-saida";
                                    $icone = $entrada ? "fa-arrow-down" : "fa-arrow-up";
                                    $data = !empty($mov['data']) ? date("d/m/Y", strtotime($mov['data'])) : "Não informada";
                                    ?>
                                    <div class="movimentacao-item" data-tipo="<?php echo $mov['tipo']; ?>">
                                        <div class="mov-tipo <?php echo $classe; ?>"><i
                                                class="fa-solid <?php echo $icone; ?>"></i></div>
                                        <div class="mov-info">
                                            <h3><?php echo htmlspecialchars($mov['nome_epi']); ?></h3>
                                            <span><i class="fa-solid fa-barcode"></i> Código:
                                                <?php echo !empty($mov['codigo']) ? htmlspecialchars($mov['codigo']) : "Não informado"; ?></span>
                                        </div>
                                        <div class="mov-funcionario">
                                            <?php if (!$entrada && !empty($mov['nome_funcionario'])): ?>
                                                <span class="mov-label">Funcionário</span>
                                                <strong><i
                                                        class="fa-solid fa-user"></i><?php echo htmlspecialchars($mov['nome_funcionario']); ?></strong>
                                            <?php else: ?>
                                                <span class="mov-label">Tipo</span>
                                                <strong><i class="fa-solid fa-box"></i>Entrada de estoque</strong>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mov-quantidade <?php echo $classe; ?>">
                                            <span>Quantidade</span>
                                            <strong><?php echo $entrada ? "+" : "-"; ?><?php echo (int) $mov['quantidade']; ?></strong>
                                        </div>
                                        <div class="mov-data">
                                            <span><i class="fa-regular fa-calendar"></i><?php echo $data; ?></span>
                                            <?php if (!empty($mov['motivo'])): ?>
                                                <small><?php echo htmlspecialchars($mov['motivo']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mov-acoes">
                                            <button type="button" class="btn-excluir-mov"
                                                onclick="if(confirm('Tem certeza que deseja excluir esta movimentação? Essa ação não poderá ser desfeita.'))window.location.href='excluirmovimentacao.php?id=<?php echo $mov['id_movimentacao']; ?>&tipo=<?php echo urlencode($mov['tipo']); ?>'"><i
                                                    class="fa-solid fa-trash"></i>Excluir</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="sem-movimentacoes">
                                    <i class="fa-solid fa-box-open"></i>
                                    <h3>Nenhuma movimentação registrada</h3>
                                    <p>Quando uma entrada ou saída for registrada, ela aparecerá aqui.</p>
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
    <script src="js/movimentacoes.js"></script>
</body>

</html>
<?php mysqli_close($con); ?>