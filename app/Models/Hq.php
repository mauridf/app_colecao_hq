<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hq extends Model
{
    use HasFactory;
    protected $fillable = ['nome','nome_original','tipo_serie_id','ano_lancamento','total_edicoes','idioma','sinopse','status_id','observacao','capa'];

    public function rules() {
        return [
            'nome' => 'required|min:3',
            'capa' => 'required|file|mimes:png,jpeg,jpg'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'capa.mimes' => 'O arquivo deve ser uma imagem do tipo PNG ou JPG'
        ];
    }

    public function hqEditora() {
        return $this->hasMany('App\Models\Hq_Editora');
    }

    public function hqPersonagem() {
        return $this->hasMany('App\Models\Hq_Personagem');
    }

    public function tipoSerie() {
        return $this->belongsTo('App\Models\TipoSerie');
    }

    public function status() {
        return $this->belongsTo('App\Models\Status');
    }
}
