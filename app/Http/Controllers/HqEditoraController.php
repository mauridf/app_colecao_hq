<?php

namespace App\Http\Controllers;

use App\Models\Hq_Editora;
use Illuminate\Http\Request;

class HqEditoraController extends Controller
{
    public function __construct(Hq_Editora $hqEditora)
    {
        $this->hqEditora = $hqEditora;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hqEditoras = $this->hqEditora->all();
        return $hqEditoras;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hqEditora = $this->hqEditora->create($request->all());
        return $hqEditora;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hq_Editora  $hq_Editora
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hqEditora = $this->hqEditora->find($id);
        return $hqEditora;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hq_Editora  $hq_Editora
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hqEditora = $this->hqEditora->find($id);
        $hqEditora->update($request->all());
        return $hqEditora;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hq_Editora  $hq_Editora
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hqEditora = $this->hqEditora->find($id);
        $hqEditora->delete();
        return ['msg' => 'A relação de hq e editora foi removida com sucesso!'];
    }
}
