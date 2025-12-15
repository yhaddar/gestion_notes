<?php 
require_once "../config/db.php";
session_start();
$id = $_GET['id'];
$delete_modules = $pdo->prepare("DELETE FROM etudiant_module WHERE id_etudiant = ?");
$delete_modules->execute([$id]);
$note = $pdo->prepare("DELETE FROM notes WHERE id_etudiant = ?");
$note->execute([$id]);
$prepare = $pdo->prepare("DELETE FROM etudiant WHERE id = ?");
$prepare->execute([$id]);
header("Location: ./afficher.php");
?>