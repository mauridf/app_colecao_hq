<?php

namespace App\Http\Controllers;

use App\Models\Personagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\PersonagemRepository;

class PersonagemController extends Controller
{
    public function __construct(Personagem $personagem)
    {
        $this->personagem = $personagem;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $personagemRepository = new PersonagemRepository($this->personagem);

        if($request->has('atributos_hqPersonagem')) {
            $atributos_hqPersonagem = 'hqPersonagem:id,'.$request->atributos_hqPersonagem;
            $personagemRepository->selectAtributosRegistrosRelacionados($atributos_hqPersonagem);
        } else {
            $personagemRepository->selectAtributosRegistrosRelacionados('hqPersonagem');
        }

        if($request->has('filtro')) {
            $personagemRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $personagemRepository->selectAtributos($request->atributos);
        } 

        return response()->json($personagemRepository->getResultadoPaginado(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->personagem->rules(), $this->personagem->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $personagem = $this->personagem->create([
            'personagem' => $request->personagem,
            'descricao' => $request->descricao,
            'imagem' => $imagem_urn
        ]);

        return response()->json($personagem, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Personagem  $personagem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personagem = $this->personagem->with('hqPersonagem')->find($id);
        if($personagem === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($personagem, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Personagem  $personagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $personagem = $this->personagem->find($id);

        if($personagem === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($personagem->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $personagem->feedback());

        } else {
            $request->validate($editora->rules(), $personagem->feedback());
        }
        
        //preenchendo o objeto $marca com todos os dados do request
        $personagem->fill($request->all());

        //se a imagem foi encaminhada na requisição
        if($request->file('imagem')) {
            //remove o arquivo antigo
            Storage::disk('public')->delete($personagem->logotipo);

            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $personagem->imagem = $imagem_urn;
        }

        $personagem->save();
        return response()->json($personagem, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Personagem  $personagem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $personagem = $this->personagem->find($id);

        if($personagem === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo
        Storage::disk('public')->delete($personagem->imagem);        

        $personagem->delete();
        return response()->json(['msg' => 'O personagem foi removido com sucesso!'], 200);
    }
}
