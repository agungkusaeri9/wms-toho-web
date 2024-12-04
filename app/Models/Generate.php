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
}
