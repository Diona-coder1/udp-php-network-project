# UDP Socket Programming Project – Computer Networks

## Overview

This project implements a client–server communication system using UDP sockets in PHP, combined with an HTTP monitoring server for real-time observation.

Each team member developed individual modules that integrate into a complete file management and monitoring system over a network.

---

## Team Members

| Name            | Responsibility                       |
| --------------- | ------------------------------------ |
| Diona Gerxhaliu | `server.php` – UDP Socket Server     |
| Agnesa Godeni   | `client.php` – UDP Client            |
| Donat Hasani    | `stats.php` – HTTP Monitoring Server |
| Djellza Ramaja  | `commands.php` – File Operations     |

---

## System Architecture

### 1. UDP Server (`server.php`)

Handles communication with clients and manages sessions, roles, logs, and timeouts.

#### Features

* Listens on 0.0.0.0:5000
* Supports max 4 concurrent clients
* Roles: admin, user
* Logs activity:

  * clients.log
  * commands.log
  * messages.log
* Removes inactive clients after 30 seconds
* Updates runtime stats in data/stats.json
* Works in parallel with HTTP server

---

### 2. Client (`client.php`)

Clients communicate with the server via text messages.

#### Roles & Permissions

| Role  | Permissions                         |
| ----- | ----------------------------------- |
| Admin | Full access (read, write, execute)  |
| User  | Read-only (/list, /read <filename>) |

#### Admin Commands

| Command              | Description    |
| -------------------- | -------------- |
| /list                | List all files |
| /read <filename>     | Read file      |
| /upload <filename>   | Upload file    |
| /download <filename> | Download file  |
| /delete <filename>   | Delete file    |
| /search <keyword>    | Search files   |
| /info <filename>     | File info      |

Communication format:

```
username|message
```

Note:

* Admin responses are instant
* User responses include delay (usleep(500000))

---

### 3. HTTP Monitoring Server (`stats.php`)

Runs on:

```
http://localhost:8080/stats
```

Returns JSON data:

```json
{
  "status": "OK",
  "server_time": "2026-04-19 13:29:55",
  "active_clients": 2,
  "ip_addresses": ["127.0.0.1:50249", "127.0.0.1:50250"],
  "messages_count": 14
}
```

---

### 4. Commands Module (`commands.php`)

Handles all file operations:

* listFiles($path)
* readFileContent($path, $filename)
* uploadFile($path, $filename, $content)
* downloadFile($path, $filename)
* deleteFile($path, $filename)
* searchFiles($path, $keyword)
* fileInfo($path, $filename)

Includes path sanitization for secure file access.

---

## Directory Structure

```
udp-php-network-project/
│
├── server.php
├── client.php
├── stats.php
├── commands.php
│
├── files/
├── data/
│
├── clients.log
├── commands.log
├── messages.log
├── downloaded.txt
└── README.md
```

---

## Execution Guide

### Start UDP Server

```bash
php server.php
```

Output:

```
Server running on 0.0.0.0:5000...
```

---

### Start HTTP Monitoring Server

```bash
php stats.php
```

Output:

```
HTTP Stats Server running on http://localhost:8080/stats
```

---

### Start Client

```bash
php client.php
```

Example:

```
Enter username: diona
>> login
SERVER: Type role: admin / user
>> admin
SERVER: Logged in as admin
>> /list
SERVER: file1.txt
```

---

## Technical Details

| Component   | Description           |
| ----------- | --------------------- |
| Language    | PHP                   |
| Protocols   | UDP, HTTP             |
| Timeout     | 30 seconds inactivity |
| Security    | Path sanitization     |
| OS Support  | Windows, Linux, macOS |
| Concurrency | UDP + HTTP parallel   |

---

## Conclusion

This project demonstrates:

* UDP socket programming in PHP
* Role-based access control
* File system operations over network
* Client session management
* Automatic timeout handling
* Real-time monitoring via HTTP

It fully meets the functional and structural requirements of the Computer Networks course.
