<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cart";

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
    


}
