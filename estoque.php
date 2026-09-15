<?php
session_start();
$titulopagina = "EPI Control - Estoque";
require_once "config/conexao.php";
$con = conectar();
$sql = "SELECT * FROM tb_epis ORDER BY nome_epi ASC";
$resultado = mysqli_query($con, $sql);
if (!$resultado)
    die("Erro ao buscar estoque: " . mysqli_error($con));
$epis = [];
while ($epi = mysqli_fetch_assoc($resultado))
    $epis[] = $epi;
$totalTipos = count($epis);
$totalQuantidade = 0;
$estoqueNormal = 0;
$estoqueBaixo = 0;
$semEstoque = 0;
foreach ($epis as $epi) {
    $qtd = (int) $epi['quantidade_estoque'];
    $min = (int) $epi['estoque_minimo'];
    $totalQuantidade += $qtd;
    if ($qtd <= 0)
        $semEstoque++;
    elseif ($qtd <= $min)
        $estoqueBaixo++;
    else
        $estoqueNormal++;
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
                    <a href="estoque.php" class="ativo"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
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
                    <h1>Ver Estoque</h1>
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
                                <div class="icone-titulo"><i class="fa-solid fa-boxes-stacked"></i></div>
                                <div>
                                    <h2>Estoque de EPIs</h2>
                                    <p>Gerencie e acompanhe os equipamentos disponíveis no sistema.</p>
                                </div>
                            </div>
                        </div>

                        <div class="acoes-topo">
                            <div class="contador-funcionarios">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <div><span>Total em estoque</span><strong><?php echo $totalQuantidade; ?></strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="linha-divisoria"></div>

                    <div class="cards-grid">
                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-box"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $totalTipos; ?></span>
                            <span class="info-card">Tipos de EPIs cadastrados</span>
                        </div>

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-circle-check"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $estoqueNormal; ?></span>
                            <span class="info-card">Estoque normal</span>
                        </div>

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $estoqueBaixo; ?></span>
                            <span class="info-card">Estoque baixo</span>
                        </div>

                        <div class="card">
                            <div class="card-cabecalho">
                                <div class="card-icone"><i class="fa-solid fa-circle-xmark"></i></div>
                            </div>
                            <span class="numero-card"><?php echo $semEstoque; ?></span>
                            <span class="info-card">Sem estoque</span>
                        </div>
                    </div>

                    <div class="estoque-grafico"><br>
                        <div class="cabecalho-conteudo">
                            <div class="descricao">
                                <div class="titulo-completo">
                                    <div class="icone-titulo"><i class="fa-solid fa-boxes-stacked"></i></div>
                                    <div>
                                        <h2>Estoque de EPIs</h2>
                                        <p>Gerencie e acompanhe os equipamentos disponíveis no sistema.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grafico-area">
                            <canvas id="graficoEstoque"></canvas>
                        </div>
                    </div>

                    <div class="estoque-tabela">
                        <div class="titulo-tabela">
                            <div>
                                <h2>EPIs cadastrados</h2>
                                <p>Confira as informações e a situação atual do estoque.</p>
                            </div>
                            <div class="pesquisa-estoque">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="pesquisaEpi" placeholder="Pesquisar EPI...">
                            </div>
                        </div>

                        <div class="tabela-responsiva">
                            <table class="tabela" id="tabelaEstoque">
                                <thead>
                                    <tr>
                                        <th>EPI</th>
                                        <th>Código</th>
                                        <th>CA</th>
                                        <th>Quantidade</th>
                                        <th>Estoque mínimo</th>
                                        <th>Validade</th>
                                        <th>Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($epis)): ?>
                                        <?php foreach ($epis as $epi): ?>
                                            <?php
                                            $qtd = (int) $epi['quantidade_estoque'];
                                            $min = (int) $epi['estoque_minimo'];
                                            if ($qtd <= 0) {
                                                $classe = "badge-vermelho";
                                                $situacao = "Sem estoque";
                                            } elseif ($qtd <= $min) {
                                                $classe = "badge-amarelo";
                                                $situacao = "Estoque baixo";
                                            } else {
                                                $classe = "badge-verde";
                                                $situacao = "Normal";
                                            }
                                            ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($epi['nome_epi']); ?></strong></td>
                                                <td><?php echo !empty($epi['codigo']) ? htmlspecialchars($epi['codigo']) : 'Não informado'; ?>
                                                </td>
                                                <td><?php echo !empty($epi['ca']) ? htmlspecialchars($epi['ca']) : 'Não informado'; ?>
                                                </td>
                                                <td><strong><?php echo $qtd; ?></strong></td>
                                                <td><?php echo $min; ?></td>
                                                <td><?php echo $epi['validade'] !== null && $epi['validade'] !== '' ? htmlspecialchars($epi['validade']) . ' meses' : 'Não informado'; ?>
                                                </td>
                                                <td><span class="badge <?php echo $classe; ?>"><?php echo $situacao; ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="sem-resultado">Nenhum EPI cadastrado no estoque.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const nomesEpis = <?php echo json_encode(array_column($epis, 'nome_epi'), JSON_UNESCAPED_UNICODE); ?>;
        const quantidadesEpis = <?php echo json_encode(array_map('intval', array_column($epis, 'quantidade_estoque'))); ?>;
    </script>
    <script src="js/estoque.js"></script>
</body>

</html>
<?php mysqli_close($con); ?>