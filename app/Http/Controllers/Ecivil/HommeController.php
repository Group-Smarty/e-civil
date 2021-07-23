<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Homme;
use Illuminate\Http\Request;

class HommeController extends Controller
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

    
    public function findHomme($id)
    {
       $homme = Homme::where('hommes.id',$id)
                ->join('fonctions', 'fonctions.id','=','hommes.profession_id')
                ->select('fonctions.libelle_fonction')
                ->get();
       $jsonData["rows"] = $homme->toArray();
       $jsonData["total"] = $homme->count();
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
     * @param  \App\Homme  $homme
     * @return \Illuminate\Http\Response
     */
    public function show(Homme $homme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Homme  $homme
     * @return \Illuminate\Http\Response
     */
    public function edit(Homme $homme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Homme  $homme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Homme $homme)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Homme  $homme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Homme $homme)
    {
        //
    }
}
