<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SessionRequest;


use Session;
use App\Utilisateur;


class SessionController extends Controller
{
    
    public function connexion(SessionRequest $req)
    {   
        $email      = $req->input('email');
        $motdepasse = md5($req->input('motdepasse'));
 
        $utilisateur = Utilisateur::where(['email'=>$email, 'motdepasse'=>$motdepasse])->get();

        if($utilisateur->isEmpty())
            return redirect("connexion")->with([
                'oldEmail' => $email,
                'alert'=>'<div class="alert-dandger col"  style="color:rgb(255,200,200)"><i class="fa fa-exclamation-triangle"></i> &nbsp; &nbsp;Email ou mot de passe incorrect !</div><br>']);
        
        else if( !$utilisateur[0]->confirme)
            return redirect("connexion")->with([
                'oldEmail' => $email,
                'alert'=>'<div class="alert-indfo col"  style="color:rgb(100,255,100)"><i class="fa fa-exclamation-circle"></i> &nbsp;<div style="display:inline-table;width:90%">Desolé, Votre compte n\'est pas encore confirmé par l\'administration !</div></div><br>']);

        session()->regenerate();
 
        Session::put('utilisateur', $utilisateur[0]); 

        if($utilisateur[0]->type == 'admin')
        return redirect("administration/commandes/statistiques");

        return redirect("accueil");
    }

    public function deconnexion()
    { 
        Session::flush();
        return redirect('connexion');
    }
    

}
