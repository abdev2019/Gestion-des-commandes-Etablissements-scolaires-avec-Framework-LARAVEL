<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ouvrage;
use Session;

class OuvrageController extends Controller
{

    public function gerer(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        $id                 = $req->input('id');
        $designation        = $req->input('designation');
        $matiere            = $req->input('matiere');
        $niveauScolaire     = $req->input('niveauScolaire');
        $niveau             = $req->input('niveau');
        $prix               = $req->input('prix');
        $unite              = $req->input('unite');
        $code               = $req->input('code'); 
        $tva                = $req->input('tva'); 

        $etablissement = new Ouvrage;
        if($id!=0) $etablissement = Ouvrage::find($id);

        $etablissement->designation     = $designation;
        $etablissement->idMatiere       = $matiere;
        $etablissement->niveauScolaire  = $niveauScolaire;
        $etablissement->niveau          = $niveau;
        $etablissement->prix            = $prix;
        $etablissement->unite           = $unite;
        $etablissement->code            = $code;
        $etablissement->tva             = $tva;

        $etablissement->save();
        
        return "<div class='alert-success alert'><i class='fa fa-check text-success'></i> Ouvrage ".($id!=0?"$id est mis à jour":"ajouté")." ! <a href=\"".(url("administration/ouvrages"))."\">Actualiser</a></div>";
    } 
 

    public function supprimer(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        Ouvrage::where('id', $req->input('id') )->delete();         
    }
    
}
