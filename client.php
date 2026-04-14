<?php

$serverIP = "127.0.0.1";
$serverPort = 5000;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

if (!$socket) {
    die("Failed to create socket\n");
}

echo "Username (admin/user): ";
$user = trim(fgets(STDIN));

echo "Connected to server...\n";

if ($user === "admin") {
    echo "ADMIN MODE\n";
    echo "Commands:\n";
    echo "/list\n";
    echo "/info\n";
    echo "/read filename\n";
    echo "/upload filename|content\n";
    echo "/delete filename\n";
    echo "/search keyword\n\n";
} else {
    echo "USER MODE (only chat)\n\n";
}

while (true) {

    echo ">> ";
    $msg = trim(fgets(STDIN));

    if ($msg === "exit") {
        break;
    }

    // format: user|message
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

    socket_recvfrom($socket, $response, 2048, 0, $from, $port);

    echo "SERVER: $response\n";
}

socket_close($socket);

?>