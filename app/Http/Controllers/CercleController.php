<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cercle;
use Session;

class CercleController extends Controller
{
    public function ajouter(Request $req)
    {
        $commune = new Cercle;
        $commune->nom = $req->input('nom');
        $commune->save();
    }

    public function modifier(Request $req)
    {
        $commune = Cercle::find($req->input('id'));
        $commune->nom = $req->input('nom');
        $commune->save();
    }
 
    public function supprimer(Request $req)
    {
        Cercle::where('id', $req->input('id') )->delete();         
    }
}
