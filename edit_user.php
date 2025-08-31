<?php
require_once('inclusoes/verif_sessao.php');
require_once('database/Connection.php');

$userId = $_POST['id'] ?? null;
$user = trim($_POST['user'] ?? '');
$passwd = trim($_POST['password'] ?? '');
$type = $_POST['type_hidden'] ?? $_POST['type'] ?? '';

$meuId = $_SESSION['user_id'];
$meuTipo = $_SESSION['tipo_user'];

// verificações
if ($meuTipo != "QUALIDADE") {
    echo json_encode(['erro' => 'Somente admins podem editar usuários.']);
    return;
}

if (!$userId || !$user) {
    echo json_encode(['erro' => 'Usuário inválido']);
    return;
}

if ($userId == $meuId && $type != $meuTipo) {
    echo json_encode(['erro' => 'Você não pode alterar seu próprio tipo.']);
    return;
}

try {
    $pdo = Connection::getPdo();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM equipe WHERE id = ?");
    $stmt->execute([$userId]);

    if (!$stmt->rowCount()) {
        echo json_encode(['erro' => 'Usuário não existe.']);
        return;
    }

    // monta query com os campos dinamicamente
    $fields = ['user = ?'];
    $vals = [$user];

    if ($meuId != $userId) {
        $fields[] = 'tipo = ?';
        $vals[] = $type;
    }

    // atualiza a senha apenas se foi editada
    if ($passwd != '') {
        $fields[] = 'password = ?';
        $vals[] = md5($passwd);
    }

    $vals[] = $userId;

    $sql = "UPDATE equipe SET " . implode(', ', $fields) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($vals);

    echo json_encode(['sucesso' => 'Usuário ' . $user . ' atualizado com sucesso.']);
} catch (Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
