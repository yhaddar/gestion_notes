<?php 
require_once "../config/db.php";
require_once "../authentication/session.php";
session_start();
$id = $_GET['id'];
$delete_modules = $pdo->prepare("DELETE FROM mentions WHERE id = ?");
$delete_modules->execute([$id]);

header("Location: ./afficher.php");
?>