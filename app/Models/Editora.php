<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editora extends Model
{
    use HasFactory;
    protected $fillable = ['editora','logotipo'];

    public function rules() {
        return [
            'editora' => 'required|min:3',
            'logotipo' => 'required|file|mimes:png,jpeg,jpg'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'editora.min' => 'A editora deve ter no mínimo 3 caracteres',
            'logotipo.mimes' => 'O arquivo deve ser uma imagem do tipo PNG ou JPG'
        ];
    }

    public function hqEditora() {
        return $this->hasMany('App\Models\Hq_Editora');
    }
}
