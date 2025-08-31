<?php
require_once('database/Connection.php');
require_once('inclusoes/verif_sessao.php');
require_once('inclusoes/layout.php');

$tipo = $_SESSION['tipo_user'];
$meu_id = $_SESSION['user_id'];

$pdo = Connection::getPdo();
$statement = $pdo->query("SELECT id, user, tipo FROM equipe");
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <script>const meuID = <?= $meu_id ?>; </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Usuários</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .input-group-text {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.75rem;
        }
    </style>
</head>
<main class="mt-5 pt-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Usuários</h4>
            <?php if ($tipo == "QUALIDADE"): ?>
                <button title="Criar usuário" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastro">
                    <i class="bi bi-person-plus-fill"></i>
                </button>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usuariosTable" class="table table-bordered table-hover collapsed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <?php if($tipo == "QUALIDADE"): ?><th>Ações</th> <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $k=1;
                            foreach ($users as $u): ?>
                                <tr>
                                    <td><?= $k++ ?></td>
                                    <td><?= $u['user'] ?></td>
                                    <td><?= $u['tipo'] ?></td>
                                    <?php if($tipo == "QUALIDADE"): ?>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning shadow edit-user"
                                            data-id="<?= $u['id']; ?>"
                                            data-user="<?= $u['user']; ?>"
                                            data-type="<?= $u['tipo']; ?>">
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </button>
                                        <button <?php if ($meu_id == $u['id']): ?> disabled <?php endif; ?> 
                                            class="btn btn-sm btn-danger shadow delete-user"
                                            data-id="<?= $u['id'] ?>"
                                            data-nome="<?= $u['user'] ?>">
                                            <i class="bi bi-trash"></i>
                                            Excluir
                                        </button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal de Cadastro -->
    <div class="modal fade" id="modalCadastro" tabindex="-1" aria-labelledby="modalCadastroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-cadastro" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalCadastroLabel">
                            <i class="bi bi-person-fill-add mr-2"></i> Cadastrar novo usuário
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user" class="form-label">Usuário:</label>
                            <input type="text" name="user" id="user" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Função do usuário:</label>
                            <select class="form-select" name="type" id="type">
                                <option value="QUALIDADE">QUALIDADE</option>
                                <option value="APOIO">APOIO</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Senha:</label>
                            <input type="password" name="pass" id="pass" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-floppy-fill"></i>
                            Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="form-update" method="POST">
        <div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false">
            <input type="hidden" name="id" id="user-id">                                   
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <i class="bi bi-person-fill-up mr-2"></i> Editar usuário
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name-user">
                                Nome
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <input id="name-user" name="user"
                                    class="form-control" placeholder="Usuário">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="type-user">
                                Função do usuário
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                </div>
                                <select id="type-user" name="type" class="form-select">
                                    <option value="QUALIDADE">QUALIDADE</option>
                                    <option value="APOIO">APOIO</option>
                                </select>
                                <input type="hidden" name="type_hidden" id="type-hidden">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="password">
                                Senha
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                <input id="password" name="password"
                                    value=""
                                    class="form-control" type="password" placeholder="Senha">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-floppy-disk"></i> Salvar dados </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</main>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/frames.js"></script>
<script src="js/usuarios.js"></script>