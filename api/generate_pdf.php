<?php
// Include FPDF library
require('./fpdf/fpdf.php');
include('../config/database.php');
// Generate Combined Report
function generateCombinedReport($pdo)
{
    // Set headers for inline PDF display
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="library_report.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
// Get the selected filter from the URL query string
$selectedFilter = $_GET['selectedFilter'] ?? null;

 // Initialize FPDF
 $pdf = new FPDF();
 $pdf->SetFont('Arial', 'B', 16);

 // Books Report
 $pdf->AddPage('L');
 $pdf->Cell(0, 10, 'Library Book Report', 0, 1, 'C');
 $pdf->Ln(10);

if ($selectedFilter === 'Available') {
    // Fetch books from the database
    $bookSql = "SELECT book_id, book_title, book_author, book_publisher, book_status, book_borrowed_date, book_returned_date FROM tbl_book WHERE book_status = 'Available'";
    $bookStmt = $pdo->prepare($bookSql);
    $bookStmt->execute();
    $books = $bookStmt->fetchAll(PDO::FETCH_ASSOC);


    // Table Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, 'ID', 1);
    $pdf->Cell(60, 10, 'Book Title', 1);
    $pdf->Cell(50, 10, 'Book Author', 1);
    $pdf->Cell(55, 10, 'Book Publisher', 1);
    $pdf->Cell(20, 10, 'Status', 1);
    $pdf->Cell(41, 10, 'Borrowed Date', 1);
    $pdf->Cell(41, 10, 'Returned Date', 1);
    $pdf->Ln();

    // Table Content
    $pdf->SetFont('Arial', '', 12);
    foreach ($books as $book) {
        $pdf->Cell(10, 10, $book['book_id'], 1);
        $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_title']), 1);
        $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_author']), 1);
        $pdf->Cell(55, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_publisher']), 1);
        $pdf->Cell(20, 10, $book['book_status'], 1);
        $pdf->Cell(41, 10, $book['book_borrowed_date'] ?? '-', 1);
        $pdf->Cell(41, 10, $book['book_returned_date'] ?? '-', 1);
        $pdf->Ln();
    }
    // Output PDF
$pdf->Output('I', 'library_report.pdf');
    exit;
} else if ($selectedFilter === 'Borrowed') {
     // Fetch books from the database
     $bookSql = "SELECT book_id, book_title, book_author, book_publisher, book_status, book_borrowed_date, book_returned_date FROM tbl_book WHERE book_status = 'Borrowed'";
     $bookStmt = $pdo->prepare($bookSql);
     $bookStmt->execute();
     $books = $bookStmt->fetchAll(PDO::FETCH_ASSOC);
 
 
     // Table Header
     $pdf->SetFont('Arial', 'B', 12);
     $pdf->Cell(10, 10, 'ID', 1);
     $pdf->Cell(60, 10, 'Book Title', 1);
     $pdf->Cell(50, 10, 'Book Author', 1);
     $pdf->Cell(55, 10, 'Book Publisher', 1);
     $pdf->Cell(20, 10, 'Status', 1);
     $pdf->Cell(41, 10, 'Borrowed Date', 1);
     $pdf->Cell(41, 10, 'Returned Date', 1);
     $pdf->Ln();
 
     // Table Content
     $pdf->SetFont('Arial', '', 12);
     foreach ($books as $book) {
         $pdf->Cell(10, 10, $book['book_id'], 1);
         $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_title']), 1);
         $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_author']), 1);
         $pdf->Cell(55, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_publisher']), 1);
         $pdf->Cell(20, 10, $book['book_status'], 1);
         $pdf->Cell(41, 10, $book['book_borrowed_date'] ?? '-', 1);
         $pdf->Cell(41, 10, $book['book_returned_date'] ?? '-', 1);
         $pdf->Ln();
     }
     // Output PDF
$pdf->Output('I', 'library_report.pdf');
     exit;
} 
else {
    // Fetch books from the database
    $bookSql = "SELECT book_id, book_title, book_author, book_publisher, book_status, book_borrowed_date, book_returned_date FROM tbl_book";
    $bookStmt = $pdo->prepare($bookSql);
    $bookStmt->execute();
    $books = $bookStmt->fetchAll(PDO::FETCH_ASSOC);


    // Table Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, 'ID', 1);
    $pdf->Cell(60, 10, 'Book Title', 1);
    $pdf->Cell(50, 10, 'Book Author', 1);
    $pdf->Cell(55, 10, 'Book Publisher', 1);
    $pdf->Cell(20, 10, 'Status', 1);
    $pdf->Cell(41, 10, 'Borrowed Date', 1);
    $pdf->Cell(41, 10, 'Returned Date', 1);
    $pdf->Ln();

    // Table Content
    $pdf->SetFont('Arial', '', 12);
    foreach ($books as $book) {
        $pdf->Cell(10, 10, $book['book_id'], 1);
        $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_title']), 1);
        $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_author']), 1);
        $pdf->Cell(55, 10, iconv('UTF-8', 'ISO-8859-1', $book['book_publisher']), 1);
        $pdf->Cell(20, 10, $book['book_status'], 1);
        $pdf->Cell(41, 10, $book['book_borrowed_date'] ?? '-', 1);
        $pdf->Cell(41, 10, $book['book_returned_date'] ?? '-', 1);
        $pdf->Ln();
    }
    // Output PDF
$pdf->Output('I', 'library_report.pdf');
    exit;
}
   

    
}
 // 'I' for inline display


// Call the function
generateCombinedReport($pdo);
