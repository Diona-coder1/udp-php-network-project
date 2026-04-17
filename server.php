<?php
set_time_limit(0);
require_once "commands.php";

$ip = "0.0.0.0";
$port = 5000;
$max_clients = 4;
$timeout = 30;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, $ip, $port);

echo "Server running on $ip:$port...\n";

$clients = [];
$message_count = 0;

$fileDir = __DIR__ . "/files/";
$dataDir = __DIR__ . "/data/";

if (!file_exists($fileDir)) mkdir($fileDir);
if (!file_exists($dataDir)) mkdir($dataDir);


while (true) {
    $buf = '';
    $client_ip = '';
    $client_port = 0;

    socket_recvfrom($socket, $buf, 2048, 0, $client_ip, $client_port);
     if (!$buf) continue;

    $key = "$client_ip:$client_port";

    $user = "unknown";
    $msg = $buf;

    if (strpos($buf, "|") !== false) {
    list($user, $msg) = explode("|", $buf, 2);
    $clients[$client_key]["user"] = $user;
    }

    // Limit
    if (!isset($clients[$key]) && count($clients) >= $max_clients) {
         $response = "Server full (max $max_clients clients)";
        socket_sendto($socket, $response, strlen($response), 0, $client_ip, $client_port);
        continue;
    }

     // INIT CLIENT
    if (!isset($clients[$key])) {
        $clients[$key] = [
            "user" => "unknown",
            "role" => null,
            "logged" => false,
            "awaiting_role" => false,
            "last" => time()
        ];
    } else {
        $clients[$key]["last"] = time();
    }
    // PARSE
    $user = "unknown";
    $msg = $buf;

    if (strpos($buf, "|") !== false) {
        list($user, $msg) = explode("|", $buf, 2);
        $clients[$key]["user"] = $user;
    }

    echo "[$key][$user] $msg\n";
    $response = "";

    // LOGIN
    if ($msg === "login") {
        $clients[$key]["logged"] = true;
        $clients[$key]["awaiting_role"] = true;
        $response = "Type role: admin / user";
    }

    // ROLE
    elseif ($clients[$key]["awaiting_role"]) {
        if ($msg === "admin" || $msg === "user") {
            $clients[$key]["role"] = $msg;
            $clients[$key]["awaiting_role"] = false;
            $response = "Logged in as $msg";
        } else {
            $response = "Invalid role (admin/user)";
        }
    }

    // ADMIN
    elseif ($clients[$key]["role"] === "admin") {
        if (strpos($msg, "/") === 0) {
            $response = handleCommand($msg, "admin", $fileDir);
        } else {
            $response = "Admin message received";
        }
    }
    // USER
    elseif ($clients[$key]["role"] === "user") {
        usleep(500000); // slower response
        if (strpos($msg, "/") === 0) {
            $response = "Permission denied";
        } else {
            $response = "User message received";
        }
    }

    else {
        $response = "Please login first";
    }

    //  Shkruan klientët në clients.log (FIX path)
    file_put_contents(__DIR__ . "/clients.log", implode("\n", array_keys($clients)));

    // Ruajnë mesazhet
    $messages[] = "$client_key -> $buf";

    //  Shkruan mesazhet në messages.log (FIX path)
    file_put_contents(__DIR__ . "/messages.log", "$client_key -> $buf\n", FILE_APPEND);

    echo "Mesazh nga $client_ip:$client_port -> $buf\n";

    $response = "Mesazhi u pranua";
    socket_sendto($socket, $response, strlen($response), 0, $client_ip, $client_port);

    // Timeout për klientët
    foreach ($clients as $client => $last_time) {
        if (time() - $last_time > 30) {
            unset($clients[$client]);
            echo "Klienti $client u largua (timeout)\n";
        }
    }
}

?>