<?php
namespace app\Http\Models;

use Young\Framework\Http\Model;

class City extends Model{
    protected $table = "city";
    protected $primary = "ID";
    protected $related_tables = ["country" => "CountryCode"];
}