<?php
session_start();
$titulopagina = "EPI Control - Relatórios";
require_once "config/conexao.php";
$con = conectar();

$sql = "SELECT f.*,s.id_solicitacao,s.status_solicitacao,s.data_solicitacao,s.justificativa,e.nome_epi,e.codigo FROM tb_funcionarios f LEFT JOIN tb_solicitacoes s ON s.id_solicitacao=(SELECT MAX(s2.id_solicitacao) FROM tb_solicitacoes s2 WHERE s2.id_funcionario=f.id_funcionario) LEFT JOIN tb_epis e ON e.id_epi=s.id_epi ORDER BY f.nome_completo ASC LIMIT 9";
$resultado = mysqli_query($con, $sql);

if (!$resultado) {
    die("Erro ao buscar relatórios: " . mysqli_error($con));
}

$totalRelatorios = mysqli_num_rows($resultado);
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
                    <a href="cadastro.php"><i class="fa-regular fa-user"></i><span>Cadastrar funcionário</span></a>
                    <a href="funcionarios.php"><i class="fa-solid fa-users"></i><span>Funcionários</span></a>
                    <a href="relatorios.php" class="ativo"><i
                            class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
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

        <div class="conteudo">

            <header class="topbar">
                <div class="titulo-pagina">
                    <span>EPI CONTROL</span>
                    <h1>Relatórios</h1>
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
                <section class="funcionarios-container">

                    <div class="cabecalho-conteudo">
                        <div class="titulo-completo">
                            <div class="icone-titulo"><i class="fa-solid fa-chart-column"></i></div>
                            <div>
                                <h2>Relatórios gerados</h2>
                                <p>Confira os relatórios e informações dos funcionários.</p>
                            </div>
                        </div>

                        <div class="acoes-topo">
                            <div class="contador-funcionarios">
                                <i class="fa-solid fa-file-lines"></i>
                                <div><span>Total de funcionários</span><strong><?php echo $totalRelatorios; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="linha-divisoria"></div>

                    <div class="lista-funcionarios">

                        <?php while ($funcionario = mysqli_fetch_assoc($resultado)): ?>

                            <?php
                            $foto = !empty($funcionario['foto_url']) ? $funcionario['foto_url'] : 'img/boy.png';
                            $status = !empty($funcionario['status_solicitacao']) ? $funcionario['status_solicitacao'] : 'Sem pedido';
                            $statusClasse = strtolower($status);
                            if ($statusClasse == "aprovado") {
                                $classeStatus = "status-aprovado";
                            } elseif ($statusClasse == "recusado") {
                                $classeStatus = "status-recusado";
                            } elseif ($statusClasse == "pendente") {
                                $classeStatus = "status-pendente";
                            } else {
                                $classeStatus = "status-sem-pedido";
                            }
                            ?>

                            <div class="funcionario-card">

                                <div class="card-topo">
                                    <div class="foto-funcionario">
                                        <img src="<?php echo htmlspecialchars($foto); ?>"
                                            alt="<?php echo htmlspecialchars($funcionario['nome_completo']); ?>">
                                    </div>

                                    <div class="status">
                                        <span></span>Ativo
                                    </div>
                                </div>

                                <div class="dados-funcionario">

                                    <h2><?php echo htmlspecialchars($funcionario['nome_completo']); ?></h2>

                                    <div class="informacao">
                                        <i class="fa-solid fa-building"></i>
                                        <span>Setor: <?php echo htmlspecialchars($funcionario['setor']); ?></span>
                                    </div>

                                    <div class="comentario-relatorio">
                                        <i class="fa-solid fa-shield-halved"></i>

                                        <?php if (!empty($funcionario['nome_epi'])): ?>

                                            <span>
                                                EPI: <?php echo htmlspecialchars($funcionario['nome_epi']); ?>
                                                <?php if (!empty($funcionario['codigo'])): ?>
                                                    - Código: <?php echo htmlspecialchars($funcionario['codigo']); ?>
                                                <?php endif; ?>
                                            </span>

                                        <?php else: ?>

                                            <span>Nenhum pedido de EPI registrado.</span>

                                        <?php endif; ?>

                                    </div>

                                    <?php if (!empty($funcionario['id_solicitacao'])): ?>

                                        <div class="informacao">
                                            <i class="fa-solid fa-file-lines"></i>
                                            <span>Pedido #<?php echo $funcionario['id_solicitacao']; ?></span>
                                        </div>

                                        <div class="informacao">
                                            <i class="fa-regular fa-calendar"></i>
                                            <span><?php echo date("d/m/Y H:i", strtotime($funcionario['data_solicitacao'])); ?></span>
                                        </div>

                                        <div class="informacao">
                                            <i class="fa-solid fa-circle-info"></i>
                                            <span
                                                class="<?php echo $classeStatus; ?>"><?php echo htmlspecialchars($status); ?></span>
                                        </div>

                                    <?php endif; ?>

                                </div>

                                <div class="card-footer">

                                    <?php if (!empty($funcionario['id_solicitacao'])): ?>

                                        <a href="pedidos.php" class="btn-pedir-epi">
                                            <span>Ver pedido</span>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </a>

                                    <?php else: ?>

                                        <a href="informacoes.php?id=<?php echo $funcionario['id_funcionario']; ?>"
                                            class="btn-pedir-epi">
                                            <span>Ver funcionário</span>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </a>

                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    </div>

                </section>

                <div class="frase-seguranca">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Segurança em cada detalhe, proteção em cada escolha.</span>
                </div>

            </main>
        </div>
    </div>

</body>

</html>
<?php mysqli_close($con); ?>