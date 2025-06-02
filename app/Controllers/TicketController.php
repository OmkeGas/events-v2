<?php

namespace App\Controllers;

use Core\Controller;
use Core\Middleware;

class TicketController extends Controller
{
    public function __construct()
    {
        Middleware::isAuth(); // Ensure user is logged in
    }

    public function download($id)
    {
        // Validate ID
        if (!$id || !is_numeric($id)) {
            $this->redirect('/dashboard/event');
            return;
        }

        // Get registration data
        $registrationModel = $this->model('Registration');
        $registration = $registrationModel->getById($id);

        // Check if registration exists and belongs to current user
        if (!$registration || $registration['id_user'] != $_SESSION['user']['id']) {
            $this->redirect('/dashboard/event');
            return;
        }

        // Generate PDF ticket
        $this->generateTicketPDF($registration);
    }

    private function generateTicketPDF($registration)
    {
        // Set headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ticket_' . $registration['registration_code'] . '.pdf"');

        // Get QR code path for inclusion in PDF
        $qrCodePath = '';
        if (!empty($registration['qr_code'])) {
            $qrCodePath = $_SERVER['DOCUMENT_ROOT'] . '/images/qrcodes/' . $registration['qr_code'];
        }

        // Use FPDF library
        require_once __DIR__ . '/../../app/Config/fpdf/fpdf.php';

        // Create new PDF document
        $pdf = new \FPDF('P', 'mm', 'A4');

        // Add a page
        $pdf->AddPage();

        // Set document properties
        $pdf->SetTitle('Event Ticket - ' . $registration['title']);
        $pdf->SetAuthor('DIPens');
        $pdf->SetCreator('Event Management System');

        // Set font
        $pdf->SetFont('Arial', '', 12);

        // Top blue bar (like in modal)
        $pdf->SetFillColor(59, 130, 246); // Blue color (#3b82f6)
        $pdf->Rect(10, 10, 190, 8, 'F');

        // Header section with event details - use light gray like modal
        $pdf->SetFillColor(249, 250, 251); // Light gray background (#f9fafb)
        $pdf->Rect(10, 20, 190, 35, 'F');
        $pdf->Line(10, 55, 200, 55); // Bottom border

        // Event Title - make it bold like in modal but wrap the text
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(17, 24, 39); // Dark text color (#111827)
        $pdf->SetXY(15, 22);

        // Handle long titles with MultiCell instead of Cell
        $title = utf8_decode($registration['title']);
        $pdf->MultiCell(180, 8, $title, 0, 'L');

        // Get the Y position after the title to position the location and date correctly
        $currentY = $pdf->GetY();

        // Event Location and Address - Fix the location address bug
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(75, 85, 99); // Gray text color (#4b5563)
        $pdf->SetXY(15, $currentY + 1); // Position right after the title

        // Combine location and address with a hyphen if address exists
        $locationText = $registration['location_name'];
        if (!empty($registration['location_address'])) {
            $locationText .= ' - ' . $registration['location_address'];
        }
        $pdf->MultiCell(180, 5, utf8_decode($locationText), 0, 'L');

        // Update current Y position
        $currentY = $pdf->GetY();

        // Event Date and Time
        $pdf->SetXY(15, $currentY + 1);
        $dateTimeText = strtoupper(date('M d, Y', strtotime($registration['start_date']))) . ', ' .
                       date('g:i A', strtotime($registration['start_time'])) . ' (WIB)';
        $pdf->Cell(180, 6, $dateTimeText, 0, 1);

        // Reposition the bottom border if the content exceeds the default height
        $newBottomY = max(55, $pdf->GetY() + 10);
        $pdf->Line(10, $newBottomY, 200, $newBottomY);

        // QR Code section - similar to modal
        if (!empty($registration['qr_code']) && file_exists($qrCodePath)) {
            $pdf->Image($qrCodePath, 25, $newBottomY + 10, 40, 40);
        } else {
            // Draw a white rectangle with border for the QR code placeholder
            $pdf->SetDrawColor(229, 231, 235); // Border color (#e5e7eb)
            $pdf->SetFillColor(255, 255, 255); // White background
            $pdf->Rect(25, $newBottomY + 10, 40, 40, 'DF');

            // Now draw a simple QR code placeholder similar to the SVG in the modal
            $pdf->SetDrawColor(156, 163, 175); // Gray (#9ca3af)
            $centerX = 25 + 20; // Center of the QR placeholder
            $centerY = $newBottomY + 10 + 20;
            $size = 15; // Size of the placeholder icon

            // Draw QR code placeholder lines to mimic the SVG
            $pdf->SetLineWidth(0.5);

            // Center dot
            $pdf->Line($centerX, $centerY - 5, $centerX, $centerY - 5 + 0.1);

            // Vertical lines
            $pdf->Line($centerX, $centerY - 8, $centerX, $centerY - 6); // Top
            $pdf->Line($centerX, $centerY + 6, $centerX, $centerY + 8); // Bottom

            // Horizontal lines
            $pdf->Line($centerX - 8, $centerY, $centerX - 6, $centerY); // Left
            $pdf->Line($centerX + 6, $centerY, $centerX + 8, $centerY); // Right

            // Top left square
            $pdf->Rect($centerX - 8, $centerY - 8, 4, 4);

            // Top right square
            $pdf->Rect($centerX + 4, $centerY - 8, 4, 4);

            // Bottom left square
            $pdf->Rect($centerX - 8, $centerY + 4, 4, 4);
        }

        // Draw vertical divider line between QR and details - just like modal
        $pdf->SetDrawColor(229, 231, 235);
        $pdf->Line(80, $newBottomY + 10, 80, $newBottomY + 55);

        // ISSUED TO section - match modal formatting
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128); // Gray text (#6b7280)
        $pdf->SetXY(90, $newBottomY + 10);
        $pdf->Cell(30, 6, 'ISSUED TO', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetXY(90, $newBottomY + 16);
        $pdf->Cell(100, 6, utf8_decode($_SESSION['user']['full_name']), 0, 1);

        // ORDER NUMBER and REGISTERED in two columns - like modal
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->SetXY(90, $newBottomY + 28);
        $pdf->Cell(50, 6, 'ORDER NUMBER', 0);

        $pdf->SetXY(145, $newBottomY + 28);
        $pdf->Cell(50, 6, 'REGISTERED', 0);

        // Use monospace font for codes like in modal
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetXY(90, $newBottomY + 34);
        $pdf->Cell(50, 6, $registration['registration_code'], 0);

        $pdf->SetXY(145, $newBottomY + 34);
        $pdf->Cell(50, 6, strtoupper(date('M d, Y', strtotime($registration['registration_date']))), 0);

        // TICKET section - match modal
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->SetXY(90, $newBottomY + 46);
        $pdf->Cell(30, 6, 'TICKET', 0);

        $pdf->SetFont('Courier', 'B', 10);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetXY(90, $newBottomY + 52);
        $ticketNumber = !empty($registration['ticket_number']) ?
            $registration['ticket_number'] :
            'RSVP-' . substr($registration['registration_code'], -8);
        $pdf->Cell(100, 6, $ticketNumber, 0);

        // Footer section - match modal style
        $footerY = $newBottomY + 70;
        $pdf->SetFillColor(249, 250, 251);
        $pdf->Rect(10, $footerY, 190, 20, 'F');
        $pdf->Line(10, $footerY, 200, $footerY); // Top border

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->SetXY(15, $footerY + 5);
        $pdf->Cell(180, 5, 'Event organized by DIPens', 0, 1, 'C');

        $pdf->SetXY(15, $footerY + 10);
        $pdf->Cell(180, 5, 'Please present this ticket at the event entrance. This serves as your official entry pass.', 0, 1, 'C');

        // Output the PDF for download
        $pdf->Output('D', 'ticket_' . $registration['registration_code'] . '.pdf');
        exit();
    }
}
