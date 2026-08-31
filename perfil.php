<?php
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

$id_usuario_data = $_SESSION['id_usuario'];

$consulta_usuario = $conexion->prepare(
  "SELECT id_usuario, nombre_completo, correo_electronico, perfil, cedula, certificacion_diploma
    FROM $tblUsuarios
    WHERE id_usuario = ?"
);
$consulta_usuario->bind_param("i", $id_usuario_data);
$consulta_usuario->execute();
$usuario = $consulta_usuario->get_result()->fetch_assoc();

$consulta_rutinas = $conexion->prepare("SELECT COUNT(*) AS total FROM $tblRutinas WHERE id_usuario_fk = ?");
$consulta_rutinas->bind_param("i", $id_usuario_data);
$consulta_rutinas->execute();
$total_rutinas = $consulta_rutinas->get_result()->fetch_assoc()['total'];

$consulta_calendarios = $conexion->prepare("SELECT COUNT(*) AS total FROM $tblCalendarios WHERE id_usuario_fk = ?");
$consulta_calendarios->bind_param("i", $id_usuario_data);
$consulta_calendarios->execute();
$total_calendarios = $consulta_calendarios->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil.viky</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="img/logofoca.png" type="image/x-icon">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Poppins:wght@300;400;500;600;700;800&display=swap');
  </style>
</head>

<body class="body3">
  <header class="header">
    <div class="logo">
      <img src="img/logofoca.png" alt="logo foca graduada">
    </div>
    <nav class="navbar">
      <ul>
        <li><a href="/learn-viky/index.php">INICIO</a></li>
        <li><a href="/learn-viky/nosotros.html">NOSOTROS</a></li>
        <li><a href="/learn-viky/tips.php">TIPS</a></li>
        <li><a href="/learn-viky/rutinas.php">RUTINAS</a></li>
        <li><a href="/learn-viky/calendario.php">CALENDARIO</a></li>
        <li><a href="/learn-viky/perfil.php" class="active">PERFIL</a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <a href="/learn-viky/backend/controlers/cerrar_sesion_controller.php" style="text-decoration: none;">
        <button class="btn-ins">CERRAR SESION</button>
      </a>
    </div>
  </header>

  <mainperfil>
    <div class="titulop">TU PERFIL</div>

    <?php if (isset($_GET['mensaje'])) { ?>
      <div class="notice success">Cambios guardados correctamente.</div>
    <?php } ?>

    <?php if (isset($_GET['error'])) { ?>
      <div class="notice error">No se pudo guardar. Revisa la informacion.</div>
    <?php } ?>

    <cartap class="cartap">
      <avatarp class="avatarp">
        <avatar-wrap class="avatar-wrap">
          <img src="img/logoperfil.png" alt="Avatar">
        </avatar-wrap>
        <username class="username"><?php echo h($usuario['nombre_completo']); ?></username>
        <estadop class="estadop">Cuenta activa</estadop>
        <miniestatadisticas class="miniestadisticas profile-stats">
          <div><strong><?php echo h($total_rutinas); ?></strong>Rutinas</div>
          <div><strong><?php echo h($total_calendarios); ?></strong>Eventos</div>
          <div><strong><?php echo h($usuario['perfil']); ?></strong>Perfil</div>
        </miniestatadisticas>
      </avatarp>

      <info-section class="info-section">
        <section-label class="section-label">Informacion personal</section-label>

        <form class="profile-form" action="backend/controlers/perfil_controller.php" method="POST">
          <div class="campo-n">
            <label>Nombre</label>
            <div class="input-wrap">
              <span class="icon">*</span>
              <input type="text" name="nombre_completo_form" value="<?php echo h($usuario['nombre_completo']); ?>"
                required>
            </div>
          </div>

          <two-col class="two-col">
            <div class="field-group">
              <label>Correo</label>
              <div class="input-wrap">
                <span class="icon">@</span>
                <input type="email" name="correo_electronico_form"
                  value="<?php echo h($usuario['correo_electronico']); ?>" required>
              </div>
            </div>

            <div class="field-group">
              <label>Perfil</label>
              <div class="readonly-field"><?php echo h(ucfirst($usuario['perfil'])); ?></div>
            </div>
          </two-col>

          <?php if ($usuario['perfil'] == 'profesor') { ?>
            <two-col class="two-col">
              <div class="field-group">
                <label>CC</label>
                <div class="readonly-field"><?php echo h($usuario['cedula']); ?></div>
              </div>
              <div class="field-group">
                <label>Certificacion de diploma</label>
                <div class="readonly-field">
                  <?php if (!empty($usuario['certificacion_diploma'])) { ?>
                    <a href="<?php echo h($usuario['certificacion_diploma']); ?>" target="_blank">Ver archivo</a>
                  <?php } else { ?>
                    Sin archivo
                  <?php } ?>
                </div>
              </div>
            </two-col>
          <?php } ?>

          <actions class="actions">
            <button class="btn btn-save" type="submit" name="btn-actualizar-perfil">Guardar</button>
          </actions>
        </form>

        <form class="profile-form compact" action="backend/controlers/perfil_controller.php" method="POST">
          <section-label class="section-label">Cambiar contrasena</section-label>
          <two-col class="two-col">
            <input type="password" name="contrasena_form" placeholder="Nueva contrasena" required>
            <input type="password" name="contrasena_validada_form" placeholder="Confirmar contrasena" required>
          </two-col>
          <button class="btn btn-edit" type="submit" name="btn-cambiar-contrasena">Actualizar contrasena</button>
        </form>

        <form action="backend/controlers/perfil_controller.php" method="POST">
          <button class="btn btn-delete" type="submit" name="btn-eliminar-perfil">Borrar cuenta</button>
        </form>
      </info-section>
    </cartap>
  </mainperfil>

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
        <a href="/learn-viky/tips.php">Tips de aprendizaje</a>
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