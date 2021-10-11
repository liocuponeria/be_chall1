<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model 
{
    public $name;
    public $price;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price',
    ];

    public function set_name($name){
        $this->name = $name;
    }
    public function set_price($price){
        $this->price = $price;
    }

}
