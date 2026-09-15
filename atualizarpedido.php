<?php
session_start();
require_once "config/conexao.php";
$id = isset($_POST['id_solicitacao']) ? (int) $_POST['id_solicitacao'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
if ($id > 0 && in_array($status, ['Aprovado', 'Recusado'])) {
    $con = conectar();
    $stmt = mysqli_prepare($con, "UPDATE tb_solicitacoes SET status_solicitacao=? WHERE id_solicitacao=?");
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
header("Location: pedidos.php");
exit;
?>