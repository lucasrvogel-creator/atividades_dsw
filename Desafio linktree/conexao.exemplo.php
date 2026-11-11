<?php
// Copie este arquivo para conexao.php e configure seu banco local
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'linktree';

$conexao = new mysqli($servidor, $usuario, $senha, $banco);
$conexao->set_charset('utf8mb4');
