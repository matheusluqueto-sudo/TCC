<?php
session_start();
require_once 'config/conexao.php';
$id=isset($_GET['id'])?(int)$_GET['id']:0;
$tipo=isset($_GET['tipo'])?$_GET['tipo']:'';
if($id>0&&in_array($tipo,['Entrada','Saída'])){
$conexao=conectar();
if($tipo==='Entrada')$sql="DELETE FROM tb_entrada WHERE id_entrada=?";
else $sql="DELETE FROM tb_saida WHERE id_saida=?";
$stmt=mysqli_prepare($conexao,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conexao);
}
header('Location: movimentacoes.php');
exit;
?>