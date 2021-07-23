<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Pere;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PereController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

   
    public function create()
    {
        //
    }

    public function findPere($id){
        $pere = Pere::where('peres.id',$id)
                ->join('fonctions', 'fonctions.id','=','peres.fonction_id')
                ->join('nations', 'nations.id','=','peres.nation_id')
                ->select('fonctions.libelle_fonction','nations.libelle_nation')
                ->get();
       $jsonData["rows"] = $pere->toArray();
       $jsonData["total"] = $pere->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pere  $pere
     * @return Response
     */
    public function show(Pere $pere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pere  $pere
     * @return Response
     */
    public function edit(Pere $pere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Pere  $pere
     * @return Response
     */
    public function update(Request $request, Pere $pere)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pere  $pere
     * @return Response
     */
    public function destroy(Pere $pere)
    {
        //
    }
}
