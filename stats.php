<?php
set_time_limit(0);

$ip = "0.0.0.0";
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (!$socket) die("Socket create failed\n");
if (!socket_bind($socket, $ip, $port)) die("Bind failed\n");
if (!socket_listen($socket)) die("Listen failed\n");

echo "HTTP Stats Server running on http://localhost:$port/stats\n";
















while (true) {

    $client = socket_accept($socket);

    if ($client === false) {
        continue;
    }

    $response = "Hello from server";

    socket_write($client, $response);


    socket_close($client);
}


















function read_file_safe($file) {
    if (!file_exists($file)) {
        return [];
    }
    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$clients_file = __DIR__ . "/clients.log";
$messages_file = __DIR__ . "/messages.log";

$clients = read_file_safe($clients_file);
$messages = read_file_safe($messages_file);

$unique_ips = array_values(array_unique($clients));

$response = [
    "status" => "OK",
    "active_clients" => count($unique_ips),
    "ip_addresses" => $unique_ips,
    "messages_count" => count($messages),
    "messages" => array_slice($messages, -50)
];


?>