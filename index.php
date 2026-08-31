<?php
// El inicio tambien revisa la sesion para cambiar los botones del menu.
session_start();
$logueado = isset($_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learn.Viky</title>
  <link rel="stylesheet" href="./css/styles.css">
  <link rel="icon" href="img/logofoca.png" type="image/x-icon">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Poppins:wght@300;400;500;600;700;800&display=swap');
  </style>
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="img/logofoca.png" alt="logo foca graduada">
    </div>

    <nav class="navbar">
      <ul>
        <li><a href="/learn-viky/index.php" class="active">INICIO</a></li>
        <li><a href="/learn-viky/nosotros.html">NOSOTROS</a></li>
        <li><a href="/learn-viky/tips.php">TIPS</a></li>
        <li><a href="/learn-viky/rutinas.php">RUTINAS</a></li>
        <li><a href="/learn-viky/calendario.php">CALENDARIO</a></li>
        <li><a href="/learn-viky/perfil.php">PERFIL</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <?php if ($logueado) { ?>
        <a href="/learn-viky/backend/controlers/cerrar_sesion_controller.php" style="text-decoration:none;">
          <button class="btn-ins">CERRAR SESION</button>
        </a>
      <?php } else { ?>
        <a href="/learn-viky/iniciar_sesion.html" style="text-decoration:none;">
          <button class="btn-ins">INICIAR SESION</button>
        </a>
        <a href="/learn-viky/registrarse.html" style="text-decoration: none;">
          <button class="btn-registr">REGISTRARSE</button>
        </a>
      <?php } ?>
    </div>
  </header>

  <main class="home-landing">
    <section class="home-hero">
      <div class="home-copy">
        <p class="home-kicker">Learn.Viky</p>
        <h1>Organiza tu estudio sin enredarte</h1>
        <p class="home-description">
          Crea rutinas, guarda eventos importantes y consulta videos utiles para estudiar mejor.
        </p>
        <div class="home-actions">
          <a href="/learn-viky/rutinas.php">
            <button class="btn-rutinab">Crear rutina</button>
          </a>
          <a href="/learn-viky/calendario.php">
            <button class="btn-secondary">Abrir calendario</button>
          </a>
        </div>
      </div>

      <div class="home-visual">
        <img src="img/chica_estudiandp.png" alt="Estudiante organizando sus actividades">
      </div>
    </section>

    <section class="home-shortcuts">
      <a class="shortcut-card" href="/learn-viky/rutinas.php">
        <span>01</span>
        <h2>Rutinas</h2>
        <p>Planea que estudiar, el dia y la hora.</p>
      </a>
      <a class="shortcut-card" href="/learn-viky/calendario.php">
        <span>02</span>
        <h2>Calendario</h2>
        <p>Guarda tareas, repasos y fechas importantes.</p>
      </a>
      <a class="shortcut-card" href="/learn-viky/tips.php">
        <span>03</span>
        <h2>Videos</h2>
        <p>Agrega y consulta tips en video.</p>
      </a>
    </section>
  </main>

  <footer class="footer">
    <div class="footer-container">
      <div class="footer-section">
        <h2>LEARN.VIKY</h2>
        <p>El mejor espacio para aprender, organizarte y mejorar cada dia.</p>
      </div>
      <div class="footer-section">
        <h3>ENLACES</h3>
        <a href="/learn-viky/index.php">INICIO</a>
        <a href="/learn-viky/nosotros.html">NOSOTROS</a>
        <a href="/learn-viky/tips.php">TIPS</a>
        <a href="/learn-viky/rutinas.php">RUTINAS</a>
        <a href="/learn-viky/calendario.php">CALENDARIO</a>
        <a href="/learn-viky/perfil.php">PERFIL</a>
      </div>
      <div class="footer-section">
        <h3>RECURSOS</h3>
        <a href="/learn-viky/rutinas.php">Rutina de estudio</a>
        <a href="/learn-viky/tips.php">Videos de aprendizaje</a>
      </div>
      <div class="footer-section">
        <h3>CONTACTOS</h3>
        <p>learn.viky@.com</p>
        <p>3203848091</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 Learn.Viky. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
