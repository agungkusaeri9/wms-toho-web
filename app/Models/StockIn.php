<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;
    protected $table = 'stock_ins';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getNewCode()
    {
        $prefix = '';
        $lastCode = self::query()
            ->orderBy('code', 'desc')
            ->value('code');

        if ($lastCode) {
            // Ambil angka terakhir dari kode (misal SPL001 -> 001)
            $lastNumber = intval(substr($lastCode, strlen($prefix)));
            $newNumber = $lastNumber + 1; // Tambahkan 1
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }
        // Format angka menjadi tiga digit, misalnya 001, 002
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = \Str::uuid()->toString();
            $model->code = self::getNewCode();
        });
    }
}
