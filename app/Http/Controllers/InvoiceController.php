<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan invoice file
     */
 public function show($id)
{
    $invoice = Invoice::findOrFail($id);
    
    if (!$invoice->file_content) {
        abort(404, 'File not found');
    }

    return response($invoice->file_content)
        ->header('Content-Type', $invoice->file_mime_type)
        ->header('Content-Disposition', 'inline; filename="' . $invoice->file_original_name . '"');
}

public function download($id)
{
    $invoice = Invoice::findOrFail($id);
    
    if (!$invoice->file_content) {
        abort(404, 'File not found');
    }

    return response($invoice->file_content)
        ->header('Content-Type', $invoice->file_mime_type)
        ->header('Content-Disposition', 'attachment; filename="' . $invoice->file_original_name . '"');
}
}