<?php
// Esta pagina muestra videos generales y, si hay sesion, permite CRUD.
session_start();
include("backend/connection/abrir_conexion.php");

function h($texto)
{
  return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$logueado = isset($_SESSION['id_usuario']);
$puede_gestionar_tips = $logueado && in_array($_SESSION['perfil'] ?? '', ['administrador', 'profesor']);

// Cargamos las areas para el formulario y para editar tips.
$areas_resultado = mysqli_query($conexion, "SELECT id_area, nombre_area FROM $tblAreas ORDER BY id_area");
$areas_lista = [];
while ($area = mysqli_fetch_assoc($areas_resultado)) {
  $areas_lista[] = $area;
}

// Buscamos todos los videos guardados.
$tips = mysqli_query(
  $conexion,
  "SELECT t.id_tip, t.titulo, t.enlace, t.imagen, t.id_area_fk, a.nombre_area
    FROM $tblTips t
    INNER JOIN $tblAreas a ON t.id_area_fk = a.id_area
    ORDER BY a.nombre_area, t.titulo"
);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learn.Viky - Tips</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="img/logofoca.png" type="image/x-icon">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Poppins:wght@300;400;500;600;700;800&display=swap');
  </style>
</head>

<body>
  <header class="header">
    <div class="logo"><img src="img/logofoca.png" alt="logo foca graduada"></div>
    <nav class="navbar">
      <ul>
        <li><a href="/learn-viky/index.php">INICIO</a></li>
        <li><a href="/learn-viky/nosotros.html">NOSOTROS</a></li>
        <li><a href="/learn-viky/tips.php" class="active">TIPS</a></li>
        <li><a href="/learn-viky/rutinas.php">RUTINAS</a></li>
        <li><a href="/learn-viky/calendario.php">CALENDARIO</a></li>
        <li><a href="/learn-viky/perfil.php">PERFIL</a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <?php if ($logueado) { ?>
        <a href="/learn-viky/backend/controlers/cerrar_sesion_controller.php" style="text-decoration: none;">
          <button class="btn-ins">CERRAR SESION</button>
        </a>
      <?php } else { ?>
        <a href="/learn-viky/iniciar_sesion.html" style="text-decoration: none;">
          <button class="btn-ins">INICIAR SESION</button>
        </a>
        <a href="/learn-viky/registrarse.html" style="text-decoration: none;">
          <button class="btn-registr">REGISTRARSE</button>
        </a>
      <?php } ?>
    </div>
  </header>

  <main class="page-shell">
    <section class="panel">
      <div class="section-heading">
        <p>Videos generales</p>
        <h1>Tips de aprendizaje</h1>
      </div>

      <?php if (isset($_GET['mensaje'])) { ?>
        <div class="notice success">Operacion realizada correctamente.</div>
      <?php } ?>

      <?php if (isset($_GET['error'])) { ?>
        <div class="notice error">No tienes permiso para realizar esa accion.</div>
      <?php } ?>

      <?php if ($puede_gestionar_tips) { ?>
        <!-- enctype permite enviar archivos, en este caso la imagen del tip. -->
        <form class="form-grid tips-form" action="backend/controlers/tips_controller.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="titulo_form" placeholder="Titulo del video" required>
          <input type="url" name="enlace_form" placeholder="https://..." required>
          <input type="file" name="imagen_form" accept=".jpg,.jpeg,.png,.webp">
          <select name="id_area_form" required>
            <?php foreach ($areas_lista as $area) { ?>
              <option value="<?php echo h($area['id_area']); ?>"><?php echo h($area['nombre_area']); ?></option>
            <?php } ?>
          </select>
          <button type="submit" class="primary-action" name="btn-add-tip">Agregar video</button>
        </form>
      <?php } else { ?>
        <div class="notice">Solo administradores y profesores pueden agregar, editar o eliminar videos.</div>
      <?php } ?>
    </section>

    <section class="tips-video-grid">
      <?php if (mysqli_num_rows($tips) == 0) { ?>
        <div class="panel empty">Aun no hay videos guardados.</div>
      <?php } ?>

      <?php while ($tip = mysqli_fetch_assoc($tips)) { ?>
        <article class="video-card">
          <div class="video-preview">
            <?php if (!empty($tip['imagen'])) { ?>
              <img src="<?php echo h($tip['imagen']); ?>" alt="Imagen del tip <?php echo h($tip['titulo']); ?>">
            <?php } else { ?>
              <span>VIDEO</span>
            <?php } ?>
          </div>
          <div class="video-body">
            <p class="video-area"><?php echo h($tip['nombre_area']); ?></p>
            <h2><?php echo h($tip['titulo']); ?></h2>
            <a href="<?php echo h($tip['enlace']); ?>" target="_blank">Ver video</a>
          </div>

          <?php if ($puede_gestionar_tips) { ?>
            <!-- Si se sube una imagen nueva al editar, reemplaza la anterior. -->
            <form class="crud-form tip-edit-form" action="backend/controlers/tips_controller.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id_tip_form" value="<?php echo h($tip['id_tip']); ?>">
              <input type="text" name="titulo_form" value="<?php echo h($tip['titulo']); ?>" required>
              <input type="url" name="enlace_form" value="<?php echo h($tip['enlace']); ?>" required>
              <input type="file" name="imagen_form" accept=".jpg,.jpeg,.png,.webp">
              <select name="id_area_form" required>
                <?php foreach ($areas_lista as $area) { ?>
                  <option value="<?php echo h($area['id_area']); ?>" <?php if ($tip['id_area_fk'] == $area['id_area'])
                       echo 'selected'; ?>>
                    <?php echo h($area['nombre_area']); ?>
                  </option>
                <?php } ?>
              </select>
              <div class="crud-actions">
                <button class="icon-btn" type="submit" name="btn-editar-tip">Editar</button>
                <button class="icon-btn danger" type="submit" name="btn-eliminar-tip">Eliminar</button>
              </div>
            </form>
          <?php } ?>
        </article>
      <?php } ?>
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
        <h3>RECURSOS</h3><a href="/learn-viky/rutinas.php">Rutina de estudio</a><a href="/learn-viky/tips.php">Videos de
          aprendizaje</a>
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
<?php include("backend/connection/cerrar_conexion.php"); ?>
