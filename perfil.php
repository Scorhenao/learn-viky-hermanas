<?php
session_start();

$idUsuario = filter_var(
    $_SESSION['id_usuario'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if (!$idUsuario) {
    header('Location: /learn-viky/iniciar_sesion.html?error=sesion');
    exit;
}

// El controlador debe validar este token.
// Ver el archivo backend/security/perfil_csrf.php del paso 4.
if (
    empty($_SESSION['perfil_csrf'])
    || !is_string($_SESSION['perfil_csrf'])
) {
    $_SESSION['perfil_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['perfil_csrf'];

// Esta página ya no modifica la sesión.
session_write_close();

header('Cache-Control: no-store, private');

function lv_h($value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function lv_document_url($value): string
{
    $url = trim((string) ($value ?? ''));

    // Conserva rutas relativas y HTTP(S), nunca esquemas ejecutables.
    if (
        $url === ''
        || preg_match('~[\x00-\x1F\x7F\\\\]~', $url)
        || strpos($url, '//') === 0
    ) {
        return '';
    }

    $scheme = parse_url($url, PHP_URL_SCHEME);

    if (
        $scheme === false
        || (
            $scheme !== null
            && !in_array(strtolower($scheme), ['http', 'https'], true)
        )
    ) {
        return '';
    }

    return $url;
}

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    require __DIR__ . '/backend/connection/abrir_conexion.php';

    /*
     * Los nombres de las tablas proceden de tu configuración.
     * Nunca deben obtenerse de GET o POST.
     *
     * Los conteos son independientes para no multiplicar registros
     * al combinar rutinas y eventos.
     */
    $sql = "
        SELECT
            u.id_usuario,
            u.nombre_completo,
            u.correo_electronico,
            u.perfil,
            u.cedula,
            u.certificacion_diploma,

            (
                SELECT COUNT(*)
                FROM {$tblRutinas} r
                WHERE r.id_usuario_fk = u.id_usuario
            ) AS total_rutinas,

            (
                SELECT COUNT(*)
                FROM {$tblCalendarios} c
                WHERE c.id_usuario_fk = u.id_usuario
            ) AS total_eventos

        FROM {$tblUsuarios} u
        WHERE u.id_usuario = ?
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();

    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $result->free();
    $stmt->close();
    $conexion->close();
} catch (Throwable $error) {
    error_log('Learn.Viky / perfil: ' . $error->getMessage());

    http_response_code(500);

    exit(
        'No se pudo cargar tu perfil. '
        . 'Intenta nuevamente en unos minutos.'
    );
}

if (!$usuario) {
    header('Location: /learn-viky/iniciar_sesion.html?error=sesion');
    exit;
}

$nombre = (string) $usuario['nombre_completo'];
$correo = (string) $usuario['correo_electronico'];
$perfil = (string) $usuario['perfil'];
$documento = lv_document_url($usuario['certificacion_diploma']);

$controller = 'backend/controlers/perfil_controller.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta name="robots" content="noindex, nofollow">

    <title>Mi perfil | Learn.Viky</title>

    <link
        rel="icon"
        href="img/logofoca.png"
        type="image/png"
    >

    <link rel="stylesheet" href="css/styles.css">

    <script src="js/perfil.js?v=1" defer></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Poppins:wght@300;400;500;600;700;800&display=swap');
  </style>
</head>

<body class="lv-page">

    <a class="lv-skip" href="#contenido">
        Saltar al contenido
    </a>

    <!-- Iconos locales: sin librerías ni solicitudes externas. -->
    <svg
        class="lv-symbols"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <symbol id="lv-edit" viewBox="0 0 24 24">
            <path d="m15 5 4 4M4 20l4-1L20 7a2.8 2.8 0 0 0-4-4L4 15z"/>
        </symbol>

        <symbol id="lv-arrow" viewBox="0 0 24 24">
            <path d="M5 12h14m-5-5 5 5-5 5"/>
        </symbol>

        <symbol id="lv-lock" viewBox="0 0 24 24">
            <rect x="5" y="10" width="14" height="11" rx="2"/>
            <path d="M8 10V7a4 4 0 0 1 8 0v3m-4 5v2"/>
        </symbol>

        <symbol id="lv-eye" viewBox="0 0 24 24">
            <path
                d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
            />
            <circle cx="12" cy="12" r="3"/>
        </symbol>

        <symbol id="lv-chevron" viewBox="0 0 24 24">
            <path d="m8 5 7 7-7 7"/>
        </symbol>

        <symbol id="lv-exit" viewBox="0 0 24 24">
            <path d="M9 4H4v16h5m5-13 5 5-5 5m-6-5h11"/>
        </symbol>
    </svg>

   <header class="header">
    <a href="index.php"class="logo-inicio">
    <div class="logo">
      <img src="img/logofoca.png" alt="logo foca graduada"></div>
    </a>
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
      <a href="/learn-viky/backend/controlers/cerrar_sesion_controller.php" style="text-decoration:none;">
        <button class="btn-ins">CERRAR SESION</button>
      </a>
    </div>
  </header>


    <main class="lv-main" id="contenido" tabindex="-1">

  
        <div class="lv-heading">

            <p>
                Tu información y tu espacio de aprendizaje,
                en un solo lugar.
            </p>
        </div>

        <?php if (isset($_GET['error'])): ?>

            <div class="lv-alert lv-alert-error" role="alert">
                No se pudo completar la operación.
                Revisa los datos e inténtalo nuevamente.
            </div>

        <?php elseif (isset($_GET['mensaje'])): ?>

            <div class="lv-alert lv-alert-success" role="status">
                Cambios guardados correctamente.
            </div>

        <?php endif; ?>

        <div class="lv-layout">

            <!-- RESUMEN DEL PERFIL -->
            <aside
                class="lv-sidebar"
                aria-label="Resumen de tu perfil"
            >
                <section
                    class="lv-identity"
                    aria-labelledby="lv-user-name"
                >
                    <div class="lv-avatar">
                        <span aria-hidden="true">LV</span>

                        <img
                            src="img/logoperfil.png"
                            width="96"
                            height="96"
                            alt="Avatar de perfil"
                            decoding="async"
                            data-optional-image
                        >
                    </div>

                    <span class="lv-role">
                        <?= lv_h(ucfirst($perfil)) ?>
                    </span>

                    <h2 id="lv-user-name">
                        <?= lv_h($nombre) ?>
                    </h2>

                    <p class="lv-email">
                        <?= lv_h($correo) ?>
                    </p>

                    <p class="lv-session">
                        <span aria-hidden="true"></span>
                        Sesión iniciada
                    </p>

                    <div class="lv-activity">
                        <h3>Tu actividad</h3>

                        <div class="lv-stats">
                            <a href="rutinas.php">
                                <strong>
                                    <?= (int) $usuario['total_rutinas'] ?>
                                </strong>

                                <span>
                                    Rutinas
                                    <span aria-hidden="true">↗</span>
                                </span>
                            </a>

                            <a href="calendario.php">
                                <strong>
                                    <?= (int) $usuario['total_eventos'] ?>
                                </strong>

                                <span>
                                    Eventos
                                    <span aria-hidden="true">↗</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </section>

          
            </aside>

            <!-- INFORMACIÓN Y CONFIGURACIÓN -->
            <div class="lv-content">

                <section
                    class="lv-card"
                    aria-labelledby="lv-data-title"
                >
                    <div class="lv-card-head">
                        <div>
                            <h2 id="lv-data-title">
                                Información personal
                            </h2>

                            <p>Mantén tus datos actualizados.</p>
                        </div>

                        <button
                            class="lv-button lv-button-outline"
                            id="lv-edit-button"
                            type="button"
                            aria-controls="lv-profile-fields"
                            aria-expanded="false"
                            hidden
                        >
                            <svg class="lv-icon" aria-hidden="true">
                                <use href="#lv-edit"/>
                            </svg>

                            Editar
                        </button>
                    </div>

                    <form
                        class="lv-form lv-post-form"
                        id="lv-profile-form"
                        action="<?= lv_h($controller) ?>"
                        method="post"
                    >
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= lv_h($csrfToken) ?>"
                        >

                        <div
                            class="lv-fields"
                            id="lv-profile-fields"
                        >
                            <div class="lv-field lv-full">
                                <label for="lv-name">
                                    Nombre completo
                                </label>

                                <input
                                    class="lv-input"
                                    id="lv-name"
                                    name="nombre_completo_form"
                                    type="text"
                                    autocomplete="name"
                                    value="<?= lv_h($nombre) ?>"
                                    required
                                >
                            </div>

                            <div class="lv-field lv-full">
                                <label for="lv-email">
                                    Correo electrónico
                                </label>

                                <input
                                    class="lv-input"
                                    id="lv-email"
                                    name="correo_electronico_form"
                                    type="email"
                                    autocomplete="email"
                                    spellcheck="false"
                                    value="<?= lv_h($correo) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <dl class="lv-account-type">
                            <div>
                                <dt>Tipo de cuenta</dt>

                                <dd>
                                    <?= lv_h(ucfirst($perfil)) ?>
                                </dd>
                            </div>
                        </dl>

                        <?php if ($perfil === 'profesor'): ?>

                            <div class="lv-teacher">
                                <h3>Documentación docente</h3>

                                <dl class="lv-fields">
                                    <div>
                                        <dt>Cédula</dt>

                                        <dd>
                                            <?= lv_h($usuario['cedula']) ?>
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>
                                            Certificación de diploma
                                        </dt>

                                        <dd>
                                            <?php if ($documento !== ''): ?>

                                                <a
                                                    class="lv-text-link"
                                                    href="<?= lv_h($documento) ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Ver archivo

                                                    <span class="lv-sr-only">
                                                        (abre en otra pestaña)
                                                    </span>

                                                    <span aria-hidden="true">
                                                        ↗
                                                    </span>
                                                </a>

                                            <?php else: ?>

                                                Sin archivo disponible

                                            <?php endif; ?>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                        <?php endif; ?>

                        <div
                            class="lv-form-actions"
                            id="lv-profile-actions"
                        >
                            <span
                                class="lv-form-status"
                                role="status"
                                data-form-status
                            ></span>

                            <button
                                class="lv-button lv-button-quiet"
                                id="lv-cancel-button"
                                type="button"
                                hidden
                            >
                                Cancelar
                            </button>

                            <button
                                class="lv-button lv-button-primary"
                                type="submit"
                                name="btn-actualizar-perfil"
                                value="1"
                                data-pending-text="Guardando..."
                            >
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </section>

                <!-- CONTRASEÑA -->
                <details
                    class="lv-card lv-security"
                    id="lv-security"
                >
                    <summary class="lv-disclosure">
                        <span class="lv-section-icon">
                            <svg class="lv-icon" aria-hidden="true">
                                <use href="#lv-lock"/>
                            </svg>
                        </span>

                        <span class="lv-disclosure-copy">
                            <span class="lv-disclosure-title">
                                Contraseña y acceso
                            </span>

                            <span class="lv-muted">
                                Cambia la contraseña de tu cuenta.
                            </span>
                        </span>

                        <svg
                            class="lv-icon lv-chevron"
                            aria-hidden="true"
                        >
                            <use href="#lv-chevron"/>
                        </svg>
                    </summary>

                    <form
                        class="lv-form lv-post-form lv-password-form"
                        id="lv-password-form"
                        action="<?= lv_h($controller) ?>"
                        method="post"
                    >
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= lv_h($csrfToken) ?>"
                        >

                        <p class="lv-help">
                            Usa una contraseña larga que no utilices
                            en otra cuenta.
                        </p>

                        <div class="lv-fields">
                            <div class="lv-field">
                                <label for="lv-password">
                                    Nueva contraseña
                                </label>

                                <div class="lv-password-input">
                                    <input
                                        id="lv-password"
                                        name="contrasena_form"
                                        type="password"
                                        autocomplete="new-password"
                                        required
                                    >

                                    <button
                                        class="lv-eye"
                                        type="button"
                                        data-password-target="lv-password"
                                        aria-controls="lv-password"
                                        aria-label="Mostrar nueva contraseña"
                                        hidden
                                    >
                                        <svg
                                            class="lv-icon"
                                            aria-hidden="true"
                                        >
                                            <use href="#lv-eye"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="lv-field">
                                <label for="lv-password-confirm">
                                    Confirmar contraseña
                                </label>

                                <div class="lv-password-input">
                                    <input
                                        id="lv-password-confirm"
                                        name="contrasena_validada_form"
                                        type="password"
                                        autocomplete="new-password"
                                        aria-describedby="lv-password-error"
                                        required
                                    >

                                    <button
                                        class="lv-eye"
                                        type="button"
                                        data-password-target="lv-password-confirm"
                                        aria-controls="lv-password-confirm"
                                        aria-label="Mostrar confirmación de contraseña"
                                        hidden
                                    >
                                        <svg
                                            class="lv-icon"
                                            aria-hidden="true"
                                        >
                                            <use href="#lv-eye"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p
                            class="lv-field-error"
                            id="lv-password-error"
                            aria-live="polite"
                        ></p>

                        <div class="lv-form-actions">
                            <span
                                class="lv-form-status"
                                role="status"
                                data-form-status
                            ></span>

                            <button
                                class="lv-button lv-button-primary"
                                type="submit"
                                name="btn-cambiar-contrasena"
                                value="1"
                                data-pending-text="Actualizando..."
                            >
                                Actualizar contraseña
                            </button>
                        </div>
                    </form>
                </details>

                <!-- ELIMINACIÓN DE CUENTA -->
                <details class="lv-danger">
                    <summary class="lv-disclosure">
                        <span class="lv-disclosure-copy">
                            <span class="lv-disclosure-title">
                                Eliminar cuenta
                            </span>

                            <span class="lv-muted">
                                Una acción permanente. Revísala con cuidado.
                            </span>
                        </span>

                        <svg
                            class="lv-icon lv-chevron"
                            aria-hidden="true"
                        >
                            <use href="#lv-chevron"/>
                        </svg>
                    </summary>

                    <form
                        class="lv-form lv-post-form"
                        action="<?= lv_h($controller) ?>"
                        method="post"
                    >
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= lv_h($csrfToken) ?>"
                        >

                        <p class="lv-help">
                            Al eliminar tu cuenta perderás el acceso a ella.
                            Guarda antes la información que necesites.
                        </p>

                        <label class="lv-confirm">
                            <input
                                type="checkbox"
                                name="confirmar_eliminacion"
                                value="1"
                                required
                            >

                            <span>
                                Entiendo que esta acción no se puede deshacer.
                            </span>
                        </label>

                        <div class="lv-form-actions">
                            <span
                                class="lv-form-status"
                                role="status"
                                data-form-status
                            ></span>

                            <button
                                class="lv-button lv-button-danger"
                                type="submit"
                                name="btn-eliminar-perfil"
                                value="1"
                                data-pending-text="Eliminando..."
                            >
                                Eliminar mi cuenta
                            </button>
                        </div>
                    </form>
                </details>

            </div>
        </div>
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
        <p>3135287232</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 Learn.Viky. Todos los derechos reservados.</p>
    </div>
  </footer>
   
</body>
</html>