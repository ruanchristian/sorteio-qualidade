<?php
$atual = basename($_SERVER['PHP_SELF']);
?>

<head>
  <link rel="stylesheet" href="css/loader.css">
  <link
    href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Mukta+Vaani:wght@400&display=swap');

  body {
    background-color: #fbfbfb;
    font-family: 'Mukta Vaani', sans-serif;
  }

  @media (min-width: 991.98px) {
    main {
      padding-left: 240px;
    }
  }

  .sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    padding: 58px 0 0;
    box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
    width: 240px;
    z-index: 600;
  }

  @media (max-width: 991.98px) {
    .sidebar {
      width: 100%;
    }
  }

  .sidebar .active {
    border-radius: 5px;
    box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
  }

  .sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: 0.5rem;
    overflow-x: hidden;
    overflow-y: auto;
  }
</style>

<header>
  <section id="loading">
    <div id="loading-content"></div>
  </section>

  <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-white">
    <div class="position-sticky">
      <div class="list-group list-group-flush mx-3 mt-4">
        <a href="home.php" class="list-group-item list-group-item-action py-2 ripple <?= $atual == 'home.php' ? 'active' : '' ?>">
          <i class="bi bi-house-door-fill me-3"></i><span>Início</span>
        </a>
        <a href="participantes.php" class="list-group-item list-group-item-action py-2 ripple <?= $atual == 'participantes.php' ? 'active' : '' ?>">
          <i class="bi bi-person-arms-up me-3"></i><span>Add participantes</span>
        </a>
        <a href="sorteio.php" class="list-group-item list-group-item-action py-2 ripple <?= $atual == 'sorteio.php' || $atual=='registro_particip.php' ? 'active' : '' ?>">
          <i class="bi bi-ticket-detailed me-3"></i><span>Sorteios</span>
        </a>
        <a href="usuarios.php" class="list-group-item list-group-item-action py-2 ripple <?= $atual == 'usuarios.php' ? 'active' : '' ?>">
          <i class="bi bi-person-fill-gear me-3"></i><span>Usuários</span>
        </a>
      </div>
    </div>
  </nav>

  <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <i class="bi bi-arrow-bar-down"></i>
      </button>

      <a class="navbar-brand" href="#">
        <img src="https://portalrh.cocalqui.com.br/portalrh/Contents/images/E1001_U1001.jpg"
          title="Logo III Semana da Qualidade"
          height="35" width="130" alt="Logo da Semana da Qualidade 2025" loading="lazy" />
      </a>

      <ul class="navbar-nav ms-auto d-flex flex-row">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#" data-bs-toggle="dropdown">
            <img src="img/boneco.png" class="rounded-circle" height="22" />
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>