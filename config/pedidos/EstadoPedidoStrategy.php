<?php

interface EstadoPedidoStrategy {
    public function ejecutar($pdo, $pedido_id);
}
