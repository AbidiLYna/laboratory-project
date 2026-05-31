<?php
//============================================================+
// File name   : example_018.php
// Begin       : 2008-03-06
// Last Update : 2013-05-14
//
// Description : Example 018 for TCPDF class
//               RTL document with Persian language
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: RTL document with Persian language
 * @author Nicola Asuni
 * @since 2008-03-06
 */

// Include the main TCPDF library (search for installation path).
//require('tcpdf/tfpdf.php');

 /*try
{
	$conn = new PDO('mysql:host=127.0.0.1;dbname=MuscBase;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

 
 $result = $conn->query("SELECT * FROM user ORDER BY iduser DESC"); 

*/
require('tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('GYM 7/7 Annaba قاعة الرياضة ');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "GYM 7/7 Annaba", PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);

// ---------------------------------------------------------

// set font
//$pdf->SetFont('dejavusans', '', 12);
//$pdf->SetFont('aefurat','I', 18);
$pdf->SetFont('aefurat','B', 14);
// add a page
$pdf->AddPage();
$pdf->Rect(10,25,30,30,2.5,'',$border_style = array(0, 0, 0));
$pdf->Rect(10,60,190,10,'F','',$fill_color = array(255, 170, 96));
$pdf->Rect(10,60,190,10,2.5,'',$border_style = array(0, 0, 0));
$pdf->SetTextColor(0,0,0);
//$pdf->SetLineWidth(1);
$htmlpersian = '<span color="#660000">  النـــادي الريـــاضي الهاوي: </span> كريم.<br />
				<span color="#660000"> العنوان: </span> كريم.<br />
				<span color="#660000"> الفرع الريــاضي: </span> كريم.<span color="#660000"> الموسم الريــاضي: </span> 2024/2023 <&nbsp><br />
				<span color="#660000"> رقم الهاتف: </span> كريم. 
				<span color="#660000"> الإختصاص: </span> كريم.<br />';
$pdf->WriteHTML($htmlpersian, true, 0, true, 0);

$pdf->SetFont('aealarabiya','B', 24);

$pdf->SetTextColor(0,0,0);
$pdf->Cell(0, 12, 'طلب الإنخراط',0,1,'C');


$pdf->SetFont('aefurat','B', 14);
$pdf->SetTextColor(0,0,0);
$htmlpersian = ' يشرفنــي أن أتقدم إلى السيد رئيس النادي الرياضي الهاوي ومدير المنشأة الرياضية المتعددة الإختصاصات Annaba GYM 7/7   بهذا الطلب والمتمثل في الموضوع المذكور أعلاه .  <br />
				';
$pdf->WriteHTML($htmlpersian, true, 0, true, 0);

$pdf->Rect(10,85,190,50,2.5,'',$border_style = array(0, 0, 0));
$htmlpersian = '<span color="#660000">  الإسم : </span> كريم. <span color="#660000"> اللقب :  </span> كريم. <span color="#660000"> الجنس : </span> كريم.<br />
				<span color="#660000">  تاريخ الإزدياد : </span> كريم. <span color="#660000"> مكان الميلاد :   </span> كريم. <br />
				<span color="#660000"> العنوان: </span> كريم.<br />
				<span color="#660000">  المستوى الدراسي : </span> كريم. <span color="#660000"> المهنة :   </span> كريم. <br />
				<span color="#660000">  الفصيلة الدموية : </span> كريم. <span color="#660000"> القامة :  </span> كريم. <span color="#660000">الوزن : </span> كريم.<br />
				<span color="#660000"> تصريح بالأمراض والإعاقات الجسدية : </span> كريم.<br />
				<span color="#660000"> تاريخ الدخول :</span>  كريم.<br />';					
$pdf->WriteHTML($htmlpersian, true, 0, true, 0);

$pdf->Rect(120,140,80,10,'F','',$fill_color = array(119, 181, 254));
$pdf->SetFont('aealarabiya','B', 18);

$pdf->Rect(10,140,190,45,2.5,'',$border_style = array(0, 0, 0));

$pdf->SetTextColor(0,0,0);
$pdf->Cell(0, 12, 'شهادة طبية ( عامة + صدرية ) ',0,1,'ٌR');

$pdf->SetFont('aefurat','B', 14);
$pdf->SetTextColor(0,0,0);
$htmlpersian = '<span color="#660000"> حالة الصحة العامة : </span> .............................................................................<br />
				.........................................................................................................................................<br />
				<span color="#660000"> حالة الصحة الصدرية : </span> .............................................................................<br />
				.........................................................................................................................................<br />
				<span color="#660000"> درجة التأهيل لممارسة الرياضة : </span> ....<h4> كريم </h4>.....................................................................
				';					
$pdf->WriteHTML($htmlpersian, true, 0, true, 0);
//$pdf->Rect(10,150,50,40,2.5,'',$border_style = array(0, 0, 0));

$pdf->SetFont('aefurat','B', 18);
$pdf->SetTextColor(0,0,0);
$pdf->Rect(10,150,55,34,'F','',$fill_color = array(255, 255, 255));
//$htmlpersian = '<span color="#660000"> امـضاء الطبيب</span> <br />';					
//$pdf->WriteHTML($htmlpersian, true, 0, true, 0);

$pdf->SetXY(120, 140);
$pdf->Cell(0, 12, 'إمضاء الطبيب',0,1,'L');

$pdf->Rect(100,186,100,10,'F','',$fill_color = array(119, 181, 254));
$pdf->SetFont('aealarabiya','B', 18);


$pdf->Rect(10,186,190,40,2.5,'',$border_style = array(0, 0, 0));

$pdf->SetXY(15, 185);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0, 12, 'تصريــح أبــوي   ( للفئة الأقل من 18 سنة) ',0,1,'ٌR');

$pdf->SetFont('aefurat','B', 14);
$pdf->SetTextColor(0,0,0);
$htmlpersian = ' أنا الممضي (ة) أسفله السيد (ة) .........
				 المولود(ة)  في ......<br />
				 الحامل لـ  (ب ت و) / (ر س) تحت رقم ..........
				 الصادرة في .......
				 بـ.......<br />
				 أصرح لإبني (تي) بممارسة  رياضة.........
				 في النادي الرياضي الهاوي ............
				والمشاركة في مختلف التظاهرات والمنافسات الولائية والوطنية<br />
				';	
$pdf->WriteHTML($htmlpersian, true, 0, true, 0);

$pdf->SetFont('aefurat','B', 18);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(0, 12, 'إمضاء المعني             مصادقة البلدية          رئيس النادي          مدير المنشأة',0,1,'ٌC');



/*while($row = $result->fetch()){
//$htmlpersian = '<span color="#660000">Persian example:</span><br />'.$row['nomar'].'كريم.<br />';
}*/
//$pdf->WriteHTML($htmlpersian, true, 0, true, 0);
// print newline
$pdf->Ln();


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_018.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+