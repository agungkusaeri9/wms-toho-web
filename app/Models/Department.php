<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $guarded = ['id'];

    public static function booted()
    {
        static::created(function ($model) {
            $model->code = self::getNewCode();
        });
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
            $newNumber = $lastNumber + 1; // Createkan 1
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }
        // Format angka menjadi tiga digit, misalnya 001, 002
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
