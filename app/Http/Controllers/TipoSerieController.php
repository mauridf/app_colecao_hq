<?php

namespace App\Http\Controllers;

use App\Models\TipoSerie;
use Illuminate\Http\Request;
use App\Repositories\TipoSerieRepository;

class TipoSerieController extends Controller
{
    public function __construct(TipoSerie $tiposerie)
    {
        $this->tiposerie = $tiposerie;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $TipoSerieRepository = new TipoSerieRepository($this->tiposerie);

        if($request->has('atributos_hq')) {
            $atributos_hq = 'atributos_hq:id,'.$request->atributos_hq;
            $TipoSerieRepository->selectAtributosRegistrosRelacionados($atributos_hq);
        } else {
            $TipoSerieRepository->selectAtributosRegistrosRelacionados('hq');
        }

        if($request->has('filtro')) {
            $TipoSerieRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $TipoSerieRepository->selectAtributos($request->atributos);
        } 

        return response()->json($TipoSerieRepository->getResultadoPaginado(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->tiposerie->rules(), $this->tiposerie->feedback());

        $tiposerie = $this->tiposerie->create([
            'tiposerie' => $request->tiposerie
        ]);

        return response()->json($tiposerie, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoSerie  $tiposerie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tiposerie = $this->tiposerie->with('hq')->find($id);
        if($tiposerie === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($tiposerie, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipo_Serie  $tiposerie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tiposerie = $this->tiposerie->find($id);

        if($tiposerie === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($tiposerie->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $tiposerie->feedback());

        } else {
            $request->validate($tiposerie->rules(), $tiposerie->feedback());
        }
        
        //preenchendo o objeto $marca com todos os dados do request
        $tiposerie->fill($request->all());

        $tiposerie->save();
        return response()->json($tiposerie, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipo_Serie  $tiposerie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tiposerie = $this->tiposerie->find($id);

        if($tiposerie === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $tiposerie->delete();
        return response()->json(['msg' => 'O tipo série foi removido com sucesso!'], 200);
    }
}
