<?php
namespace app\Http\Models;

use Young\Framework\Http\Model;

class Country extends Model{
    protected $table = "country";
    protected $primary = "Code";
    protected $hidden = ["Code2"];

    public function cities(){
        return $this->hasMany(City::class, true);
    }
}