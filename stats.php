<?php
header('Content-Type: application/json');

function read_file($file) {
    if (!file_exists($file)) return [];
    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$clients = read_file("clients.log");
$messages = read_file("messages.log");

$unique_ips = array_values(array_unique($clients));

$response = [
    "status" => "OK",
    "active_clients" => count($unique_ips),
    "ip_addresses" => $unique_ips,
    "messages_count" => count($messages),
    "messages" => array_slice($messages, -50)
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>