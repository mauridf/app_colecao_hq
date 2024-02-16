<?php

namespace App\Http\Controllers;

use App\Models\Hq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\HqRepository;

class HqController extends Controller
{
    public function __construct(Hq $hq)
    {
        $this->hq = $hq;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hqRepository = new HqRepository($this->hq);

        if($request->has('atributos_hqEditora')) {
            $atributos_hqEditora = 'hqEditora:id,'.$request->atributos_hqEditora;
            $hqRepository->selectAtributosRegistrosRelacionados($atributos_hqEditora);
        } 
        if($request->has('atributos_hqPersonagem')) {
            $atributos_hqPersonagem = 'hqPersonagem:id,'.$request->atributos_hqPersonagem;
            $hqRepository->selectAtributosRegistrosRelacionados($atributos_hqPersonagem);
        } else {
            $hqRepository->selectDoisAtributosRegistrosRelacionados('hqEditora','hqPersonagem');
        }

        if($request->has('filtro')) {
            $hqRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $hqRepository->selectAtributos($request->atributos);
        } 

        return response()->json($hqRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->hq->rules(), $this->hq->feedback());

        $imagem = $request->file('capa');
        $imagem_urn = $imagem->store('imagens', 'public');

        $hq = $this->hq->create([
            'nome' => $request->nome,
            'nome_original' => $request->nome_original,
            'tipo_serie_id' => $request->tipo_serie_id,
            'ano_lancamento' => $request->ano_lancamento,
            'total_edicoes' => $request->total_edicoes,
            'idioma' => $request->idioma,
            'sinopse' => $request->sinopse,
            'status_id' => $request->status_id,
            'observacoes' => $request->observacoes,
            'capa' => $imagem_urn
        ]);

        return response()->json($hq, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hq  $hq
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hq = $this->hq->with('atributos_hqEditora','atributos_hqPersonagem')->find($id);
        if($hq === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($hq, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hq  $hq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hq = $this->hq->find($id);

        if($hq === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($hq->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $hq->feedback());

        } else {
            $request->validate($hq->rules(), $hq->feedback());
        }
        
        //preenchendo o objeto $marca com todos os dados do request
        $hq->fill($request->all());

        //se a imagem foi encaminhada na requisição
        if($request->file('capa')) {
            //remove o arquivo antigo
            Storage::disk('public')->delete($hq->logotipo);

            $imagem = $request->file('capa');
            $imagem_urn = $imagem->store('imagens', 'public');
            $hq->imagem = $imagem_urn;
        }

        $hq->save();
        return response()->json($hq, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hq  $hq
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hq = $this->hq->find($id);

        if($hq === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo
        Storage::disk('public')->delete($hq->capa);        

        $hq->delete();
        return response()->json(['msg' => 'A hq foi removida com sucesso!'], 200);
    }
}
