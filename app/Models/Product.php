<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = ['id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function part_number()
    {
        return $this->belongsTo(PartNumber::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function image()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        } else {
            return asset('assets/images/image-placeholder.png');
        }
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stock_in()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stock_out()
    {
        return $this->hasMany(StockOut::class);
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
        static::updating(function ($model) {
            $model->uuid = \Str::uuid()->toString();
            $model->code = self::getNewCode();
        });
    }

    public function dataQr($qty)
    {
        $qr = $this->code . '-' . $this->part_number->name . '-' . $this->name . '-' . $this->lot_number . '-' . $qty;
        return $qr;
    }



    public function remains()
    {
        $stock_out = StockOut::where('product_id', $this->id)->sum('qty');
        $stock_in = StockIn::where('product_id', $this->id)->sum('qty');
        $remains = $stock_in - $stock_out;
        return $remains;
    }
}
