<?php
require_once('inclusoes/verif_sessao.php');
require_once('database/Connection.php');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    exit;
}

try {

    $pdo = Connection::getPdo();
    // pega os sorteados a partir de um sorteio específico
    $stmt = $pdo->prepare("SELECT matricula FROM participantes WHERE sorteio_id = ? AND flag_sorteio = 1");
    $stmt->execute([$id]);
    $sorteados = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if(!$sorteados) {
        echo json_encode(["ok" => false, "msg" => "Ainda não foram registrados sorteios."]);
        exit;
    }

    echo json_encode(["ok" => true, "sorteados" => $sorteados]);
} catch (PDOException $e) {
    echo json_encode(["ok" => false, "msg" => $sorteados]);
}
