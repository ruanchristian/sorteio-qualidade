<?php
date_default_timezone_set('America/Fortaleza');
require_once('inclusoes/verif_sessao.php');
require_once('inclusoes/layout.php');
require_once('database/Connection.php');

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('Sorteio inválido.');</script>";
    echo "<script>window.location.href='sorteio.php'</script>";
    exit;
}

$pdo = Connection::getPdo(); // conecta com bd

// verifica se sorteio existe
$stmt = $pdo->prepare("SELECT * FROM sorteio WHERE id = ?");
$stmt->execute([$id]);
$sorteio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sorteio) {
    echo "<script>alert('Sorteio não existe no banco de dados.');</script>";
    echo "<script>window.location.href='sorteio.php'</script>";
    exit;
}

// busca
$stmt2 = $pdo->prepare("
    SELECT COUNT(*) AS tot, SUM(CASE WHEN flag_sorteio = 1 THEN 1 ELSE 0 END) AS sorteados
    FROM participantes
    WHERE sorteio_id = ?
");
$stmt2->execute([$id]);
$resp = $stmt2->fetch(PDO::FETCH_ASSOC);
$total = $resp['tot'] ?? 0;
$sorteados = $resp['sorteados'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sortear - <?= date("d/m/Y", strtotime($sorteio['dia'])) ?></title>
    <link rel="stylesheet" href="css/sorteio.css">

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>

<main class="mt-5 pt-4">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm d-flex flex-wrap">
                <li class="breadcrumb-item active fw-bold">
                    <i class="bi bi-calendar-event"></i> Sorteio: <?= date("d/m/Y", strtotime($sorteio['dia'])) ?>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-clock"></i> Início: <?= substr($sorteio['hora_inicio'], 0, 5) ?>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-clock-history"></i> Fim: <?= substr($sorteio['hora_fim'], 0, 5) ?>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-people-fill text-primary"></i> Participantes: <b><?= $total ?></b>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-check2-circle text-success"></i> Sorteados: <b id="sorteados"><?= $sorteados ?></b>
                </li>
                <li class="breadcrumb-item">
                    Status:
                    <span class="badge bg-warning text-dark">Encerrado</span>
                </li>
            </ol>
        </nav>
        <div class="text-center my-4">
            <button id="btnSortear" class="button-92">
                <i class="bi bi-trophy-fill me-2"></i>
                Realizar sorteio
            </button>

            <div class="mt-4">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mt-3" id="log-sorteados">
                </div>
            </div>
        </div>

        <!-- Modal de Sorteio -->
        <div class="modal fade" id="modalSorteio" tabindex="-1" aria-labelledby="modalSorteioLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSorteioLabel">Realizando Sorteio...</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <!-- Área de spinner (visível apenas durante a requisição) -->
                        <div id="spinner-area">
                            <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
                                <span class="visually-hidden">Sorteando...</span>
                            </div>
                            <h5 class="mt-3">Sorteando participante, aguarde alguns segundos...</h5>
                        </div>

                        <!-- Área de resultado (oculta inicialmente) -->
                        <div id="resultado-area" class="d-none">
                            <h4 class="text-success">🎉 Sorteado!</h4>
                            <h2 id="nome-sorteado" class="fs-5 fw-bold"></h2>
                        </div>

                        <!-- Área de erro (oculta inicialmente) -->
                        <div id="erro-area" class="d-none">
                            <h5 class="text-danger">Erro ao sortear</h5>
                            <p id="msg-erro" class="text-danger fw-semibold"></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
</main>

<script>
    // atualiza a página ao fechar o modal do sorteio
    document.getElementById("modalSorteio").addEventListener("hidden.bs.modal", () => {
        location.reload();
    });

    // ação do sorteio
    document.getElementById("btnSortear").addEventListener("click", async () => {
        const btn = document.getElementById("btnSortear");
        const sorts = document.getElementById("sorteados");
        const modal = new bootstrap.Modal(document.getElementById("modalSorteio"));

        btn.disabled = true;
        btn.classList.add('pe-none');
        document.getElementById("spinner-area").classList.remove("d-none");
        document.getElementById("resultado-area").classList.add("d-none");
        document.getElementById("erro-area").classList.add("d-none");

        modal.show();

        try {
            const res = await fetch(`sorteia_backend.php?id=<?= $sorteio['id'] ?>`, {
                method: "POST"
            });

            const json = await res.json();

            if (!json.ok) throw new Error(json.msg || "Erro desconhecido.");

            // Esconde spinner, mostra resultado
            document.getElementById("modalSorteioLabel").textContent = "Participante sorteado!";
            document.getElementById("spinner-area").classList.add("d-none");
            document.getElementById("resultado-area").classList.remove("d-none");

            const nome = `${json.mat} - ${json.emp}`;
            document.getElementById("nome-sorteado").textContent = nome;

            soltaConfetesTelaInteira();

        } catch (erro) {
            document.getElementById("modalSorteioLabel").textContent = "Erro!"
            document.getElementById("spinner-area").classList.add("d-none");
            document.getElementById("erro-area").classList.remove("d-none");
            document.getElementById("msg-erro").textContent = erro.message;
        }
        btn.disabled = false;
        btn.classList.remove('pe-none');
    });

    document.addEventListener("DOMContentLoaded", async () => {
        const log = document.getElementById("log-sorteados");

        try {
            const res = await fetch(`get_sorteados.php?id=<?= $sorteio['id'] ?>`);
            const json = await res.json();

            if (json.ok && json.sorteados.length > 0) {
                json.sorteados.forEach((matricula, i) => {
                    const empresa = matricula.startsWith("COC") ? "COCALQUI" : "ANIGER";
                    const limpaMat = matricula.slice(3);
                    const medalha = ["🥇", "🥈", "🥉"][i] || "🎉";

                    document.getElementById("log-sorteados").innerHTML += `
                    <div class="col">
                        <div class="card h-100 border-success shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title">${medalha} ${i+1}° Sorteado</h5>
                                <p class="card-text"><strong>Matrícula:</strong> ${limpaMat}<br>
                                <strong>Empresa:</strong> ${empresa}</p>
                            </div>
                        </div>
                    </div>`;
                });
            }
        } catch (e) {
            console.error("Erro ao carregar sorteados: ", e);
        }
    });

    // soltar confetes em vários pontos
    function soltaConfetesTelaInteira() {
        const duration = 4000,
            animationEnd = Date.now() + duration,
            defaults = {
                startVelocity: 30,
                spread: 360,
                ticks: 60,
                zIndex: 9999
            };

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);

            // since particles fall down, start a bit higher than random
            confetti(
                Object.assign({}, defaults, {
                    particleCount,
                    origin: {
                        x: randomInRange(0.1, 0.3),
                        y: Math.random() - 0.2
                    },
                })
            );
            confetti(
                Object.assign({}, defaults, {
                    particleCount,
                    origin: {
                        x: randomInRange(0.7, 0.9),
                        y: Math.random() - 0.2
                    },
                })
            );
        }, 200);

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }
    }
</script>