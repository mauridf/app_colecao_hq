<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hq_Personagem extends Model
{
    use HasFactory;
    protected $table = 'hq_personagens';
    protected $fillable = ['hq_id','personagem_id'];

    public function hq() {
        return $this->belongsTo('App\Models\Hq');
    }

    public function personagem() {
        return $this->belongsTo('App\Models\Personagem');
    }
}
