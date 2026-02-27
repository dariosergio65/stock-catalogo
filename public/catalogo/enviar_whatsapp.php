<?php
session_start();

if (!isset($_SESSION['pedido_id'])) {
    header("Location: index.php");
    exit;
}

$pedido_id = $_SESSION['pedido_id'];

$telefono_comercio = "5491130348609"; // tu número

$mensaje = "Hola! Ya realicé el pago del pedido #$pedido_id.%0A%0A";
$mensaje .= "Adjunto comprobante de transferencia.%0A";
$mensaje .= "Muchas gracias!";

$url = "https://wa.me/".$telefono_comercio."?text=".$mensaje;

header("Location: $url");
exit;