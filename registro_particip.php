<?php
require('inclusoes/verif_sessao.php');
require_once('database/Connection.php');

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('Sorteio inválido. Insira um ID válido!');</script>";
    echo "<script>window.location.href='sorteio.php'</script>";
    exit;
}

// verifica se o sorteio existe no banco de dados
$pdo = Connection::getPdo();
$stmtSorteio = $pdo->prepare("SELECT * FROM sorteio WHERE id = ?");
$stmtSorteio->execute([$id]);
$sorteio = $stmtSorteio->fetch(PDO::FETCH_ASSOC);

if (!$sorteio) {
    echo "<script>alert('Sorteio não encontrado na base da dados!');</script>";
    echo "<script>window.location.href='sorteio.php'</script>";
    exit;
}

// carrega o layout base somente se tiver passado pelas validações
require_once('inclusoes/layout.php');

// busca os participantes no banco pelo id do sorteio
$stmt = $pdo->prepare("
    SELECT 
        p.matricula,
        p.momento,
        e.user AS autor
    FROM participantes p
    JOIN equipe e ON p.autor = e.id
    WHERE p.sorteio_id = ?");
$stmt->execute([$id]);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Cadastros - Sorteio</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">
</head>
<main class="mt-5 pt-4">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill"></i>
                    Registros do sorteio de <b><?= date("d/m/Y", strtotime($sorteio['dia'])) ?></b>
                </h5>
            </div>
            <div class="card-body">
                <?php if (count($participantes) == 0): ?>
                    <div class="alert alert-info">Nenhum participante foi cadastrado neste sorteio ainda.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table id="registrosTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Matrícula</th>
                                    <th>Empresa</th>
                                    <th>Adicionado por</th>
                                    <th>Data/Hora do cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participantes as $k => $p): ?>
                                    <tr>
                                        <td><?= $k+1 ?></td>
                                        <td><?= substr($p['matricula'], 3) ?></td>
                                        <td><?= str_starts_with($p['matricula'],"COC")?'COCALQUI':'ANIGER' ?></td>
                                        <td><?= $p['autor'] ?></td>
                                        <td><?= date("d/m/Y H:i:s", strtotime($p['momento'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

</html>

<script>
    $(document).ready(() => {
        $('#registrosTable').DataTable({
            language: {
                url: '/sorteio-qualidade/inclusoes/pt-BR.json'
            },
            ordering: false,
        });
    });
</script>