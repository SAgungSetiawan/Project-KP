<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
    'client_id',
    'invoice_number',
    'file_original_name',  // Hanya ini untuk nama file
    'file_mime_type',
    'file_size',
    'file_content',
    'invoice_date',
    'amount',
];

    protected $casts = [
        'invoice_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get file as base64 encoded string
     */
    public function getFileBase64Attribute()
    {
        if ($this->file_content) {
            return base64_encode($this->file_content);
        }
        return null;
    }

    /**
     * Get file data URL for embedding
     */
    public function getFileDataUrlAttribute()
    {
        if ($this->file_content && $this->file_mime_type) {
            return 'data:' . $this->file_mime_type . ';base64,' . base64_encode($this->file_content);
        }
        return null;
    }
}