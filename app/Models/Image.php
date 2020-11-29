<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image_path'
    ];

    public function getAll()
    {
        return $this->select('*')->get();
    }

    public function scopeById($query, $id)
    {
        if(is_array($id)) {
            return $query->whereIn('id', $id);
        }
        return $query->where('id', $id);
    }

}
