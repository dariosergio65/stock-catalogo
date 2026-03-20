<?php
require_once "CanceladoStrategy.php";
require_once "EntregadoStrategy.php";
require_once "PagadoStrategy.php";
require_once "EnviadoStrategy.php";

class PedidoContext {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cambiarEstado($pedido_id, $estado) {

        $strategy = $this->resolverStrategy($estado);

        if (!$strategy) {
            throw new Exception("Estado inválido");
        }

        // ⚠️ IMPORTANTE: todavía NO ejecutamos nada
        // solo devolvemos la strategy

        return $strategy;
    }

    private function resolverStrategy($estado) {

        switch ($estado) {
            case 'pendiente': return new PendienteStrategy();
            case 'pagado': return new PagadoStrategy();
            case 'enviado': return new EnviadoStrategy();
            case 'entregado': return new EntregadoStrategy();
            case 'cancelado': return new CanceladoStrategy();
            default: return null;
        }
    }
}