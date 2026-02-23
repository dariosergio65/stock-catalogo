<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

function permisosPorRol() {
    return [
        'admin' => [
            'productos','categorias','depositos','clientes','proveedores',
            'movimientos','kardex','reportes','usuarios','dashboard','permisos'
        ],
        'empleado' => [
            'productos','clientes','movimientos','kardex','dashboard'
        ],
        'operador' => [
            'productos','movimientos','kardex','dashboard'
        ],
        'consulta' => [
            'kardex','dashboard','reportes'
        ]
    ];
}

function usuarioTienePermisoExtra($usuario_id, $modulo) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 1 FROM permisos_usuario 
        WHERE usuario_id = ? AND modulo = ?
    ");
    $stmt->execute([$usuario_id, $modulo]);
    return $stmt->fetchColumn() ? true : false;
}

function tienePermiso($modulo) {
    if (!isset($_SESSION['rol'], $_SESSION['usuario_id'])) {
        return false;
    }

    $rol = $_SESSION['rol'];
    $usuario_id = $_SESSION['usuario_id'];

    $permisos = permisosPorRol();

    // 1) Permiso por rol
    if (in_array($modulo, $permisos[$rol] ?? [])) {
        return true;
    }

    // 2) Permiso individual por usuario
    return usuarioTienePermisoExtra($usuario_id, $modulo);
}

function verificarPermiso($modulo) {
    if (!tienePermiso($modulo)) {
        echo "<div style='padding:20px;font-family:Arial'>
                <h3>⛔ Acceso denegado</h3>
                <p>No tiene permisos para acceder a este módulo.</p>
                <a href='../../public/index.php'>⬅ Volver al menú</a>
              </div>";
        exit;
    }
}
