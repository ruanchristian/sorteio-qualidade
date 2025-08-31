<?php
session_start();
session_destroy();

// deleta sessão do usuário e redireciona pra tela inicial de login.
header('Location: /');