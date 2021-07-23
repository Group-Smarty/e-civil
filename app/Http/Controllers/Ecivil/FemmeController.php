<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Femme;
use Illuminate\Http\Request;

class FemmeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function findFemme($id)
    {
       $femme = Femme::where('femmes.id',$id)
                ->join('fonctions', 'fonctions.id','=','femmes.profession_id')
                ->select('fonctions.libelle_fonction')
                ->get();
       $jsonData["rows"] = $femme->toArray();
       $jsonData["total"] = $femme->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Femme  $femme
     * @return \Illuminate\Http\Response
     */
    public function show(Femme $femme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Femme  $femme
     * @return \Illuminate\Http\Response
     */
    public function edit(Femme $femme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Femme  $femme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Femme $femme)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Femme  $femme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Femme $femme)
    {
        //
    }
}
