<?php
session_start();
require_once('database/Connection.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$dados = json_decode(file_get_contents("php://input"), true);

$user = trim($dados['usuario'] ?? '');
$pass = trim($dados['senha'] ?? '');

if($user=='' || $pass=='') {
    echo json_encode(["ok" => false, "mensagem" => "Preencha os campos usuário e senha corretamente."]);
    exit;
}

try {
    $pdo = Connection::getPdo();
    $state = $pdo->prepare("SELECT id,user,tipo FROM equipe WHERE user = ? AND password = ?");
    $state->execute([$user, md5($pass)]);

    $usr=$state->fetch(PDO::FETCH_ASSOC);

    if($usr) {
        // cria sessão única pro usuário logado
        $_SESSION['user_id'] = $usr['id'];
        $_SESSION['username'] = $usr['user'];
        $_SESSION['tipo_user'] = $usr['tipo'];

        echo json_encode(["ok"=>true, "mensagem" => "login feito"]);
        exit;
    } 

    echo json_encode(["ok" => false, "mensagem" => "Usuário ou senha incorretos."]);
} catch (PDOException $e) {
    echo json_encode(["ok" => false, "mensagem" => "Erro ao conectar com o banco de dados: ".$e->getMessage()]);
}