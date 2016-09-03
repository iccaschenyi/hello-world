<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table='config';
    protected  $primaryKey='config_id';
    public $timestamps=false;
    protected $guarded=[];
}
