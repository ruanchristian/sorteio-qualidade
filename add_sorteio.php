<?php
require_once('inclusoes/verif_sessao.php');
require_once("database/Connection.php");

// arquivo eh reaproveitado tanto para a criação quanto edição de sorteios.

$id = $_POST['id_sorteio'] ?? '';
$dia = $_POST['dia'] ?? '';
$inicio = $_POST['inicio'] ?? '';
$fim = $_POST['fim'] ?? '';

// validações base
if($_SESSION['tipo_user'] != "QUALIDADE") {
    echo json_encode(["ok"=>false, "msg"=>"Somente admins podem criar/editar sorteios."]);
    exit;
}

if (!$dia || !$inicio || !$fim) {
    echo json_encode(["ok"=>false, "msg"=>"Preencha todos os campos corretamente!"]);
    exit;
}

// não vai permitir hora de término menor ou igual a hora que vai começar
if(strtotime($fim) <= strtotime($inicio)) {
    echo json_encode(["ok"=>false, "msg"=>"A hora de término deve ser maior que a hora de início."]);
    exit;
}

try{
    $pdo = Connection::getPdo();

    // vai averiguar se já existe um sorteio cadastrado pra data passada no formulário
    if ($id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sorteio WHERE dia = ? AND id != ?");
        $stmt->execute([$dia, $id]);
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sorteio WHERE dia = ?");
        $stmt->execute([$dia]);
    }

    if($stmt->fetchColumn()) {
        echo json_encode(["ok"=>false, "msg"=>"Já existe um sorteio cadastrado para essa data."]);
        exit;
    }

    if ($id) {
        // edita o sorteio
        $stmt = $pdo->prepare("UPDATE sorteio SET dia = ?, hora_inicio = ?, hora_fim = ? WHERE id = ?");
        $stmt->execute([$dia, $inicio, $fim, $id]);
        echo json_encode(["ok"=>true, "msg"=>"Sorteio atualizado com sucesso!"]);
    } else {
        // cria o sorteio
        $stmt = $pdo->prepare("INSERT INTO sorteio (dia, hora_inicio, hora_fim) VALUES (?, ?, ?)");
        $stmt->execute([$dia, $inicio, $fim]);
        echo json_encode(["ok"=>true, "msg"=>"Sorteio cadastrado com sucesso!"]);
    }

} catch(PDOException $e) {
    echo json_encode(["ok"=>false, "msg"=>$e->getMessage()]);
}