<?php
require_once('inclusoes/verif_sessao.php');
require_once('database/Connection.php');

$id = $_POST['id'] ?? null;
$meuId = $_SESSION['user_id'] ?? null;
$meuTipo = $_SESSION['tipo_user'] ?? '';

// validações pra deletar
if (!$id || !is_numeric($id)) {
    echo json_encode(['erro' => 'ID de user inválido.']);
    return;
}

if ($meuTipo != 'QUALIDADE') {
    echo json_encode(['erro' => 'Você não tem permissão para deletar usuários.']);
    return;
}

if ($id == $meuId) {
    echo json_encode(['erro' => 'Você não pode deletar a si mesmo.']);
    return;
}

try {
    $pdo = Connection::getPdo();
    $stmt = $pdo->prepare("DELETE FROM equipe WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['sucesso' => 'Usuário deletado com sucesso.']);
} catch (Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
