<?php

namespace App\Http\Controllers;
 



use Illuminate\Http\Request;  
use App\Http\Requests\UtilisateurRequest;

use App\Utilisateur; 


use Mail;
use Session;

class UtilisateurController extends Controller
{
    
    public function creerCompte(UtilisateurRequest $req)
    {
        $user = new Utilisateur;

        $user->nom = $req->input('nom');

        $user->email = $req->input('email');

        $user->motdepasse = md5( $req->input('motdepasse') );

        $user->confirme = 0;

        $user->type='user';

        $user->save();
        
    }

    public function recupererCompte(Request $req)
    {  

        $email = $req->input('email'); 
        
        $user = Utilisateur::where('email',$email)->get();
        
        if( !sizeof($user) )
        return "<div class='text-danger alert-danger alert'> 
                    Aucun compte a l'e-mail email $email !
                </div>";
        
        $body = "
        <div style='color:white;background:rgb(70,70,170);border-radius:5px;font-size:20px;padding:20px'>
        <h1>Bonjour ".$user[0]->nom.",</h1> <br>
        <p>Le mot de passe de votre compte est : 
                <span style='color:red'><b>".($user[0]->motdepasse)."</b></span></p>
        </div>";

 
        Mail::send(array(), array(), function ($message) use ($body,$email) {
            $message->to($email)
                ->subject('Récuperation du compte')
                ->from('informatique.taroudant@gmail.com','Administration')
                ->setBody($body, 'text/html');
            });

            $user[0]->motdepasse = md5($user[0]->motdepasse);
            $user[0]->save();
             
    
        return "<div class='text-success alert-success alert'> 
                    Vérfier votre boite de reception de $email !
                </div>";
                 
    }

    
    public function confirmerUtilisateur(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        $user           = Utilisateur::find( $req->input('id') );
        if(!$user) return;
        $user->confirme = $req->input('valConfirmation');
        $user->save();
    }

    public function supprimerUtilisateur(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');
        
        Utilisateur::where('id', $req->input('id') )->delete();                 
    }

    public function modifierInformations(UtilisateurRequest $req)
    {
        if(Session::get('utilisateur')->id != $req->input('id')) return "iii";

        $user = Utilisateur::find( $req->input('id') );
 
        if($user=="") return;

        $user->nom        = $req->input('nom');
        $user->email      = $req->input('email');
        $user->motdepasse = md5( $req->input('motdepasse') );
        
        $user->save();
        return "<div class='alert-success text-success alert'>Mise à jour réussite !</div>";
    }
}
