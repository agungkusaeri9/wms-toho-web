<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'areas';
    protected $guarded = ['id'];

    public static function getTypes()
    {
        $arr = [
            'Storage',
            'Receiving',
            'Shipping',
            'Packing'
        ];
        return $arr;
    }

    public function racks()
    {
        return $this->hasMany(Rack::class, 'area_id', 'id');
    }
}
