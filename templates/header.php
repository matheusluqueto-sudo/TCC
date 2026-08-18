<?php
session_start();
require_once 'config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo_pagina ?? 'Biblios' ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">Biblios</div>
        <nav>
            <a href="home.php">Home</a>
            <?php if (isset($_SESSION['usuariologado'])): ?>
                <a href="livro.php">Livros</a>
                <a href="leitor.php">Leitores</a>
                <a href="emprestimo.php">Empréstimos</a>
                <a href="logout.php" class="logout">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>