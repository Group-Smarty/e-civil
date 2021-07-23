<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Mere;
use Illuminate\Http\Request;

class MereController extends Controller
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

    public function findMere($id){
        $mere = Mere::where('meres.id',$id)
                ->join('fonctions', 'fonctions.id','=','meres.fonction_id')
                ->join('nations', 'nations.id','=','meres.nation_id')
                ->select('fonctions.libelle_fonction','nations.libelle_nation')
                ->get();
       $jsonData["rows"] = $mere->toArray();
       $jsonData["total"] = $mere->count();
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
     * @param  \App\Mere  $mere
     * @return \Illuminate\Http\Response
     */
    public function show(Mere $mere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mere  $mere
     * @return \Illuminate\Http\Response
     */
    public function edit(Mere $mere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mere  $mere
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mere $mere)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mere  $mere
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mere $mere)
    {
        //
    }
}
