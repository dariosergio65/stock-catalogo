<?php
require_once __DIR__ . "/db.php";

function registrarAuditoria($accion, $modulo, $descripcion) {
    global $pdo;

    if (!isset($_SESSION['usuario_id'])) return;

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';

    $stmt = $pdo->prepare("
        INSERT INTO auditoria 
        (usuario_id, accion, modulo, descripcion, ip)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION['usuario_id'],
        $accion,
        $modulo,
        $descripcion,
        $ip
    ]);
}
