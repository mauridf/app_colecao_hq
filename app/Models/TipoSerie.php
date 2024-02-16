<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSerie extends Model
{
    use HasFactory;
    protected $table = 'tipo_series';
    protected $fillable = ['tiposerie'];

    public function rules() {
        return [
            'tiposerie' => 'required|min:3'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'tiposerie.min' => 'O Tipo de Série deve ter no mínimo 3 caracteres'
        ];
    }

    public function hq() {
        //UM tipoSerie POSSUI MUITAS HQs
        return $this->hasMany('App\Models\Hq');
    }
}
