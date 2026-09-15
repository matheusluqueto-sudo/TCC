<?php
session_start();
$titulopagina = "EPI Control - Meu EPI";
require_once "config/conexao.php";
if (!isset($_SESSION['id_funcionario'])) {
    header("Location: login.php");
    exit;
}
$con = conectar();
$id_funcionario = (int) $_SESSION['id_funcionario'];
$stmt = mysqli_prepare($con, "SELECT nome_completo,cargo,foto_url FROM tb_funcionarios WHERE id_funcionario=?");
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

$stmtEpi = mysqli_prepare($con, "SELECT e.id_epi,e.nome_epi,e.codigo,e.ca FROM tb_saida s INNER JOIN tb_epis e ON e.id_epi=s.id_epi WHERE s.id_funcionario=? GROUP BY e.id_epi,e.nome_epi,e.codigo,e.ca ORDER BY e.nome_epi ASC");
mysqli_stmt_bind_param($stmtEpi, "i", $id_funcionario);
mysqli_stmt_execute($stmtEpi);
$epis = mysqli_stmt_get_result($stmtEpi);
$episArray = [];
while ($epi = mysqli_fetch_assoc($epis)) {
    $episArray[] = $epi;
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
                    <a href="homefunc.php"><i class="fa-solid fa-house"></i><span>Início</span></a>
                    <a href="meuepi.php" class="ativo"><i class="fa-solid fa-shield-halved"></i><span>Meu EPI</span></a>
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
                    <h1>Meu EPI</h1>
                </div>
                <div class="topbar-direita">
                    <div class="notificacao"><i class="fa-regular fa-bell"></i></div>
                    <div class="perfil">
                        <div class="perfil-icone"><img src="<?php echo htmlspecialchars($foto); ?>" alt="Perfil"></div>
                        <div class="perfil-texto">
                            <strong><?php echo htmlspecialchars($primeiroNome); ?></strong>
                            <span><?php echo htmlspecialchars($funcionario['cargo']); ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <section class="pagina">
                <div class="funcionarios-container meuepi-area">

                    <div class="cabecalho-conteudo">
                        <div class="descricao">
                            <div class="titulo-completo">
                                <div class="icone-titulo"><i class="fa-solid fa-shield-halved"></i></div>
                                <div>
                                    <h2>Meu EPI</h2>
                                    <p class="meuepi-instrucoes">Arraste cada equipamento para o local correto do corpo.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="linha-divisoria"></div>

                    <div class="meuepi-jogo">

                        <div class="lista-epis">
                            <h3>Seus EPIs</h3>
                            <p>Arraste um equipamento para o corpo.</p>

                            <?php if (count($episArray) > 0): ?>

                                <?php foreach ($episArray as $epi):
                                    $nomeEpi = $epi['nome_epi'];
                                    $nomeLower = strtolower($nomeEpi);
                                    $tipo = 'generico';
                                    $icone = 'fa-shield-halved';
                                    $destino = 'drop-rosto';

                                    if (strpos($nomeLower, 'óculos') !== false || strpos($nomeLower, 'oculos') !== false) {
                                        $tipo = 'oculos';
                                        $icone = 'fa-glasses';
                                        $destino = 'drop-olhos';
                                    } elseif (strpos($nomeLower, 'auricular') !== false) {
                                        $tipo = 'auricular';
                                        $icone = 'fa-headphones';
                                        $destino = 'drop-orelha';
                                    } elseif (strpos($nomeLower, 'luva') !== false) {
                                        $tipo = 'luvas';
                                        $icone = 'fa-hand';
                                        $destino = 'drop-mao';
                                    } elseif (strpos($nomeLower, 'facial') !== false) {
                                        $tipo = 'facial';
                                        $icone = 'fa-shield-halved';
                                        $destino = 'drop-rosto';
                                    }
                                    ?>

                                    <div class="epi-arrastavel" draggable="true" data-tipo="<?php echo $tipo; ?>"
                                        data-destino="<?php echo $destino; ?>"
                                        data-nome="<?php echo htmlspecialchars($nomeEpi); ?>">
                                        <div class="epi-icone"><i class="fa-solid <?php echo $icone; ?>"></i></div>
                                        <div class="epi-info">
                                            <strong><?php echo htmlspecialchars($nomeEpi); ?></strong>
                                            <span><?php echo !empty($epi['ca']) ? 'CA ' . $epi['ca'] : 'Arraste para usar'; ?></span>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="meuepi-vazio">
                                    <i class="fa-solid fa-box-open"></i>
                                    <br><br>
                                    Você ainda não possui EPIs atribuídos.
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="area-corpo">
                            <div class="corpo-titulo"><i class="fa-solid fa-person"></i> Equipamento de segurança</div>

                            <div class="corpo">

                                <div class="corpo-cabeca">
                                    <div class="corpo-cabelo"></div>
                                    <div class="corpo-rosto"><span>•</span><span>•</span></div>
                                </div>

                                <div class="corpo-pescoco"></div>
                                <div class="corpo-tronco"></div>
                                <div class="corpo-braco-esq"></div>
                                <div class="corpo-braco-dir"></div>
                                <div class="corpo-mao-esq"></div>
                                <div class="corpo-mao-dir"></div>
                                <div class="corpo-perna-esq"></div>
                                <div class="corpo-perna-dir"></div>
                                <div class="corpo-pe-esq"></div>
                                <div class="corpo-pe-dir"></div>

                                <div class="drop-zone drop-olhos" data-destino="drop-olhos">Óculos</div>
                                <div class="drop-zone drop-orelha" data-destino="drop-orelha">Ouvido</div>
                                <div class="drop-zone drop-orelha-dir" data-destino="drop-orelha">Ouvido</div>
                                <div class="drop-zone drop-mao" data-destino="drop-mao">Mão</div>
                                <div class="drop-zone drop-mao-dir" data-destino="drop-mao">Mão</div>
                                <div class="drop-zone drop-rosto" data-destino="drop-rosto">Rosto</div>

                                <div class="epi-no-corpo epi-oculos-corpo" id="uso-oculos"><i
                                        class="fa-solid fa-glasses"></i> Óculos</div>
                                <div class="epi-no-corpo epi-auricular-corpo" id="uso-auricular"><i
                                        class="fa-solid fa-headphones"></i> Protetor</div>
                                <div class="epi-no-corpo epi-luva-esq-corpo" id="uso-luva-esq"><i
                                        class="fa-solid fa-hand"></i> Luva</div>
                                <div class="epi-no-corpo epi-luva-dir-corpo" id="uso-luva-dir"><i
                                        class="fa-solid fa-hand"></i> Luva</div>

                            </div>
                        </div>

                        <div class="status-epis">
                            <h3><i class="fa-solid fa-circle-check"></i> EPIs em uso</h3>
                            <div class="status-lista" id="statusLista">
                                <div class="status-item"><i class="fa-solid fa-circle-info"></i> Arraste um EPI para
                                    começar.</div>
                            </div>
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

    <script src="js/meuepi.js"></script>
</body>

</html>
<?php mysqli_stmt_close($stmt);
mysqli_stmt_close($stmtEpi);
mysqli_close($con); ?>