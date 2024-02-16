<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personagem extends Model
{
    use HasFactory;
    protected $table = 'personagens';
    protected $fillable = ['personagem','descricao','imagem'];

    public function rules() {
        return [
            'personagem' => 'required|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'personagem.min' => 'O Personagem deve ter no mínimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG ou JPG'
        ];
    }

    public function hqPersonagem() {
        return $this->hasMany('App\Models\Hq_Personagem');
    }
}
