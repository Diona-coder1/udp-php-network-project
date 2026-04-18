<?php
set_time_limit(0);

$ip = "0.0.0.0";
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (!$socket) {
    die("Socket create failed\n");
}

if (!socket_bind($socket, $ip, $port)) {
    die("Bind failed: " . socket_strerror(socket_last_error()) . "\n");
}

if (!socket_listen($socket)) {
    die("Listen failed\n");
}

echo "HTTP Stats Server running on http://localhost:$port/stats\n";

// funksion për lexim të sigurt
function read_file_safe($file) {
    if (!file_exists($file)) return [];
    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

while (true) {

    $client = socket_accept($socket);

    if ($client === false) {
        continue;
    }

    $request = socket_read($client, 1024);

    if (preg_match('#GET /stats#', $request)) {

        $clients_file = __DIR__ . "/clients.log";
        $messages_file = __DIR__ . "/messages.log";

        $clients = read_file_safe($clients_file);
        $messages = read_file_safe($messages_file);

        $unique_clients = array_values(array_unique($clients));

        $data = json_encode([
            "status" => "OK",
            "server_time" => date("Y-m-d H:i:s"),
            "active_clients" => count($unique_clients),
            "ip_addresses" => $unique_clients,
            "messages_count" => count($messages),
            "messages" => array_slice($messages, -50)
        ], JSON_PRETTY_PRINT);

        $response =
            "HTTP/1.1 200 OK\r\n" .
            "Content-Type: application/json\r\n\r\n" .
            $data;

    } else {
        $response =
            "HTTP/1.1 404 Not Found\r\n\r\nNot Found";
    }

    socket_write($client, $response);
    socket_close($client);
}