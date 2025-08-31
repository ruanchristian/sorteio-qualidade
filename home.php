<?php
  require('inclusoes/verif_sessao.php');
  require('inclusoes/layout.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <main class="mt-5 pt-4">
    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Dashboard</h4>
      <span>Bem-vindo: <strong><?= $user ?></strong></span>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-start border-primary border-4 shadow h-100">
          <div class="card-body">
            <h6 class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h6>
            <h3 class="fw-bold" id="usuariosCount">--</h3>
            <i class="bi bi-person-lines-fill fs-3 text-primary float-end"></i>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-start border-success border-4 shadow h-100">
          <div class="card-body">
            <h6 class="text-muted">Sorteios programados</h6>
            <h3 class="fw-bold" id="sorteiosHoje">--</h3>
            <i class="bi bi-ticket-perforated-fill fs-3 text-success float-end"></i>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-start border-warning border-4 shadow h-100">
          <div class="card-body">
            <h6 class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h6>
            <h3 class="fw-bold" id="participantesCount">--</h3>
            <i class="bi bi-people-fill fs-3 text-warning float-end"></i>
          </div>
        </div>
      </div>

    </div>
    </div>
  </main>
</html>

<script>
  console.log("logou: "+"<?php echo $user; ?>");
</script>