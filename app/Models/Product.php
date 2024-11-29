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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
