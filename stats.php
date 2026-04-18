<?php
set_time_limit(0);

$ip = "0.0.0.0";
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (!$socket) die("Socket create failed\n");
if (!socket_bind($socket, $ip, $port)) die("Bind failed\n");
if (!socket_listen($socket)) die("Listen failed\n");

echo "HTTP Stats Server running on http://localhost:$port/stats\n";







function read_file_safe($file) {
    if (!file_exists($file)) {
        return [];
    }
    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}









while (true) {

    $client = socket_accept($socket);

    if ($client === false) {
        continue;
    }
    $request = socket_read($client, 1024);

if (preg_match('#GET /stats#', $request)) {

    $response =
        "HTTP/1.1 200 OK\r\n\r\n" .
        "Stats endpoint working";

} else {

    $response =
        "HTTP/1.1 404 Not Found\r\n\r\nNot Found";
}







































    socket_write($client, $response);


    socket_close($client);
}







?>