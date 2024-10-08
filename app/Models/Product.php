<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
class Product extends Model
{
    use HasFactory;

    // Define fillable properties if necessary
    protected $fillable = ['name', 'category', 'image', 'price'];
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

}
