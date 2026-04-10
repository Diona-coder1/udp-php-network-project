<?php

$ip = "0.0.0.0";
$port = 5000;

// krijo socket UDP
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

// lidh socket me IP dhe port
socket_bind($socket, $ip, $port);

echo "Serveri është duke dëgjuar në $ip:$port...\n";

?>
