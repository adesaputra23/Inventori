<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table        = 'roles';
    protected $primaryKey   = 'id_role';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;
}
