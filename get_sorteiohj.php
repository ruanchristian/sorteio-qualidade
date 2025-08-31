<?php
date_default_timezone_set("America/Fortaleza");
require_once("database/Connection.php");

try {
    $pdo = Connection::getPdo();
    $hoje = date("Y-m-d");

    // verifica se existe sorteio cadastrado para hj
    $stmt = $pdo->prepare("SELECT * FROM sorteio WHERE dia = ?");
    $stmt->execute([$hoje]);

    $sorteio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sorteio) {
        echo json_encode(["ok" => false, "msg" => "Nenhum sorteio cadastrado para o dia de hoje."]);
        exit;
    }

    // transforma as datas de início e fim em timestamp (realizar comparações)
    $inicio = strtotime($sorteio["dia"]." ".$sorteio["hora_inicio"]);
    $fim = strtotime($sorteio["dia"]." ".$sorteio["hora_fim"]);
    
    $agora = time(); // retorna o tempo atual em timestamp

    if ($agora < $inicio) {
        echo json_encode([
            "ok" => false, 
            "espera" => true, 
            "inicio" => date("Y-m-d H:i:s", $inicio),
            "msg" => "O período de cadastro do sorteio ainda não começou."
        ]);
        exit;
    }

    if ($agora > $fim) {
        echo json_encode(["ok" => false, "msg" => "O sorteio de hoje já foi encerrado."]);
        exit;
    }

    // ok - sorteio ativo
    echo json_encode([
        "ok" => true,
        "id" => $sorteio["id"],
        "data" => date("d/m/Y", strtotime($sorteio["dia"])),
        "fim" => date("Y-m-d H:i:s", $fim),
    ]);
} catch(PDOException $e) {
    echo json_encode(["ok" => false, "msg" => "Erro ao buscar sorteio: ".$e->getMessage()]);
}