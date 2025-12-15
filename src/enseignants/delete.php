<?php 
require_once "../config/db.php";
session_start();
$id = $_GET['id'];
$delete_modules = $pdo->prepare("DELETE FROM module WHERE id_enseignant = ?");
$delete_modules->execute([$id]);
$prepare = $pdo->prepare("DELETE FROM enseignant WHERE id = ?");
$prepare->execute([$id]);
header("Location: ./afficher.php");
?>