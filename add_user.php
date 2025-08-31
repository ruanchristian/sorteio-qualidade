<?php
require_once('inclusoes/verif_sessao.php');
require_once('database/Connection.php');

$user = trim($_POST['user'] ?? '');
$pass = trim($_POST['pass'] ?? '');
$type = $_POST['type'] ?? '';

if($_SESSION['tipo_user'] != "QUALIDADE") {
    http_response_code(401);
    echo json_encode(['erro' => 'Somente admins podem executar essa ação.']);
    exit();
}

if($user=='' || $pass=='' || $type=='') {
    http_response_code(400);
    echo json_encode(['erro' => 'Preencha todos os campos corretamente!']);
    exit();
}

$pdo = Connection::getPdo();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM equipe WHERE user = ?");
$stmt->execute([$user]);

if($stmt->fetchColumn()) {
    http_response_code(409);
    echo json_encode(['erro' => 'Esse usuário já está cadastrado, escolha outro nome.']);
    exit();
}

$stmt = $pdo->prepare("INSERT INTO equipe (user, password, tipo) VALUES (?, ?, ?)");
$stmt->execute([$user,md5($pass),$type]);
echo json_encode(['sucesso' => 'Usuário cadastrado com sucesso!']);