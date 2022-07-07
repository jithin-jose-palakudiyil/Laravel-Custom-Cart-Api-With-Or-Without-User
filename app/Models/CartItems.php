<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cart_items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [];
    
    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    
    protected $guarded = [ ]; 
    

    /**
     * Get the user associated with the coin.
     */
    public function Product()
    {
        return $this->hasOne(Products::class,'id','product_id');
    }

}
