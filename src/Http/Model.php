<?php
namespace Young\Framework\Http;

use JsonSerializable;
use QB\QBuilder;

class Model extends Qbuilder implements JsonSerializable{
    protected $hidden = [];
    
    public function jsonSerialize(): mixed
    {
        $fields = $this->fields;
        foreach($this->hidden as $item){
            unset($fields[$item]);
        }
        return $fields;
    }

}