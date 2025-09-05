<?php
date_default_timezone_set('America/Fortaleza');
require_once('inclusoes/verif_sessao.php');
require_once('inclusoes/layout.php');
require_once('database/Connection.php');

$pdo = Connection::getPdo();
$stmt = $pdo->query("SELECT * FROM sorteio");
$sorteios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Sorteios</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">
</head>
<main class="mt-5 pt-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Painel - Sorteios</h3>
            <button id="criarSort" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSorteio">
                <i class="bi bi-plus-lg"></i> Criar sorteio
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (count($sorteios)): ?>
                    <div class="table-responsive">
                        <table id="tabela-sorteios" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Hora de início</th>
                                    <th>Hora de término</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $k = 1;
                                foreach ($sorteios as $s): ?>

                                    <?php
                                    $agora = time();
                                    $fim = strtotime($s['dia'] . ' ' . $s['hora_fim']);

                                    // verifica se o sorteio está ativo ou não
                                    $encerrado = $agora > $fim;
                                    ?>

                                    <tr>
                                        <td><?= $k++ ?></td>
                                        <td><?= date("d/m/Y", strtotime($s['dia'])) ?></td>
                                        <td><?= substr($s['hora_inicio'], 0, 5) ?></td>
                                        <td><?= substr($s['hora_fim'], 0, 5) ?></td>
                                        <td>
                                            <?php if ($_SESSION['tipo_user'] == 'QUALIDADE'): ?>
                                                <button class="btn btn-sm btn-warning shadow edit-sorteio"
                                                    data-id="<?= $s['id'] ?>"
                                                    data-dia="<?= $s['dia'] ?>"
                                                    data-inicio="<?= substr($s['hora_inicio'], 0, 5) ?>"
                                                    data-fim="<?= substr($s['hora_fim'], 0, 5) ?>">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </button>
                                                <button class="btn btn-sm btn-danger shadow deleta-sorteio"
                                                    data-id="<?= $s['id'] ?>"
                                                    data-dia="<?= $s['dia'] ?>">
                                                    <i class="bi bi-trash"></i>
                                                    Excluir
                                                </button>
                                            <?php endif; ?>
                                            <a href="registro_particip.php?id=<?= $s['id'] ?>">
                                                <button class="btn btn-sm btn-primary shadow">
                                                    <i class="bi bi-eye-fill"></i>
                                                    Ver registros
                                                </button>
                                            </a>
                                            <!-- Botão da página de sorteio -->
                                            <?php if ($_SESSION['tipo_user'] == 'QUALIDADE'): ?>
                                                <a href="<?php if (!$encerrado): ?> javascript:void(0); <?php else: ?>
                                                    realiza_sorteio.php?id=<?= $s['id'] ?> 
                                                    <?php endif; ?>">
                                                    <button class="btn btn-sm btn-success shadow"
                                                        <?= !$encerrado ? 'disabled' : '' ?>>
                                                        <i class="bi bi-trophy-fill"></i>
                                                        Sortear
                                                    </button>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Nenhum sorteio cadastrado até o momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Cadastro/edição de sorteio -->
    <div class="modal fade" id="modalSorteio" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalSorteioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-sorteio" class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalSorteioLabel">
                        <i class="bi bi-database-fill-add"></i> <span id="txt">Cadastrar novo sorteio</span>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_sorteio" name="id_sorteio">
                    <div class="mb-3">
                        <label for="dia" class="form-label">Data do sorteio</label>
                        <input type="date" id="dia" name="dia" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="inicio" class="form-label">Horário de início</label>
                        <input type="time" id="inicio" name="inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="fim" class="form-label">Horário de fim</label>
                        <input type="time" id="fim" name="fim" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/frames.js"></script>
<script src="js/sorteio.js"></script>