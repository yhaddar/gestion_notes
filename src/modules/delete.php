<?php 
require_once "../config/db.php";
session_start();
$id = $_GET['id'];
$delete_modules = $pdo->prepare("DELETE FROM etudiant_module WHERE id_module = ?");
$delete_modules->execute([$id]);

$delete_coefficient = $pdo->prepare("DELETE FROM coefficient WHERE id_module = ?");
$delete_coefficient->execute([$id]);

$prepare = $pdo->prepare("DELETE FROM module WHERE id = ?");
$prepare->execute([$id]);

header("Location: ./afficher.php");
?>