<?php

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

$message = "Hello Server";
socket_sendto($socket, $message, strlen($message), 0, "127.0.0.1", 5000);

echo "Mesazhi u dergua\n";

?>