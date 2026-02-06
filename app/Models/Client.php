<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Invoice;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'nama_brand',
    'phone',
    'email',
    'address',
    'notes',
    'category',
    'status',
    'start_date',
    'expired_date',
];


    protected $casts = [
        'start_date'   => 'date',
        'expired_date' => 'date',
    ];

    /* =========================
     |  KATEGORI
     ========================= */
    const CATEGORY_BASIC    = 'Basic';
    const CATEGORY_GROWTH   = 'Growth';
    const CATEGORY_BUSINESS = 'Business';
    const CATEGORY_PREMIUM  = 'Premium';

    public static function getCategories()
    {
        return [
            self::CATEGORY_BASIC,
            self::CATEGORY_GROWTH,
            self::CATEGORY_BUSINESS,
            self::CATEGORY_PREMIUM,
        ];
    }

    /* =========================
     |  STATUS
     ========================= */
    const STATUS_ACTIVE       = 'aktif';
    const STATUS_INACTIVE     = 'non aktif';
    const STATUS_NOT_STARTED  = 'belum aktif';

    /**
     * STATUS OTOMATIS + MANUAL OVERRIDE
     */
    public function getAutoStatusAttribute()
    {
        // 🔥 MANUAL OVERRIDE
        if (in_array($this->status, [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
        ])) {
            return $this->status;
        }

        $today = now()->startOfDay();

        if ($today->lt($this->start_date)) {
            return self::STATUS_NOT_STARTED;
        }

        if ($today->gt($this->expired_date)) {
            return self::STATUS_INACTIVE;
        }

        return self::STATUS_ACTIVE;
    }

    public function invoices()
{
    return $this->hasMany(Invoice::class);
}


}
