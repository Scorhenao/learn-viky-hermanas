<?php

// Incluir al principio de perfil_controller.php,
// antes de procesar operaciones o modificar datos.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);

    exit('Metodo no permitido.');
}

$lvReceivedToken = $_POST['csrf_token'] ?? null;
$lvSessionToken = $_SESSION['perfil_csrf'] ?? null;

if (
    empty($_SESSION['id_usuario'])
    || !is_string($lvReceivedToken)
    || !is_string($lvSessionToken)
    || $lvSessionToken === ''
    || !hash_equals($lvSessionToken, $lvReceivedToken)
) {
    http_response_code(403);

    exit(
        'Solicitud no valida. '
        . 'Recarga tu perfil e intenta nuevamente.'
    );
}

if (
    isset($_POST['btn-eliminar-perfil'])
    && ($_POST['confirmar_eliminacion'] ?? '') !== '1'
) {
    http_response_code(400);

    exit('Debes confirmar la eliminacion de tu cuenta.');
}