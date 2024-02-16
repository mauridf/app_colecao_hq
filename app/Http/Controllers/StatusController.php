<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Repositories\StatusRepository;

class StatusController extends Controller
{
    public function __construct(Status $status)
    {
        $this->status = $status;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $statusRepository = new StatusRepository($this->status);

        if($request->has('atributos_hq')) {
            $atributos_hq = 'atributos_hq:id,'.$request->atributos_hq;
            $statusRepository->selectAtributosRegistrosRelacionados($atributos_hq);
        } else {
            $statusRepository->selectAtributosRegistrosRelacionados('hq');
        }

        if($request->has('filtro')) {
            $statusRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $statusRepository->selectAtributos($request->atributos);
        } 

        return response()->json($statusRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->status->rules(), $this->status->feedback());

        $status = $this->status->create([
            'status' => $request->status
        ]);

        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = $this->status->with('hq')->find($id);
        if($status === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($status, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = $this->status->find($id);

        if($status === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($status->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $status->feedback());

        } else {
            $request->validate($status->rules(), $status->feedback());
        }
        
        //preenchendo o objeto $marca com todos os dados do request
        $status->fill($request->all());

        $status->save();
        return response()->json($status, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = $this->status->find($id);

        if($status === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $status->delete();
        return response()->json(['msg' => 'O status foi removido com sucesso!'], 200);
    }
}
