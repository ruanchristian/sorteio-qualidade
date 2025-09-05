<?php
session_start();
date_default_timezone_set('America/Fortaleza');
require_once('database/Connection.php');

$matricula = trim($_POST['matricula'] ?? '');
$empresa = $_POST['empresa'] ?? '';
$sorteio_id = $_POST['sorteio_id'] ?? '';
$autor = $_SESSION['user_id'];

if (!$matricula || !$empresa || !$sorteio_id) {
    echo json_encode(["ok" => false, "msg" => "Preencha todos os campos corretamente."]);
    exit;
}

try {
    $pdo = Connection::getPdo(); // conexão com o banco de dados

    // busca sorteio pelo id
    $stmt = $pdo->prepare("SELECT * FROM sorteio WHERE id = ?");
    $stmt->execute([$sorteio_id]);
    $sorteio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sorteio) {
        echo json_encode(["ok" => false, "msg" => "Sorteio não existe."]);
        exit;
    }

    $agora = time();
    $inicio = strtotime($sorteio['dia']." ".$sorteio['hora_inicio']);
    $fim = strtotime($sorteio['dia']." ".$sorteio['hora_fim']);

    if ($agora < $inicio) {
        echo json_encode(["ok" => false, "msg" => "O período de cadastro dos participantes ainda não iniciou."]);
        exit;
    }

    if ($agora > $fim) {
        echo json_encode(["ok" => false, "msg" => "O sorteio já foi encerrado."]);
        exit;
    }

    // concatena empresa+matrícula
    $mat;
    if ($empresa == "COCALQUI") {
        $mat="COC".$matricula;
    } else if ($empresa == "ANIGER") {
        $mat="ACE".$matricula;
    } else {
        echo json_encode(["ok" => false, "msg" => "O campo 'empresa' precisa ser ANIGER ou COCALQUI."]);
        exit;
    }

    // verifica se matrícula já é cadastrada no sorteio
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM participantes WHERE matricula = ? AND sorteio_id = ?");
    $stmt->execute([$mat, $sorteio_id]);
    if ($stmt->fetchColumn()) {
        echo json_encode(["ok" => false, "msg" => "Essa matrícula já está cadastrada nesse sorteio."]);
        exit;
    }

    // salva participante
    $stmt = $pdo->prepare("INSERT INTO participantes (matricula, autor, sorteio_id, momento) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$mat, $autor, $sorteio_id]);

    echo json_encode(["ok" => true, "msg" => 'Colaborador da matrícula <b>'.$matricula.'</b> foi cadastrado no sorteio de hoje.']);
} catch (PDOException $e) {
    echo json_encode(["ok" => false, "msg" => "Erro no db: " . $e->getMessage()]);
}