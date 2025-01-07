<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generate extends Model
{
    use HasFactory;
    protected $table = 'generates';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public static function booted()
    {
        static::creating(function ($model) {
            $model->code = substr(\Str::uuid()->toString(), 0, 8);
        });
    }

    public function stock_in()
    {
        return $this->hasMany(StockIn::class, 'generate_id');
    }
    public function stock_out()
    {
        return $this->hasMany(StockOut::class, 'generate_id');
    }

    public function remains()
    {
        $stock_in = $this->stock_in()->sum('qty');
        $stock_out = $this->stock_out()->sum('qty');
        $remains = $stock_in - $stock_out;
        return $remains;
    }
}
