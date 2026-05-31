<?php
session_start();
include 'connection.php';

// جيب id من الرابط
$id = $_GET["id"];

// جيب المعلومات من قاعدة البيانات
$select = "SELECT * FROM scientifiques WHERE id = '$id' LIMIT 1";
$result = mysqli_query($link, $select) or die(mysqli_error($link));

if (mysqli_num_rows($result) == 0) {
    die("Scientifique introuvable.");
}

$row = mysqli_fetch_assoc($result);

$nom        = $row['nom'];
$type       = $row['type'];
$university = $row['university'];
$faculte    = $row['faculte'];
$email      = $row['email'];
$telephone  = $row['telephone'];

// =====================
// TCPDF - نفس طريقة البروف
// =====================
require('PDF/tcpdf/tcpdf.php');

// إنشاء PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// معلومات الوثيقة
$pdf->SetCreator('Laboratoire');
$pdf->SetAuthor('Laboratoire');
$pdf->SetTitle('Fiche Scientifique - ' . $nom);

// إخفاء الهيدر والفوتر الافتراضيين
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// الهوامش
$pdf->SetMargins(15, 15, 15);

// فاصل صفحة تلقائي
$pdf->SetAutoPageBreak(TRUE, 15);

// الخط - aefurat يدعم العربية مثل مثال البروف
$pdf->SetFont('aefurat', 'B', 12);

// إضافة صفحة
$pdf->AddPage();

// =====================
// رسم الإطار الخارجي
// =====================
$pdf->SetDrawColor(16, 185, 129);   // لون أخضر
$pdf->SetLineWidth(1);
$pdf->Rect(10, 10, 190, 277, 'D');

// =====================
// العنوان الرئيسي
// =====================
$pdf->SetFillColor(16, 185, 129);
$pdf->Rect(10, 10, 190, 18, 'F');

$pdf->SetFont('aefurat', 'B', 20);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(10, 12);
$pdf->Cell(190, 12, 'Laboratoire', 0, 1, 'C');

// =====================
// عنوان الوثيقة
// =====================
$pdf->SetFont('aefurat', 'B', 16);
$pdf->SetTextColor(30, 41, 59);
$pdf->SetXY(10, 35);
$pdf->Cell(190, 12, 'Fiche du Scientifique', 0, 1, 'C');

// =====================
// جدول المعلومات
// =====================
$pdf->SetFont('aefurat', 'B', 13);

$startY = 55;
$pdf->SetXY(15, $startY);

// بيانات الجدول
$data = [
    ['ID',           $id],
    ['Nom complet',  $nom],
    ['Type',         $type],
    ['Université',   $university],
    ['Faculté',      $faculte],
    ['Email',        $email],
    ['Téléphone',    $telephone],
];

foreach ($data as $i => $ligne) {
    $y = $startY + ($i * 16);

    // خلفية الخلية اليسرى (الحقل)
    if ($i % 2 == 0) {
        $pdf->SetFillColor(236, 253, 245);  // أخضر فاتح جداً
    } else {
        $pdf->SetFillColor(249, 250, 251);  // رمادي فاتح
    }

    // خلية الحقل
    $pdf->SetXY(15, $y);
    $pdf->SetTextColor(5, 150, 105);
    $pdf->SetFont('aefurat', 'B', 12);
    $pdf->Cell(60, 14, $ligne[0], 1, 0, 'C', true);

    // خلية القيمة
    $pdf->SetTextColor(17, 24, 39);
    $pdf->SetFont('aefurat', '', 12);
    $pdf->Cell(125, 14, $ligne[1] ? $ligne[1] : '-', 1, 1, 'L', true);
}

// =====================
// التوقيع والتاريخ
// =====================
$lastY = $startY + (count($data) * 16) + 15;

$pdf->SetXY(15, $lastY);
$pdf->SetFont('aefurat', 'I', 10);
$pdf->SetTextColor(156, 163, 175);
$pdf->Cell(190, 8, 'Généré automatiquement par le système Laboratoire - ' . date('d/m/Y'), 0, 1, 'C');

// =====================
// إخراج الـ PDF مباشرة في المتصفح
// =====================
ob_end_clean();
$pdf->Output('scientifique_' . $id . '.pdf', 'I');
?>
