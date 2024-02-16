<?php

namespace App\Http\Controllers;

use App\Models\Editora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\EditoraRepository;

class EditoraController extends Controller
{
    public function __construct(Editora $editora)
    {
        $this->editora = $editora;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $editoraRepository = new EditoraRepository($this->editora);

        if($request->has('atributos_hqEditora')) {
            $atributos_hqEditora = 'hqEditora:id,'.$request->atributos_hqEditora;
            $editoraRepository->selectAtributosRegistrosRelacionados($atributos_hqEditora);
        } else {
            $editoraRepository->selectAtributosRegistrosRelacionados('hqEditora');
        }

        if($request->has('filtro')) {
            $editoraRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $editoraRepository->selectAtributos($request->atributos);
        } 

        return response()->json($editoraRepository->getResultadoPaginado(5), 200);
        // return response()->json($editoraRepository->getResultado(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->editora->rules(), $this->editora->feedback());

        $imagem = $request->file('logotipo');
        $imagem_urn = $imagem->store('imagens', 'public');

        $editora = $this->editora->create([
            'editora' => $request->editora,
            'logotipo' => $imagem_urn
        ]);

        return response()->json($editora, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $editora = $this->editora->with('hqEditora')->find($id);
        if($editora === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($editora, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $editora = $this->editora->find($id);

        if($editora === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($editora->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $editora->feedback());

        } else {
            $request->validate($editora->rules(), $editora->feedback());
        }
        
        //preenchendo o objeto $marca com todos os dados do request
        $editora->fill($request->all());

        //se a imagem foi encaminhada na requisição
        if($request->file('logotipo')) {
            //remove o arquivo antigo
            Storage::disk('public')->delete($editora->logotipo);

            $imagem = $request->file('logotipo');
            $imagem_urn = $imagem->store('imagens', 'public');
            $editora->logotipo = $imagem_urn;
        }

        $editora->save();
        return response()->json($editora, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $editora = $this->editora->find($id);

        if($editora === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo
        Storage::disk('public')->delete($editora->logotipo);        

        $editora->delete();
        return response()->json(['msg' => 'A editora foi removida com sucesso!'], 200);
    }
}
