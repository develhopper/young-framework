<?php
namespace app\Http\Models;

use Young\Framework\Http\Model;

class Country extends Model{
    protected $table = "country";
    protected $primary = "Code";

    public function cities(){
        return $this->hasMany(City::class, true);
    }
}