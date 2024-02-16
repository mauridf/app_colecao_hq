<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hq_Editora extends Model
{
    use HasFactory;
    protected $table = 'hq_editoras';
    protected $fillable = ['hq_id','editora_id'];

    public function hq() {
        return $this->belongsTo('App\Models\Hq');
    }

    public function editora() {
        return $this->belongsTo('App\Models\Editora');
    }
}
