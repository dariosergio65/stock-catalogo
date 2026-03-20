<?php
require_once "../../config/auth.php";
require_once "../../config/db.php";
require_once "../../config/permisos.php";

verificarPermiso('usuarios');

$logs = $pdo->query("
    SELECT a.*, u.nombre 
    FROM auditoria a
    JOIN usuarios u ON a.usuario_id = u.id
    ORDER BY a.fecha DESC
    LIMIT 500
")->fetchAll();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Auditoría</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap + icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* HEADER */
.header {
    background: linear-gradient(135deg, #212529, #343a40);
    color: white;
    padding: 1.5rem 1rem;
    border-radius: 0 0 20px 20px;
    margin-bottom: 1rem;
}

/* CARDS MOBILE */
.log-card {
    background: white;
    border-radius: 15px;
    padding: 0.8rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border: 1px solid #dee2e6;
}

.log-top {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: #6c757d;
}

.log-user {
    font-weight: 600;
}

.log-body {
    margin-top: 0.3rem;
}

.log-modulo {
    font-size: 0.75rem;
    color: #0d6efd;
    text-transform: uppercase;
}

.log-accion {
    font-weight: 600;
    font-size: 0.9rem;
}

.log-desc {
    font-size: 0.85rem;
    color: #555;
}

.log-ip {
    font-size: 0.7rem;
    color: #999;
}

/* ACCIONES COLORES */
.accion-alta { color: #198754; }
.accion-baja { color: #dc3545; }
.accion-modificacion { color: #0d6efd; }

/* DESKTOP TABLE */
@media (min-width: 768px) {
    .mobile-view { display: none; }
}

@media (max-width: 767px) {
    .desktop-view { display: none; }
}
</style>

</head>

<body class="bg-light">

<!-- HEADER -->
<div class="header d-flex justify-content-between align-items-center">
    <h5 class="m-0">
        <i class="bi bi-clipboard-data"></i> Auditoría
    </h5>
    <a href="../../public/index.php" class="btn btn-light btn-sm">
        ⬅ Menú
    </a>
</div>

<div class="container-fluid px-3">

    <!-- ===================== -->
    <!-- 📱 MOBILE (CARDS) -->
    <!-- ===================== -->
    <div class="mobile-view">

        <?php foreach($logs as $l): ?>

        <div class="log-card">

            <div class="log-top">
                <span><?= date('d/m H:i', strtotime($l['fecha'])) ?></span>
                <span class="log-user"><?= htmlspecialchars($l['nombre']) ?></span>
            </div>

            <div class="log-body">
                <div class="log-modulo"><?= htmlspecialchars($l['modulo']) ?></div>

                <div class="log-accion accion-<?= strtolower($l['accion']) ?>">
                    <?= strtoupper($l['accion']) ?>
                </div>

                <div class="log-desc">
                    <?= htmlspecialchars($l['descripcion']) ?>
                </div>

                <div class="log-ip">
                    IP: <?= $l['ip'] ?>
                </div>
            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <!-- ===================== -->
    <!-- 💻 DESKTOP (TABLA) -->
    <!-- ===================== -->
    <div class="desktop-view">

        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>IP</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($logs as $l): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($l['fecha'])) ?></td>
                    <td><?= htmlspecialchars($l['nombre']) ?></td>
                    <td><?= htmlspecialchars($l['modulo']) ?></td>
                    <td>
                        <span class="fw-bold accion-<?= strtolower($l['accion']) ?>">
                            <?= strtoupper($l['accion']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($l['descripcion']) ?></td>
                    <td><?= $l['ip'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

</body>
</html>