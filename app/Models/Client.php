<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nama_brand',
        'phone',
        'email',
        'address',
        'category',
        'join_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    // Konstanta untuk kategori
    const CATEGORY_BASIC = 'Basic';
    const CATEGORY_GROWTH = 'Growth';
    const CATEGORY_BUSINESS = 'Business';
    const CATEGORY_PREMIUM = 'Premium';

    public static function getCategories()
    {
        return [
            self::CATEGORY_BASIC,
            self::CATEGORY_GROWTH,
            self::CATEGORY_BUSINESS,
            self::CATEGORY_PREMIUM,
        ];
    }

    // Konstanta untuk status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }
}