<?php

namespace App\Models;

use App\Helper\Traits\HasDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    public $guarded = [];
    protected $perPage = 5;
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

}
