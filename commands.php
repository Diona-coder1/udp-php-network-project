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

