<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Cercle;
use App\Commune;
use App\Etablissement;


class AccueilController extends Controller
{
    public function afficher()
    {
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        $cercles = Cercle::all()->sortBy('nom'); 
        return view('accueil', ['cercles'=>$cercles]);
    }

    public function getCommunes(Request $req)
    {
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        $cercles = Commune::where('idCercle',$req->input('cercle'))->get()->sortBy('nom');
        $res = "";
        foreach($cercles as $v) 
            $res .= "<option value=\"$v->id\">$v->nom</option>";
        return $res;
    } 

    public function getEtablissements(Request $req)
    {
        if (!session()->has('utilisateur')) 
            return redirect('connexion');


        $ns = $req->input('niveauScolaire');
        $etablissements = Etablissement::where(['idCommune'=>$req->input('commune'),'niveau'=>$ns] )->get()->sortBy('nom');
        $res = "";

        foreach($etablissements as $v) 
            $res .= "<option value=\"$v->id\">$v->nom</option>";

        return $res;
    }

}
