<?php
require('inclusoes/verif_sessao.php');
require_once('database/Connection.php');

$pdo = Connection::getPdo();

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(["ok" => false, "msg" => "ID de sorteio inválido."]);
    exit;
}

try {
    // pega 1 participante aleatoriamente que ainda não foi sorteado
    $stmt = $pdo->prepare("
        SELECT * FROM participantes 
        WHERE flag_sorteio = 0 AND sorteio_id = ? 
        ORDER BY RAND() LIMIT 1
    ");
    $stmt->execute([$id]);
    $sorteado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sorteado) {
        echo json_encode(["ok" => false, "msg" => "Não há mais participantes para sortear."]);
        exit;
    }

    // atualiza a flag para marcar como já sorteado
    $upd = $pdo->prepare("UPDATE participantes SET flag_sorteio = 1 WHERE id = ?");
    $upd->execute([$sorteado['id']]);

    sleep(5); // aciona delay de 5 segundos
    
    $matriculaSort = substr($sorteado['matricula'], 3);
    $empresa = str_starts_with($sorteado['matricula'],"COC")?'COCALQUI':'ANIGER';

    echo json_encode(["ok" => true, "mat" => $matriculaSort, "emp" => $empresa]);
} catch (Exception $e) {
    echo json_encode(["ok" => false, "msg" => "Erro ao sortear: ".$e->getMessage()]);
}