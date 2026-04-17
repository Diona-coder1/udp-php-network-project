<?php

$serverIP = "127.0.0.1";
$serverPort = 5000;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

echo "Enter username: ";
$user = trim(fgets(STDIN));

echo "Type 'login' to start system\n\n";

while (true) {

    echo ">> ";
    $msg = trim(fgets(STDIN));

    if ($msg === "exit") break;

    $packet = $user . "|" . $msg;

    socket_sendto(
        $socket,
        $packet,
        strlen($packet),
        0,
        $serverIP,
        $serverPort
    );

    $from = "";
    $port = 0;

    socket_recvfrom($socket, $response, 4096, 0, $from, $port);

    echo "SERVER: $response\n";
}
?>