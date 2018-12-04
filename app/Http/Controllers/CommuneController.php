<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commune;
use Session;

class CommuneController extends Controller
{

    public function ajouter(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        $commune = new Commune;
        $commune->nom = $req->input('nom');
        $commune->idCercle = $req->input('cercle');
        $commune->save();
    }

    public function modifier(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        $commune = Commune::find($req->input('id'));
        $commune->nom = $req->input('nom');
        $commune->idCercle = $req->input('cercle');
        $commune->save();
    }

    public function supprimer(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        Commune::where('id', $req->input('id') )->delete();         
    }
}
