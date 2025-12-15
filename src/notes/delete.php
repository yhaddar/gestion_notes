<?php 
require_once "../config/db.php";
session_start();
$id = $_GET['id'];
$delete_modules = $pdo->prepare("DELETE FROM notes WHERE id = ?");
$delete_modules->execute([$id]);

header("Location: ./afficher.php");
?>