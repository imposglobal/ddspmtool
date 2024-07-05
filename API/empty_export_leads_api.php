<?php

    // Set headers for CSV export
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=sales_lead_generation.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen("php://output", "w");

    // Write headers to CSV
    fputcsv($output, array('Client Name', 'Business Name', 'Industry', 'Email ID', 'Contact Number', 'Services Looking', 'Channel', 'Status', 'Notes'));

    // Close output stream
    fclose($output);

    // Exit script
    exit;
?>
