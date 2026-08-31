<?php
// Esta pagina muestra el CRUD de rutinas.
session_start();

// Si no hay sesion, mandamos al usuario al login.
if (!isset($_SESSION['id_usuario'])) {
  header('Location:/learn-viky/iniciar_sesion.html?error=sesion');
  exit;
}

include("backend/connection/abrir_conexion.php");

// h() evita que se imprima HTML peligroso en la pagina.
function h($texto)
{
  return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$id_usuario_data = $_SESSION['id_usuario'];

// Cargamos las areas en un arreglo para poder usarlas muchas veces.
$areas_resultado = mysqli_query($conexion, "SELECT id_area, nombre_area FROM $tblAreas ORDER BY id_area");
$areas_lista = [];
while ($area = mysqli_fetch_assoc($areas_resultado)) {
  $areas_lista[] = $area;
}

// Buscamos todas las rutinas del usuario conectado.
$consulta_rutinas = $conexion->prepare(
  "SELECT r.id_rutina, r.nota, r.dia_semana, r.tema, r.hora, r.id_area_fk, a.nombre_area
    FROM $tblRutinas r
    INNER JOIN $tblAreas a ON r.id_area_fk = a.id_area
    WHERE r.id_usuario_fk = ?
    ORDER BY FIELD(r.dia_semana, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'), r.hora"
);
$consulta_rutinas->bind_param("i", $id_usuario_data);
$consulta_rutinas->execute();
$rutinas = $consulta_rutinas->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rutinas</title>
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
        <li><a href="/learn-viky/tips.php">TIPS</a></li>
        <li><a href="/learn-viky/rutinas.php" class="active">RUTINAS</a></li>
        <li><a href="/learn-viky/calendario.php">CALENDARIO</a></li>
        <li><a href="/learn-viky/perfil.php">PERFIL</a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <a href="/learn-viky/backend/controlers/cerrar_sesion_controller.php" style="text-decoration:none;">
        <button class="btn-ins">CERRAR SESION</button>
      </a>
    </div>
  </header>

  <main class="page-shell">
    <section class="panel">
      <div class="section-heading">
        <p>Crear</p>
        <h1>Rutina personalizada</h1>
      </div>

      <?php if (isset($_GET['mensaje'])) { ?>
        <div class="notice success">Operacion realizada correctamente.</div>
      <?php } ?>
      <?php if (isset($_GET['error'])) { ?>
        <div class="notice error">No se pudo guardar. Revisa los datos.</div>
      <?php } ?>

      <form class="form-grid" action="backend/controlers/rutina_controller.php" method="POST">
        <select name="dia_semana_form" required>
          <option value="Lunes">Lunes</option>
          <option value="Martes">Martes</option>
          <option value="Miercoles">Miercoles</option>
          <option value="Jueves">Jueves</option>
          <option value="Viernes">Viernes</option>
          <option value="Sabado">Sabado</option>
          <option value="Domingo">Domingo</option>
        </select>

        <select name="id_area_form" required>
          <?php foreach ($areas_lista as $area) { ?>
            <option value="<?php echo h($area['id_area']); ?>"><?php echo h($area['nombre_area']); ?></option>
          <?php } ?>
        </select>

        <input type="time" name="hora_form" value="08:00" required>
        <input type="text" name="tema_form" placeholder="Tema a estudiar" required>
        <textarea name="notas_form" placeholder="Notas, recursos u objetivos"></textarea>

        <button type="submit" name="btn-add" class="primary-action">Agregar rutina</button>
      </form>
    </section>

    <section class="panel">
      <div class="section-heading small">
        <p>Leer, editar y eliminar</p>
        <h2>Rutinas guardadas</h2>
      </div>

      <div class="items-list">
        <?php if ($rutinas->num_rows == 0) { ?>
          <div class="empty">Aun no tienes rutinas. Crea la primera arriba.</div>
        <?php } ?>

        <?php while ($rutina = $rutinas->fetch_assoc()) { ?>
          <article class="crud-card">
            <form class="crud-form" action="backend/controlers/rutina_controller.php" method="POST">
              <input type="hidden" name="id_rutina_form" value="<?php echo h($rutina['id_rutina']); ?>">

              <select name="dia_semana_form" required>
                <?php foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'] as $dia) { ?>
                  <option value="<?php echo h($dia); ?>" <?php if ($rutina['dia_semana'] == $dia)
                       echo 'selected'; ?>>
                    <?php echo h($dia); ?></option>
                <?php } ?>
              </select>

              <select name="id_area_form" required>
                <?php foreach ($areas_lista as $area) { ?>
                  <option value="<?php echo h($area['id_area']); ?>" <?php if ($rutina['id_area_fk'] == $area['id_area'])
                       echo 'selected'; ?>>
                    <?php echo h($area['nombre_area']); ?>
                  </option>
                <?php } ?>
              </select>

              <input type="time" name="hora_form" value="<?php echo h(substr($rutina['hora'], 0, 5)); ?>" required>
              <input type="text" name="tema_form" value="<?php echo h($rutina['tema']); ?>" required>
              <textarea name="notas_form"><?php echo h($rutina['nota']); ?></textarea>

              <div class="crud-actions">
                <button class="icon-btn" type="submit" name="btn-editar">Editar</button>
                <button class="icon-btn danger" type="submit" name="btn-eliminar">Eliminar</button>
              </div>
            </form>
          </article>
        <?php } ?>
      </div>
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