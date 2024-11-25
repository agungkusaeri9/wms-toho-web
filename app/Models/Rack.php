<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;
    protected $table = 'racks';
    protected $guarded = ['id'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public static function getSatus()
    {
        $arr = [
            'Available',
            'Full',
            'Under Maintenance'
        ];
        return $arr;
    }

    public function status()
    {
        if ($this->status === 'Available') {
            return '<span class="badge badge-success">Available</span>';
        } elseif ($this->status === 'Full') {
            return '<span class="badge badge-danger">Full</span>';
        } else {
            return '<span class="badge badge-warning">Under Maintenance</span>';
        }
    }
}
