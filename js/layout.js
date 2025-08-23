export function loadLayout(user) {
  // css aplicado dinamicamente
  const style = `
      <style>
        body {
    background-color: #fbfbfb;
  }
  @media (min-width: 991.98px) {
    main {
      padding-left: 240px;
    }
  }
  
  /* Sidebar */
  .sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    padding: 58px 0 0; /* Height of navbar */
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
    overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
  }
      </style>
    `;

  // html da navbar e sidebar
  const html = `
      <header>
  <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-white">
    <div class="position-sticky">
      <div class="list-group list-group-flush mx-3 mt-4">
        <a href="home.html" class="list-group-item list-group-item-action py-2 ripple active">
          <i class="bi bi-house-door-fill me-3"></i><span>Início</span>
        </a>
        <a href="cadastro_sorteio.html" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-ticket-detailed me-3"></i><span>Cadastrar sorteio</span>
        </a>
        <a href="add_participantes.html" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-person-arms-up me-3"></i><span>Add participantes</span>
        </a>
        <a href="usuarios.html" class="list-group-item list-group-item-action py-2 ripple">
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
             height="35" width="130" alt="Logo da Semana da Qualidade 2025" loading="lazy"/>
      </a>

      <ul class="navbar-nav ms-auto d-flex flex-row">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#" data-bs-toggle="dropdown">
            <img src="https://mdbootstrap.com/img/Photos/Avatars/img (31).jpg" class="rounded-circle" height="22" alt=""/>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" id="logoutBtn" href="javascript:void(0);">Sair (${user})</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>`;

  // Adiciona ao DOM
  document.head.insertAdjacentHTML("beforeend", style);
  document.body.insertAdjacentHTML("afterbegin", html);

  requestAnimationFrame(() => {
    const logoutBtn = document.getElementById("logoutBtn");
    // fazer logout
    if (logoutBtn) {
      logoutBtn.addEventListener("click", () => {
        fetch("https://testes.epquixeramobim.com.br/logout.php", {
          method: "GET",
          credentials: "include",
        }).then(() => {
          window.location.href = "../index.html";
        });
      });
    }
  });
}
