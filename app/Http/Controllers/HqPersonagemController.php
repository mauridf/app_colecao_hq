<?php

namespace App\Http\Controllers;

use App\Models\Hq_Personagem;
use Illuminate\Http\Request;

class HqPersonagemController extends Controller
{
    public function __construct(Hq_Personagem $hqPersonagem)
    {
        $this->hqPersonagem = $hqPersonagem;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hqPersonagens = $this->hqPersonagem->all();
        return $hqPersonagens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hqPersonagem = $this->hqPersonagem->create($request->all());
        return $hqPersonagem;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hq_Personagem  $hq_Personagem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hqPersonagem = $this->hqPersonagem->find($id);
        return $hqPersonagem;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hq_Personagem  $hq_Personagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hqPersonagem = $this->hqPersonagem->find($id);
        $hqPersonagem->update($request->all());
        return $hqPersonagem;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hq_Personagem  $hq_Personagem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hqPersonagem = $this->hqPersonagem->find($id);
        $hqPersonagem->delete();
        return ['msg' => 'A relação de hq e personagem foi removida com sucesso!'];
    }
}
