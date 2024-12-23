<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';

    // The primary key for the model
    protected $primaryKey = 'id';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category'
    ];

    // The attributes that should be hidden for arrays
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Enable UUID as the primary key
    public $incrementing = false;
    protected $keyType = 'string';
    protected static function booted()
    {
        static::creating(function ($product) {
            if (!$product->id) {
                $product->id = (string) Str::uuid(); // Automatically generate a UUID
            }
        });
    }

    // The attributes that should be cast to native types
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
