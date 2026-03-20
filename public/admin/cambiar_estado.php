<?php
session_start();

require_once "../../config/db.php";
require_once "../../config/stock.php";
require_once "../../config/pedidos/PedidoContext.php";

$id = (int)($_GET['id'] ?? 0);
$estado = $_GET['estado'] ?? '';

$validos = ['pendiente','pagado','enviado','cancelado','entregado'];

if(!$id || !in_array($estado, $validos)){
    die("Datos inválidos");
}

try {

    $pdo->beginTransaction();

    // 🔎 Obtener pedido actual
    $stmt = $pdo->prepare("SELECT estado, fecha FROM pedidos WHERE id=?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch();

    if(!$pedido){
        throw new Exception("Pedido inexistente");
    }

    $estado_actual = $pedido['estado'];

    // 🔒 Control de transición
    $transiciones = [
        'pendiente' => ['pagado','cancelado'],
        'pagado' => ['enviado','cancelado'],
        'enviado' => ['entregado','cancelado'],
        'entregado' => ['cancelado'],
        'cancelado' => []
    ];

    if (!in_array($estado, $transiciones[$estado_actual])) {
        throw new Exception("Cambio de estado no permitido");
    }

    // 🔒 Regla de 10 días para cancelación
    if ($estado == 'cancelado' && $estado_actual == 'entregado') {

        $fechaPedido = new DateTime($pedido['fecha']);
        $hoy = new DateTime();

        $diff = $fechaPedido->diff($hoy)->days;

        if ($diff > 10) {
            throw new Exception("No se puede cancelar: pasaron más de 10 días");
        }
    }

    if ($estado == 'enviado') {

        require_once "../../config/pedidos/PedidoContext.php";

        $context = new PedidoContext($pdo);
        $strategy = $context->cambiarEstado($id, 'enviado');

        $strategy->ejecutar($pdo, $id);

        $stmt = $pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
        $stmt->execute([$estado,$id]);

        $pdo->commit();

        header("Location: pedido_ver.php?id=".$id);
        exit;
    }

    if ($estado == 'entregado') {

        $context = new PedidoContext($pdo);
        $strategy = $context->cambiarEstado($id, 'entregado');

        $strategy->ejecutar($pdo, $id);

        $stmt = $pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
        $stmt->execute([$estado,$id]);

        $pdo->commit();

        header("Location: pedido_ver.php?id=".$id);
        exit;
    }

    // 🧠 STRATEGY (EL CORAZÓN DEL SISTEMA)
    $context = new PedidoContext($pdo);
    $strategy = $context->cambiarEstado($id, $estado);

    // ejecutar lógica específica del estado
    $strategy->ejecutar($pdo, $id);

    // 📝 actualizar estado del pedido
    $stmt = $pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->execute([$estado,$id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error: " . $e->getMessage());
}

header("Location: pedido_ver.php?id=".$id);
exit;