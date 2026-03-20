<?php

class PagadoStrategy
{
    public function ejecutar($pdo, $pedido_id)
    {
        // ✅ No hacer nada con stock
        // Solo cambia el estado del pedido

        return true;
    }
}