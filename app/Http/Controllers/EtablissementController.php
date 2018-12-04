<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\EtablissementRequest;


use App\Ouvrage;
use App\Matiere;
use App\Etablissement;
use App\Commune;
use App\Cercle;


use Session;

class EtablissementController extends Controller
{

    public function init(EtablissementRequest $req)
    { 
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        $etablissement = Etablissement::find($req->input('etablissement'));
        $commune       = Commune::find($req->input('commune'));
        $cercle        = Cercle::find($commune->idCercle);

        Session::put('cercle', $cercle);
        Session::put('commune', $commune);
        Session::put('etablissement', $etablissement); 

        Session::put('ouvrages', array());


        $test = \DB::select( '
            select id from commandes  where   
            commandes.idEtablissement = ? and substr(created_at,1,4)=?' , 
            [Session::get('etablissement')->id, date("Y")]
        );


        if(!session()->has('ModificationCommande') && count($test)>0 ) 
            return redirect('etablissement')->with(
                'commandeEpuise',
                "<div class='text-danger alert-warning alert' style='padding:5px; background-color:rgba(\"#FFFFFF\")'>
                Désolé, Vous avez déja effectué une commande pour cette année !<br>
                Merci de contacter l'administration<br><br>
                <center><a href='".url("/commande/{$test[0]->id}")."'>Consulter la commande</a></center></div><br>");
        

        return redirect('/etablissement');
    } 
    
    
    public function filtrer(Request $req)
    {   
        $ouvrage = '';

        $ns = Session::get('etablissement')->niveau;
        $m  = $req->input('matiere');
        $n  = $req->input('niveau');

        if($n==0 && $m==0) 
            $ouvrages = Ouvrage::where(['niveauScolaire'=>$ns])->get();

        else if($n!=0 && $m==0) 
            $ouvrages = Ouvrage::where(['niveauScolaire'=>$ns,'niveau'=>$n])->get();

        else if($n==0 && $m!=0) 
            $ouvrages = Ouvrage::where(['niveauScolaire'=>$ns,'idMatiere'=>$m])->get();

        else if($n!=0 && $m!=0) 
            $ouvrages = Ouvrage::where(['niveauScolaire'=>$ns,'idMatiere'=>$m, 'niveau'=>$n])->get();
        
        
        $tmp_matiers  = Matiere::all();

        $matiers = array();
        foreach($tmp_matiers as  $v) 
            $matiers[$v->id] = $v->nom; 

       $re='
       <table id="tableListeOuvrages" class="table-responsive table-hover" style="color:white; width:100%;"> 
            <thead class="alert-primary" >
            <tr>   
                <th style="height:40px">DESIGNATION DES OUVRAGES</th> 
                <th>NIVEAU</th> 
                <th>MATIERE</th>  
                <th style="width:80px">Quantité</th>  
            </tr>
            </thead>
            <tbody> ';

        if(isset($ouvrages)){
        $ar = Session::get('ouvrages');
        foreach($ouvrages as $ouvrage)
        {
            $re .= "<tr>\n";                
                $re .= "<td style=\"padding-bottom:20px\">$ouvrage->designation</td>\n";
                $re .= "<td style=\"padding-bottom:20px\">".( ($ouvrage->niveau==0)?"Fournitures":"NIVEAU $ouvrage->niveau" )."</td>\n";
                //$re .= "<td style=\"padding-bottom:20px\">$ouvrage->code</td>\n";
                $re .= "<td style=\"padding-bottom:20px\">{$matiers[$ouvrage->idMatiere]}</td>\n";
                //$re .= "<td style=\"padding-bottom:20px\">$ouvrage->unite</td>\n";
                //$re .= "<td style=\"padding-bottom:20px\">$ouvrage->prix</td>\n";

                if( !isset($ar[('_qo'.$ouvrage->id)]) )
                    $re .= 
                        "<td style='padding:0; padding-bottom:20px' class=\"text-center\" id=\"td{$ouvrage->id}\" > 
                            <i class=\"fa fa-plus btn-info btn-sm\" 
                                onclick='addQte($ouvrage->id,1);' >
                            </i> 
                        </td>\n";
                else 

                    $re .="<td style='padding:0; padding-bottom:20px' class=\"text-center\" id=\"td{$ouvrage->id}\" >
                    <div class=\"input-group\">
                    <input  onkeypress=\"return valider(event);\" value=\"{$ar[('_qo'.$ouvrage->id)]}\" placeholder=0 name='_qo$ouvrage->id'  type=\"text\" class=\"form-control form-control-sm\" style=\"width:10%;\" onblur=\"gererSession(1,$ouvrage->id,this.value)\">
                    <div class=\"input-group-addon btn-secondary btn-sm\" onclick='addQte($ouvrage->id,0); gererSession(0,$ouvrage->id,0);' >
                    <span class=\"fa fa-close\"></span></div></div></td>\n";

            $re .= "</tr>\n";
        }
        } 
        
        $re.='</tbody></table>';
        return  $re;   
    }


    public function afficher()
    {  
        if (!session()->has('utilisateur')) 
            return redirect('connexion');
            
        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $ouvrages     = Ouvrage::all();  
        $tmp_matiers  = Matiere::all();

        $matiers = array();
        foreach($tmp_matiers as  $v) 
            $matiers[$v->id] = $v->nom;
            
            

        $test = \DB::select( '
            select id from commandes  where   
            commandes.idEtablissement = ? and substr(created_at,1,4)=?' , 
            [Session::get('etablissement')->id, date("Y")]
        );
 
        if(!session()->has('ModificationCommande') && count($test)>0 ) 
            return view('etablissement')->with(
                'commandeEpuise',
                "<h5    class='text-danger alert-warning alert' >
                    Désolé, Vous avez déja effectué une commande pour cette année !<br>
                    Merci de contacter l'administration<br><br>
                    <a style='color:blue' href='".url("/commande/{$test[0]->id}")."'>
                        Consulter la commande
                    </a> 
                </h5><br>");
                

        return view(
                'etablissement',
                ['ouvrages'=>$ouvrages, 'matiers'=>$matiers]
        );
    } 


    public function gerer(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');
        
        $id      = $req->input('id');
        $nom     = $req->input('nom');
        $niveau  = $req->input('niveau');
        $commune = $req->input('commune');

        $etablissement = new Etablissement;
        $etablissement->idAnnexe = 29;

        if($id!=0) $etablissement = Etablissement::find($id); 

        $etablissement->nom = $nom;
        $etablissement->niveau = $niveau;
        $etablissement->idCommune = $commune;
        $etablissement->save();
        
        return "<div class='alert-success alert'><i class='fa fa-check text-success'></i> Etablissement ".($id!=0?"$id est mis à jour":"ajouté")." ! <a href=\"".(url("administration/etablissements"))."\">Actualiser</a></div>";
    }

    public function supprimer(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        Etablissement::where('id', $req->input('id') )->delete(); 
    }
 
}


 