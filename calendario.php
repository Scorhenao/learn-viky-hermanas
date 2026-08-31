<?php
// Esta pagina muestra el CRUD del calendario.
session_start();

if (!isset($_SESSION['id_usuario'])) {
  header('Location:/learn-viky/iniciar_sesion.html?error=sesion');
  exit;
}

include("backend/connection/abrir_conexion.php");

function h($texto)
{
  return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

// Convierte la fecha de MySQL al formato que necesita input datetime-local.
function fecha_para_input($fecha)
{
  if ($fecha == '') {
    return '';
  }

  return date('Y-m-d\TH:i', strtotime($fecha));
}

$id_usuario_data = $_SESSION['id_usuario'];

// Buscamos los eventos guardados del usuario conectado.
$consulta_eventos = $conexion->prepare(
  "SELECT id_calendario, observacion, fecha_inicio, fecha_final
    FROM $tblCalendarios
    WHERE id_usuario_fk = ?
    ORDER BY fecha_inicio"
);
$consulta_eventos->bind_param("i", $id_usuario_data);
$consulta_eventos->execute();
$eventos = $consulta_eventos->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="img/logofoca.png" type="image/x-icon">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Poppins:wght@300;400;500;600;700;800&display=swap');
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js"></script>
  <script>
    // FullCalendar lee los eventos desde calendario_controller.php?accion=listar.
    $(document).ready(function () {
      $('#calendario').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        editable: false,
        eventLimit: true,
        locale: 'es',
        events: 'backend/controlers/calendario_controller.php?accion=listar'
      });
    });
  </script>
</head>

<body>
  <header class="header">
    <div class="logo"><img src="img/logofoca.png" alt="logo foca graduada"></div>
    <nav class="navbar">
      <ul>
        <li><a href="/learn-viky/index.php">INICIO</a></li>
        <li><a href="/learn-viky/nosotros.html">NOSOTROS</a></li>
        <li><a href="/learn-viky/tips.php">TIPS</a></li>
        <li><a href="/learn-viky/rutinas.php">RUTINAS</a></li>
        <li><a href="/learn-viky/calendario.php" class="active">CALENDARIO</a></li>
        <li><a href="/learn-viky/perfil.php">PERFIL</a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <a href="/learn-viky/backend/controlers/cerrar_sesion_controller.php" style="text-decoration: none;">
        <button class="btn-ins">CERRAR SESION</button>
      </a>
    </div>
  </header>

  <main class="page-shell calendar-layout">
    <section class="panel">
      <div class="section-heading">
        <p>Crear</p>
        <h1>Calendario</h1>
      </div>

      <?php if (isset($_GET['mensaje'])) { ?>
        <div class="notice success">Operacion realizada correctamente.</div>
      <?php } ?>
      <?php if (isset($_GET['error'])) { ?>
        <div class="notice error">No se pudo guardar. Revisa el nombre y las fechas.</div>
      <?php } ?>

      <form class="form-grid calendar-form" action="backend/controlers/calendario_controller.php" method="POST">
        <input type="text" name="observacion_form" placeholder="Nombre del evento" required>
        <input type="datetime-local" name="fecha_inicio_form" required>
        <input type="datetime-local" name="fecha_final_form">
        <button type="submit" class="primary-action" name="btn-add-calendario">Agregar evento</button>
      </form>
    </section>

    <section class="calendar-panel">
      <div id="calendario"></div>
    </section>

    <section class="panel">
      <div class="section-heading small">
        <p>Leer, editar y eliminar</p>
        <h2>Eventos guardados</h2>
      </div>

      <div class="items-list">
        <?php if ($eventos->num_rows == 0) { ?>
          <div class="empty">Aun no tienes eventos guardados.</div>
        <?php } ?>
        <?php while ($evento = $eventos->fetch_assoc()) { ?>
          <article class="crud-card">
            <form class="crud-form calendar-edit-form" action="backend/controlers/calendario_controller.php"
              method="POST">
              <input type="hidden" name="id_calendario_form" value="<?php echo h($evento['id_calendario']); ?>">
              <input type="text" name="observacion_form" value="<?php echo h($evento['observacion']); ?>" required>
              <input type="datetime-local" name="fecha_inicio_form"
                value="<?php echo h(fecha_para_input($evento['fecha_inicio'])); ?>" required>
              <input type="datetime-local" name="fecha_final_form"
                value="<?php echo h(fecha_para_input($evento['fecha_final'])); ?>">
              <div class="crud-actions">
                <button class="icon-btn" type="submit" name="btn-editar-calendario">Editar</button>
                <button class="icon-btn danger" type="submit" name="btn-eliminar-calendario">Eliminar</button>
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