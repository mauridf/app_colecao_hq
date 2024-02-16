<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    protected $fillable = ['status'];

    public function rules() {
        return [
            'status' => 'required|min:3'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'status.min' => 'O Status deve ter no mínimo 3 caracteres'
        ];
    }

    public function hq() {
        //UMA marca POSSUI MUITOS modelos
        return $this->hasMany('App\Models\Hq');
    }
}
