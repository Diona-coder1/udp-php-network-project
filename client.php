<?php

$serverIP = "127.0.0.1";
$serverPort = 5000;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, [
    "sec" => 2,
    "usec" => 0
]);

echo "Enter username: ";
$user = trim(fgets(STDIN));

echo "Type 'login' to start\n";

while (true) {

    echo ">> ";
    $msg = trim(fgets(STDIN));

    if ($msg === "exit") break;
    if (empty($msg)) continue;

    $packet = $user . "|" . $msg;

    socket_sendto($socket, $packet, strlen($packet), 0, $serverIP, $serverPort);

    $from = "";
    $port = 0;

    if (@socket_recvfrom($socket, $response, 4096, 0, $from, $port) === false) {
        echo "No response from server\n";
        continue;
    }

    if (str_starts_with($response, "FILE|")) {
        $content = substr($response, 5);
        file_put_contents("downloaded.txt", $content);
        echo "File downloaded!\n";
    } else {
        echo "SERVER: $response\n";
    }
}
?>
