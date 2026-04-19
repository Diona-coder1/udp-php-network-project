UDP Socket Programming Project – Computer Networks
Overview
This project implements a client–server communication system using UDP sockets in PHP, accompanied by an HTTP monitoring server for real‑time observation of system activity.
Each team member developed individual modules that integrate into a complete file‑management and monitoring system over a network.

Team Members
Name	File / Responsibility
Diona Gerxhaliu	server.php – UDP Socket Server
Agnesa Godeni	client.php – UDP Client
Donat Hasani	stats.php – HTTP Monitoring Server
Djellza Ramaja	commands.php – File Commands & Operations
System Architecture
1. UDP Server (server.php)
Handles communication with clients and manages sessions, roles, logs, and timeouts.

Features

Listens on IP 0.0.0.0 and port 5000
Max 4 concurrent clients
Roles: admin and user
Logs all messages (clients.log, commands.log, messages.log)
Removes inactive clients after 30 seconds
Updates runtime stats in data/stats.json
Works concurrently with HTTP server (stats.php)
2. Client (client.php)
Clients communicate with the UDP server via text messages and execute commands by role.

Role	Permissions
Admin	Full access (write, read, execute)
User	Read‑only (/list, /read <filename>)
Admin Commands

Command	Description
/list	List all files
/read <filename>	Read file content
`/upload	`
/download <filename>	Download file from server
/delete <filename>	Delete file
/search <keyword>	Search filenames for keyword
/info <filename>	Show file size, creation/modification date
Communication format: username|message
Admin responses are instant; users include a delay (usleep(500000)).

3. HTTP Monitoring Server (stats.php)
Runs in parallel on port 8080 to display server statistics in JSON format.

Access:



[localhost](http://localhost:8080/stats)
Returned Data

Active clients (IP & ports)
Total client count
Message count
Client messages
Timestamp
Example Output

json


{
  "status": "OK",
  "server_time": "2026-04-19 13:29:55",
  "active_clients": 2,
  "ip_addresses": ["127.0.0.1:50249", "127.0.0.1:50250"],
  "messages_count": 14
}
4. Commands Module (commands.php)
Implements all server‑side file operations:



listFiles($path)
readFileContent($path, $filename)
uploadFile($path, $filename, $content)
downloadFile($path, $filename)
deleteFile($path, $filename)
searchFiles($path, $keyword)
fileInfo($path, $filename)
Includes path sanitization to ensure secure file access.

Directory Structure


udp-php-network-project/
├── server.php
├── client.php
├── stats.php
├── commands.php
│
├── files/          # Uploaded & readable files
├── data/           # Runtime stats.json
│
├── clients.log
├── commands.log
├── messages.log
├── downloaded.txt
└── README.md
Execution Guide
Start the UDP Server
bash


php server.php
Output:



Server running on 0.0.0.0:5000...
Start the HTTP Monitoring Server
bash


php stats.php
Output:



HTTP Stats Server running on [localhost](http://localhost:8080/stats)
Start the Client
bash


php client.php
Interaction example:



Enter username: diona
Type 'login' to start
>> login
SERVER: Type role: admin / user
>> admin
SERVER: Logged in as admin
>> /list
SERVER: file1.txt
To monitor activity:
Open 
localhost

Technical Details
Component	Description
Language	PHP
Protocols	UDP (client–server), TCP/HTTP (monitor)
Timeout	Inactivity after 30s removes client
Security	Sanitized paths for safe file operations
OS Support	Windows, Linux, macOS (PHP CLI required)
Concurrency	UDP and HTTP servers run in parallel
Conclusion
This project demonstrates UDP socket programming in PHP with:

Session and role‑based management
File‑system command execution
Automatic timeout handling
Real‑time monitoring via HTTP
It fully meets the functional and structural requirements defined in the Computer Networks course specification.

