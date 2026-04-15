<?php

// ================== CONFIG ==================
$storagePath = __DIR__ . "/files/";

// ================== ADMIN ==================
function isAdmin($clientId) {
    return $clientId === "admin"; // change later if needed
}
// ================== SECURITY ==================
function sanitizePath($basePath, $filename) {
    $realPath = realpath($basePath . $filename);
    if ($realPath === false || strpos($realPath, realpath($basePath)) !== 0) {
        return false;
    }
    return $realPath;
}
// ================== FILE SYSTEM ==================
function listFiles($path) {
    $files = array_diff(scandir($path), array('.', '..'));
    return empty($files) ? "No files found" : implode("\n", $files);
}

function readFileContent($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return "❌ File not found";
    return file_get_contents($file);
}

