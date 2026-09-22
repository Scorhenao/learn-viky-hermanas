<?php
session_start();
$logueado = isset($_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros.viky</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/logofoca.png" type="image/x-icon">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>

</head>
<body>
<header class="header">
    <a href="index.php" class="logo-inicio">
    <div class="logo">
        <img src="img/logofoca.png" alt="logo foca graduada">
    </div>
    </a>

    <nav class="navbar">
        <ul>
            <li><a href="/learn-viky/index.php">INICIO</a></li>
            <li><a href="/learn-viky/nosotros.php" class="active">NOSOTROS</a></li>
            <li><a href="/learn-viky/tips.php">TIPS</a></li>
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
                <button class="btn-ins">INICIAR SESIÓN</button>
            </a>
            <a href="/learn-viky/registrarse.html" style="text-decoration: none;">
                <button class="btn-registr">REGISTRARSE</button>
            </a>
        <?php } ?>
    </div>

</header>

<main class="about-page">
    <section class="about-hero">
        <div class="about-copy">
            
            <h1>Una comunidad para estudiar </h1>
            <p>Learn.Viky nace para convertir el estudio en una experiencia más clara, organizada y motivadora. Aquí cada estudiante puede planear su tiempo, seguir sus metas y encontrar recursos útiles para avanzar con confianza.</p>
        </div>
        <div class="about-visual">
            <img src="img/chica_estudiandp.png" alt="Estudiantes usando Learn.Viky">
        </div>
    </section>

    <section class="about-values">
        <div class="value-card">
            <h3>Organización</h3>
            <p>Planifica tus rutinas, fechas importantes y tareas con una estructura visual clara.</p>
        </div>
        <div class="value-card">
            <h3>Aprendizaje</h3>
            <p>Consulta videos y recursos por materia para reforzar cada tema con contenido concreto.</p>
        </div>
        <div class="value-card">
            <h3>Motivación</h3>
            <p>Diseñamos una experiencia amable y visual para que estudiar sea más constante y menos pesado.</p>
        </div>
    </section>

    <section class="about-team">
        <div class="team-heading">
            <p class="section-tag">Equipo</p>
            <h2>Conoce a quienes impulsan Learn.Viky</h2>
        </div>

        <div class="nosotros">
            <article class="imagenes">
                <img src="img/vale2.jpeg" alt="foto vale">
                <h3>Valentina Córdoba</h3>
                <p>Supervisa el avance del proyecto y coordina la organización del equipo para cumplir los objetivos.</p>
            </article>
            <article class="imagenes">
                <img src="img/isa.jpeg" alt="foto isa">
                <h3>Isabella Córdoba</h3>
                <p>Desarrolla la experiencia digital y las funcionalidades esenciales para que la plataforma funcione bien.</p>
            </article>
            <article class="imagenes">
                <img src="img/karen.jpeg" alt="foto karen">
                <h3>Karen Álvarez</h3>
                <p>Gestiona la documentación del proyecto y mantiene cada proceso claro, actualizado y ordenado.</p>
            </article>
            <article class="imagenes">
                <img src="img/yuly.jpeg" alt="foto yuly">
                <h3>Alejandra Montoya</h3>
                <p>Cuida la estructura y la seguridad de la información para mantener la plataforma confiable.</p>
            </article>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="footer-container">

        <div class="footer-section">
            <h2>LEARN.VIKY</h2>
            <p>El mejor espacio para aprender, organizarte y mejorar cada día.</p>
        </div>


        <div class="footer-section">
            <h3>ENLACES</h3>
            <a href="/learn-viky/index.php">INICIO</a>
            <a href="/learn-viky/nosotros.php">NOSOTROS</a>
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
        <p>3135287232</p>
    </div>

    </div>

    <div class="footer-bottom">
        <p>@ 2026 Learn.Viky. Todos los derechos reservados.</p>
    </div>

</footer>

</body>
</html>
