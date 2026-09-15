<?php
session_start();
$titulopagina = "EPI Control - Funcionários";
require_once "config/conexao.php";
$con = conectar();
$sql = "SELECT * FROM tb_funcionarios ORDER BY nome_completo ASC";
$resultado = mysqli_query($con, $sql);
if (!$resultado)
    die("Erro ao buscar funcionários: " . mysqli_error($con));
$totalFuncionarios = mysqli_num_rows($resultado);
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
                    <a href="funcionarios.php" class="ativo"><i
                            class="fa-solid fa-users"></i><span>Funcionários</span></a>
                    <a href="relatorios.php"><i class="fa-solid fa-chart-column"></i><span>Relatórios</span></a>
                    <a href="estoque.php"><i class="fa-solid fa-box"></i><span>Ver estoque</span></a>
                    <a href="pedidos.php"><i class="fa-solid fa-clipboard-list"></i><span>Ver pedidos</span></a>
                    <a href="movimentacoes.php"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Ver
                            movimentações</span></a>
                    <a href="andamento.php"><i class="fa-solid fa-spinner"></i><span>Em andamento</span></a>
                    <a href="admfunc.php"><i class="fa-solid fa-arrow-left"></i><span>Voltar</span></a>
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
                <div class="titulo-pagina"><span>Painel administrativo</span>
                    <h1>Funcionários</h1>
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
                                <div class="icone-titulo"><i class="fa-solid fa-users"></i></div>
                                <div>
                                    <h2>Funcionários cadastrados</h2>
                                    <p>Gerencie e consulte os funcionários cadastrados no sistema.</p>
                                </div>
                            </div>
                        </div>

                        <div class="acoes-topo">
                            <div class="contador-funcionarios">
                                <i class="fa-solid fa-users"></i>
                                <div><span>Total</span><strong><?php echo $totalFuncionarios; ?></strong></div>
                            </div>

                            <div class="campo-pesquisa" id="campoPesquisa">
                                <i class="fa-solid fa-magnifying-glass" id="iconePesquisa"></i>
                                <input type="text" id="pesquisaFuncionario" placeholder="Pesquisar funcionário...">
                            </div>

                        </div>
                    </div>

                    <div class="linha-divisoria"></div>

                    <div class="lista-funcionarios" id="listaFuncionarios">

                        <?php while ($funcionario = mysqli_fetch_assoc($resultado)): ?>
                            <?php $foto = !empty($funcionario['foto_url']) ? $funcionario['foto_url'] : 'img/boy.png'; ?>

                            <div class="funcionario-card">
                                <div class="card-topo">
                                    <div class="foto-funcionario">
                                        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Funcionário">
                                    </div>
                                    <div class="status"><span></span>Ativo</div>
                                </div>

                                <div class="dados-funcionario">
                                    <h2><?php echo htmlspecialchars($funcionario['nome_completo']); ?></h2>

                                    <div class="informacao">
                                        <i class="fa-solid fa-briefcase"></i>
                                        <span><?php echo htmlspecialchars($funcionario['cargo']); ?></span>
                                    </div>

                                    <div class="informacao">
                                        <i class="fa-solid fa-building"></i>
                                        <span><?php echo htmlspecialchars($funcionario['setor']); ?></span>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <a href="informacoes.php?id=<?php echo $funcionario['id_funcionario']; ?>"
                                        class="btn-informacoes">
                                        Ver informações <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                        <?php endwhile; ?>

                    </div>

                    <div class="frase-seguranca">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Segurança em cada detalhe, proteção em cada escolha.</span>
                    </div>

                </div>
            </section>
        </main>
    </div>

    <script src="js/funcionarios.js"></script>
</body>

</html>
<?php mysqli_close($con); ?>