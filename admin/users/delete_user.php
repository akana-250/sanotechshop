<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";

// Reba niba ID yatanzwe
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Reba niba user abaho
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: index.php?error=User not found");
    exit;
}

// Ntukemere ko admin yisiba
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
    header("Location: index.php?error=You cannot delete your own account");
    exit;
}

try {

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php?success=User deleted successfully");
    exit;

} catch (PDOException $e) {

    header("Location: index.php?error=Unable to delete user");
    exit;

}