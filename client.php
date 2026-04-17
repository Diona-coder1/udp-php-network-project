<?php

$serverIP = "127.0.0.1";
$serverPort = 5000;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

if (!$socket) {
    die("Socket creation failed\n");
}

echo "Enter username: ";
$user = trim(fgets(STDIN));

echo "Type 'login' to enter system\n\n";

$role = "user";

while (true) {

    echo ">> ";
    $msg = trim(fgets(STDIN));

    if ($msg === "exit") break;

    $packet = $user . "|" . $msg;

    socket_sendto($socket, $packet, strlen($packet), 0, $serverIP, $serverPort);

    $from = "";
    $port = 0;

    socket_recvfrom($socket, $response, 4096, 0, $from, $port);

    echo "SERVER: $response\n";

    // detect role
    if (str_contains($response, "ROLE: ADMIN")) {
        $role = "admin";
    }

    if ($msg === "login") {

        if ($role === "admin") {
            echo "\nADMIN MENU:\n";
            echo "/list\n/info\n/upload file|text\n/delete file\n/read file\n/search word\n\n";
        } else {
            echo "\nUSER MODE: chat only\n\n";
        }
    }
}

socket_close($socket);
?>