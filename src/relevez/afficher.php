<?php
require_once "../config/db.php";
require("fpdf/fpdf.php");

$id_etudiant = $_GET['id'];

$etudiant_stmt = $pdo->prepare("
    SELECT nom, prenom 
    FROM etudiant 
    WHERE id = ?
");
$etudiant_stmt->execute([$id_etudiant]);
$etudiant = $etudiant_stmt->fetch(PDO::FETCH_ASSOC);

$notes_stmt = $pdo->prepare("
    SELECT 
        m.intitule AS module,
        c.coefficient,
        n.note_cc,
        n.note_exam,
        n.moyenne_module
    FROM notes n
    JOIN module m ON n.id_module = m.id
    LEFT JOIN coefficient c ON m.id = c.id_module
    WHERE n.id_etudiant = ?
    ORDER BY m.intitule
");

$notes_stmt->execute([$id_etudiant]);
$notes = $notes_stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Releve de Notes',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,8,'Etudiant :',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,$etudiant['nom'].' '.$etudiant['prenom'],0,1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(55,8,'Module',1,0,'C');
$pdf->Cell(20,8,'Coef',1,0,'C');
$pdf->Cell(25,8,'CC',1,0,'C');
$pdf->Cell(25,8,'Exam',1,0,'C');
$pdf->Cell(30,8,'Moyenne',1,1,'C');

$pdf->SetFont('Arial','',11);

$total = 0;
$totalCoef = 0;

if ($notes) {
    foreach ($notes as $n) {
        $coef = $n['coefficient'] ?? 1;

        $pdf->Cell(55,8,$n['module'],1);
        $pdf->Cell(20,8,$coef,1,0,'C');
        $pdf->Cell(25,8,$n['note_cc'],1,0,'C');
        $pdf->Cell(25,8,$n['note_exam'],1,0,'C');
        $pdf->Cell(30,8,$n['moyenne_module'],1,1,'C');

        $total += $n['moyenne_module'] * $coef;
        $totalCoef += $coef;
    }

    $moyenneGenerale = $totalCoef ? $total / $totalCoef : 0;

    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(125,8,'Moyenne Generale',1);
    $pdf->Cell(30,8,$moyenneGenerale,1,1,'C');

} else {
    $pdf->Cell(155,8,'Aucune note disponible',1,1,'C');
}

$pdf->Output("I","releve_".$id_etudiant.".pdf");
