<?php

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

$message = "Hello Server";
socket_sendto($socket, $message, strlen($message), 0, "192.168.178.44", 5000);

echo "Mesazhi u dergua\n";

?>