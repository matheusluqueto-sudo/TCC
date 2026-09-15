<?php
session_start();
require_once 'config/conexao.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
    $conexao = conectar();
    mysqli_begin_transaction($conexao);
    try {
        $sql = "DELETE FROM tb_saida WHERE id_funcionario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $sql = "DELETE FROM tb_solicitacoes WHERE id_funcionario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $sql = "DELETE FROM tb_funcionarios WHERE id_funcionario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_commit($conexao);
    } catch (Exception $e) {
        mysqli_rollback($conexao);
    }
    mysqli_close($conexao);
}
header('Location: funcionarios.php');
exit;
?>