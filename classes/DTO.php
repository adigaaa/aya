<?php

namespace App;

class DTO
{
    protected  $result;

    private function __construct($result)
    {
        $this->result = $result;
    }

    public function fetchAllArray()
    {
        if ($this->isEmpty()){
            return [];
        }
        while ($row = mysqli_fetch_assoc($this->result)){
            $result[] = array_map(function ($value){
                return $this->xssProtection($value);
            }, $row);
        }
        return $result;
    }

    protected function xssProtection($value)
    {
        return htmlentities($value, ENT_QUOTES,'UTF-8');
    }
    public function fetchFirstArray()
    {
        return mysqli_fetch_assoc($this->result);
    }

    public function isEmpty()
    {
        return mysqli_num_rows($this->result) === 0;
    }

    public static function make($result)
    {
        return new static($result);
    }
}