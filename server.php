<?php

$ip = "0.0.0.0";
$port = 5000;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, $ip, $port);

echo "Serveri është duke dëgjuar në $ip:$port...\n";

$clients = [];
$messages = [];

while (true) {
    $buf = '';
    $client_ip = '';
    $client_port = 0;

    $bytes = @socket_recvfrom($socket, $buf, 1024, 0, $client_ip, $client_port);

if ($bytes === false) {
    continue;
}

    $client_key = "$client_ip:$client_port";
    $clients[$client_key] = time();

    $messages[] = "$client_key -> $buf";

    echo "Mesazh nga $client_ip:$client_port -> $buf\n";

    $response = "Mesazhi u pranua";
    socket_sendto($socket, $response, strlen($response), 0, $client_ip, $client_port);
    
    foreach ($clients as $client => $last_time) {
    if (time() - $last_time > 30) {
        unset($clients[$client]);
        echo "Klienti $client u largua (timeout)\n";
    }
}
}

?>