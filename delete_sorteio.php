<?php
require_once('inclusoes/verif_sessao.php');
require_once("database/Connection.php");

$id = $_POST['id'] ?? '';

if (!$id || !is_numeric($id)) {
    echo json_encode(["ok"=>false, "msg"=>"ID inválido"]);
    exit;
}

if($_SESSION['tipo_user'] != "QUALIDADE") {
    echo json_encode(["ok"=>false, "msg"=>"Somente admins podem excluir sorteios."]);
    exit;
}

try {
    $pdo = Connection::getPdo();
    $stmt = $pdo->prepare("DELETE FROM sorteio WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["ok"=>true, "msg"=>"Sorteio excluído com sucesso (participantes atrelados também foram removidos)."]);
} catch (PDOException $e) {
    echo json_encode(["ok"=>false, "msg"=>"Erro: ".$e->getMessage()]);
}