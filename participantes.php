<?php
date_default_timezone_set('America/Fortaleza');
require_once('inclusoes/verif_sessao.php');
require_once('inclusoes/layout.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Participantes</title>
</head>
<main class="mt-5 pt-4">
    <div class="container">
        <div id="infoSorteio" class="alert alert-info d-none">
            <strong id="flagm">Status:</strong> <span id="dataAtual"></span><br>
            <strong id="flagt">Finaliza em:</strong> <span id="contador"></span>
        </div>

        <div id="msgRetorno"></div>

        <div class="card">
            <div class="card-header">
                <h5> <i class="bi bi-person-vcard-fill"></i> Cadastrar participantes - <b>Sorteio <span><?= date('d/m/Y') ?></b></span></h5>
            </div>
            <div class="card-body">
                <form id="form-participante" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Matrícula:</label>
                        <input type="text" id="matricula" name="matricula" class="form-control" maxlength="8" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Empresa:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="empresa" id="aniger" value="ANIGER" required>
                            <label class="form-check-label" for="aniger">ANIGER</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="empresa" id="cocalqui" value="COCALQUI" required>
                            <label class="form-check-label" for="cocalqui">COCALQUI</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data do sorteio:</label>
                        <input type="text" class="form-control" id="dataSorteio" disabled>
                        <input type="hidden" name="sorteio_id" id="sorteio_id">
                    </div>

                    <button type="submit" class="btn btn-primary" id="btnCadastrar">
                        <i class="bi bi-plus-circle"></i> Cadastrar
                    </button>
                </form>
            </div>
        </div>

    </div>

</main>

</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/frames.js"></script>
<script src="js/particip.js"></script>