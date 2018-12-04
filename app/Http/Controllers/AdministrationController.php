<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Etablissement;
use App\Commande;
use App\Ouvrage;
use App\Utilisateur;
use App\Cercle;
use App\Commune;
use App\Matiere;



use App\LignesCommande;


use Session;




class AdministrationController extends Controller
{
    public $columnChart;

    public function getTable($table)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');
 
        $table = substr(ucfirst($table),0, strlen($table)-1 );

        switch($table)
        { 
            case 'Etablissement': return $this->getEtablissements(Etablissement::all());
            case 'Commande'     : return $this->getCommandes(Commande::all());
            case 'Ouvrage'      : return $this->getOuvrages(Ouvrage::all());
            case 'Directeur'    : return $this->getDirecteurs(Utilisateur::where('type','user')->get());
            case 'Cercle'       : return $this->getCercles(Cercle::all());
            case 'Commune'      : return $this->getCommunes(Commune::all());
        }  
    }

    public function index()
    { 
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');
         return $this->getTable("etablissements"); 
    }

    
    public function getCommandes($lignes)
    {
        $data = '';
        $head  = 
        '<tr>
            <th>#ID</th>
            <th>Effectué par</th>
            <th>&Agrave; :</th>
            <th>Date commande</th>
            <th></th>
        </tr>';

        foreach($lignes as $key=>$ligne)
        { 
            $user = Utilisateur::find($ligne->idUtilisateur);
            $data .= '<tr>';
                $data .= "<td>{$ligne->id}</td>";  
                $data .= "<td>".( $user!=null?$user->nom:"NULL" )."</td>"; 
                $data .= "<td>".( Etablissement::find($ligne->idEtablissement)->nom )."</td>";
                $data .= "<td>{$ligne->created_at}</td>"; 
                $data .= "<td><a class='btn-primary btn' href='".url('administration/commande/'.$ligne->id)."'>Details</a></td>";  
            $data .= '</tr>';
        } 

        return view('administration/administration', [ 'data'=>$data, 'table'=>'Commandes' , 'head'=>$head] );
    }


    public function getOuvrages($lignes)
    { 
        $data = '';
        $head  = 
        '<div class="col text-right"><a onclick="$(\'#form_modificationOuvrage\').modal(); $(\'#idCC\').val(0); " class="btn-info btn"><i class="fa fa-plus"></i></a></div><br>
        <tr>
            <th>Designation</th>
            <th>Matière</th>
            <th>N.Scolaire</th>
            <th>Niveau</th>
            <th>Unité</th>
            <th>Prix(MAD)</th>
            <th>TVA</th>
            <th>Code</th>
            <th></th>
        </tr>';

        $matieres = array();
        $tmp = Matiere::all();
        foreach($tmp as $matier)
            $matieres[$matier->id] = $matier;

        foreach($lignes as $ligne)
        { 
            $data .= "<tr>\n";
                $data .= "<td>{$ligne->designation}</td>\n"; 
                $data .= "<td>{$matieres[$ligne->idMatiere]->nom}</td>\n"; 
                $data .= "<td>{$ligne->niveauScolaire}</td>\n"; 
                $data .= "<td>".($ligne->niveau>0?"N.{$ligne->niveau}":"TOUS")."</td>\n"; 
                $data .= "<td>{$ligne->unite}</td>\n"; 
                $data .= "<td>{$ligne->prix}</td>\n"; 
                $data .= "<td>{$ligne->tva}%</td>\n"; 
                $data .= "<td>{$ligne->code}</td>\n"; 
                $data .= "<td><a class='btn-primary btn' data-toggle='modal' data-target='#form_modificationOuvrage' onclick='formModifierOuvrage($ligne->id, \"".str_replace("'"," ",$ligne->designation)."\", $ligne->idMatiere, $ligne->niveau, \"$ligne->niveauScolaire\",\"$ligne->unite\", \"$ligne->prix\", \"$ligne->code\", \"$ligne->tva\" );' ><i class='fa fa-list'></i></a>\n"; 
                $data .= " <a data-toggle='modal' data-target='#form_Supprimer' class='btn-dark btn' 
                onclick='$(\"#idObjet\").val($ligne->id);' 
                ><i class='fa fa-close'></i></a></td>"; 
            $data .= "</tr>\n";
        }
        $data.= "<script>$(document).ready(function() { setTimeout( function(){ $(\"#nomObjet\").val('ouvrage'); },1); });</script>";
        
        $table = 'Ouvrages';
        return view('administration/administration', [ 'data'=>$data, 'table'=>$table , 'head'=>$head, 'matieres'=>$matieres] );
    }


    public function getEtablissements($lignes)
    {
        $data = '';
        $head  = 
        '<div class="col text-right"><a onclick="$(\'#form_modificationEtablissement\').modal(); $(\'#idEtablissement\').val(0);  " class="btn-info btn"><i class="fa fa-plus"></i></a></div><br>
        <tr>
            <th>Nom</th>
            <th>Niveau Scolaire</th>
            <th>Commune</th>
            <th>Cercle</th> 
            <th></th>
        </tr>';

        $lignesCommunes = Commune::all();
        $lignesCercles  = Cercle::all();
        
        foreach($lignesCommunes as $commune) 
            $communes[ $commune->id ] = $commune; 
        
        foreach($lignesCercles as $cercle) 
            $cercles[ $cercle->id ] = $cercle; 
        

        foreach($lignes as $ligne)
        { 
            $data .= '<tr>';
                $data .= "<td>{$ligne->nom}</td>"; 
                $data .= "<td>{$ligne->niveau}</td>";  ; 
                $data .= "<td>".($communes[ $ligne->idCommune ])->nom."</td>";  
                $data .= "<td>".($cercles[ $communes[ $ligne->idCommune ]->idCercle ])->nom."</td>"; 
                $data .= "<td><a data-toggle='modal' data-target='#form_modificationEtablissement' class='btn-info btn' 
                    onclick='formModifierEtablissement($ligne->id,\"$ligne->nom\",\"$ligne->niveau\", $ligne->idCommune);' 
                    ><i class='fa fa-list'></i></a>";  
                $data .= " <a data-toggle='modal' data-target='#form_Supprimer' class='btn-dark btn' 
                onclick='$(\"#idObjet\").val($ligne->id);' 
                ><i class='fa fa-close'></i></a></td>";
            $data .= '</tr>';
        }
        $data.= "<script>$(document).ready(function() { setTimeout( function(){ $(\"#nomObjet\").val('etablissement'); },1); });</script>";
        $table = 'Etablissements';
        return view('administration/administration', [ 'data'=>$data, 'table'=>$table , 'head'=>$head, 'communes'=>$communes] );
    }


    public function getCommunes($lignes)
    {
        $data = '';
        $head  = 
        '<div class="col text-right"><a onclick="$(\'#form_MACC\').modal(); $(\'#idCC\').val(0);  " class="btn-info btn"><i class="fa fa-plus"></i></a></div><br>
        <tr>
            <th>#ID</th>
            <th>Nom</th>
            <th>Cercle</th> 
            <th></th>
        </tr>';

        $lignesCercles = Cercle::all();
        $cercles = array();
        foreach($lignesCercles as $cercle)
            $cercles[ $cercle->id ] = $cercle;

        foreach($lignes as $ligne)
        { 
            $data .= '<tr>';
                $data .= "<td>#{$ligne->id}</td>"; 
                $data .= "<td>{$ligne->nom}</td>"; 
                $data .= "<td>{$cercles[$ligne->idCercle]->nom}</td>";  
                $data .= "<td><a class='btn-primary btn'  onclick=\"$('#nomCC').val('".str_replace("'"," ",$ligne->nom)."'); $('#cercleCommune').val($ligne->idCercle);  $('#idCC').val($ligne->id);  \" data-toggle='modal' data-target='#form_MACC' ><i class='fa fa-list'></i></a>";
                $data .= " <a data-toggle='modal' data-target='#form_Supprimer' class='btn-dark btn' 
                onclick='$(\"#idObjet\").val($ligne->id);' 
                ><i class='fa fa-close'></i></a></td>";  
            $data .= '</tr>';
        }
        $data.= "<script>$(document).ready(function() { setTimeout( function(){ $(\"#nomObjet\").val('commune');$('#trCercle').show();  },1); }); </script>";
        
        $table = 'Communes';
        return view('administration/administration', [ 'data'=>$data, 'table'=>$table , 'head'=>$head, 'cercles'=>$cercles] );
    }


    public function getCercles($lignes)
    {
        $data = '';
        $head  = 
        '<div class="col text-right"><a onclick="$(\'#form_MACC\').modal(); $(\'idCC\').val(0); " class="btn-info btn"><i class="fa fa-plus"></i></a></div><br>
        <tr>
            <th>#ID</th>
            <th>Nom</th> 
            <th></th>
        </tr>';

        foreach($lignes as $ligne)
        { 
            $data .= '<tr>';
                $data .= "<td>#{$ligne->id}</td>"; 
                $data .= "<td>{$ligne->nom}</td>";  
                $data .= "<td><a class='btn-primary btn'  onclick=\"$('#nomCC').val('".str_replace("'"," ",$ligne->nom)."'); $('#idCC').val($ligne->id);\" data-toggle='modal' data-target='#form_MACC'><i class='fa fa-list'></i></a>";
                $data .= " <a data-toggle='modal' data-target='#form_Supprimer' class='btn-dark btn' 
                onclick='$(\"#idObjet\").val($ligne->id);' 
                ><i class='fa fa-close'></i></a></td>";  
            $data .= '</tr>';
        }
        $data.= "<script>$(document).ready(function() { setTimeout( function(){ $(\"#nomObjet\").val('cercle'); },1); });</script>";
        
        $table = 'Cercles';
        return view('administration/administration', [ 'data'=>$data, 'table'=>$table , 'head'=>$head] );
    }


    public function getDirecteurs($lignes)
    {
        $data = '';
        $head  = 
        '<tr>
            <th>#ID</th>
            <th>Nom</th>
            <th>E-mail</th>
            <th>Date d\'inscription</th>
            <th>Compte Confirmé</th>
            <th></th>   
        </tr>';

        foreach($lignes as $ligne)
        { 
            $data .= '<tr>';
                $data .= "<td>#{$ligne->id}</td>"; 
                $data .= "<td>{$ligne->nom}</td>"; 
                $data .= "<td>{$ligne->email}</td>"; 
                $data .= "<td>{$ligne->created_at}</td>"; 
                //$data .= "<td>".( $ligne->confirme?"<div class='btn-success btn'>OUI</div>":"<div class='alert-dark btn'>NON</div>" )."</td>";  
                $data .= 
                '<td> 
                <select id="valConfirmation'.$ligne->id.'" onchange="$(\'#idUtilisateur\').val('.$ligne->id.'); $(\'#form_confirmerUtilisateur\').modal();" class="custom-select '.( ($ligne->confirme)?"alert-success":"alert-danger" ).'" style="height:20px">
                    <option   value="1" '.( ($ligne->confirme==1)? 'selected':'' ).'>OUI</option>
                    <option   value="0" '.( ($ligne->confirme==0)? 'selected':'' ).'>NON</option> 
                </select> 
                </td>';
                $data .= "<td><a data-toggle='modal' data-target='#form_Supprimer' class='btn-dark btn' 
                onclick='$(\"#idObjet\").val($ligne->id);' 
                ><i class='fa fa-close'></i></a></td>";
            $data .= '</tr>';
        }
        $data.= "<script>$(document).ready(function() { setTimeout( function(){ $(\"#nomObjet\").val('utilisateur'); },1); });</script>";
        
        $table = 'Directeurs';
        return view('administration/administration', [ 'data'=>$data, 'table'=>$table , 'head'=>$head] );
    }


    public function afficherStatistiques()
    {  
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
            return redirect('connexion');

        $cercles = Cercle::all(); 
        $infos = array();

        $infos['communes'] = count( Commune::all() );
        $infos['etablissements'] = count( Etablissement::all() );
        $infos['commandes'] = count( Commande::all() );

        return view('administration/statistiques', [ 'cercles'=>$cercles , 'infos'=>$infos] );
    }

    public function filtrerStatistiques(Request $req)
    { 
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion'); 

            $id    = $req->input( 'cercle' );
            $annee = $req->input( 'annee' );

            $data = ""; 
            
            $datas = Commune::where( ['idCercle'=>$id] )->get();  
            
            $nbr = 0;
            foreach($datas as $com)
            { 

                $etabs = \DB::select( '
                    select * from commandes,etablissements 
                    where   
                    commandes.idEtablissement = etablissements.id and 
                    substr(commandes.created_at,1,4) = ? and 
                    idCommune=?', 
                    [$annee,$com->id]
                );

                $etabsTotal = Etablissement::where('idCommune',$com->id)->get();
                
                $data .= "{\"label\":\"$com->nom\" , \"value\":\"".sizeof($etabs)."\" , \"displayvalue\":\" ".sizeof($etabs)."/".count($etabsTotal)."\" },"; 
                $nbr += sizeof($etabs);
            }
            
            $data .= '{"label":"0" , "value":"0", "displayvalue":""}'; 
        // "caption": "Nombre d\'établissements/commune ont effectué la commande.",

            echo '
            { 
                "lastyear": {
                    "chart": { 
                        "caption":"'.$nbr.' Etablissements",
                        "legendCaption": "Communes",
                        "plotToolText": "$label : $displayvalue etablissements",
                        "legendshowvalues": "0",
                        "showpercentvalues": "1",
                        "showborder": "0",
                        "showplotborder": "0",
                        "showlegend": "1",
                        "legendborder": "0",
                        "legendposition": "bottom",
                        "enablesmartlabels": "1",
                        "use3dlighting": "0",
                        "showshadow": "1",
                        "legendbgcolor": "#CCCCCC",
                        "legendbgalpha": "20",
                        "legendborderalpha": "0",
                        "legendshadow": "0",
                        "legendnumcolumns": "3"
                    
                    },
                        "data": ['.$data.']
                }
            }
            '; 
    }


    public function filtrerStatistiquesParCommune(Request $req)
    { 
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion'); 

            $id    = $req->input( 'commune' );  
            $annee = $req->input( 'annee' );
            $totalMontant = 0;
            
            
            $etablissements = Etablissement::where( ['idCommune'=>$id] )->get();  
            $data = ""; 
            foreach($etablissements as $etablissement)
            { 

                $cmd = \DB::select( '
                    select * from commandes
                    where    
                    substr(commandes.created_at,1,4) = ? and 
                    idEtablissement=?', 
                    [$annee,$etablissement->id]
                ); 
                $montant = 0;
                if( count($cmd) > 0 )
                {
                    $montant = $cmd[0]->prixTotalHT;
                    $totalMontant += $montant;
                }
                $data .= "{\"label\":\"$etablissement->nom\" , \"value\":\"$montant\" },"; 
            }
            
            $data .= '{ "label":"" , "value":"" }'; 

            echo '
            { 
                "lastyear": {
                    "chart": {
                        "caption":"Montant total : '.$totalMontant.' DHs",
                        "subCaption": "",
                        "numberPrefix": "",
                        "rotatevalues": "0",  

                        "legendCaption": "Etablissements",
                        "plotToolText": "$value Dhs",
                        "legendshowvalues": "0",
                        "showpercentvalues": "1",
                        "showborder": "0",
                        "showplotborder": "0",
                        "showlegend": "1",
                        "legendborder": "0",
                        "legendposition": "bottom",
                        "enablesmartlabels": "1",
                        "use3dlighting": "0",
                        "showshadow": "1",
                        "legendbgcolor": "#CCCCCC",
                        "legendbgalpha": "20",
                        "legendborderalpha": "0",
                        "legendshadow": "0",
                        "legendnumcolumns": "3",
                        "palettecolors": "#f8bd19,#e44a00,#008ee4,#33bdda,#6baa01,#583e78"
                    
                    },
                        "data": ['.$data.']
                }
            }
            '; 
    }


    public function getStatistiquesEtablissement(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
            return redirect('connexion'); 

        $cmdT = \DB::select( '
            select * from commandes
            where    
            substr(commandes.created_at,1,4) = ? and 
            idEtablissement=?', 
            [$req->annee,$req->etab]
        ); 

        
        $html = "<style>#tabInfosEtab th{border:none;}  0.01em rgb(100,100,255)</style>";
        $html .= "<fieldset style='border:solid rgb(100,100,255);padding:0'>
                  <legend style='width:200px;font-size:13px;margin-left:15px;text-align:center' id='nomEtab'></legend>";
        
        $cmd = null;
        if( count($cmdT)>0 ) $cmd = $cmdT[0];
        else return "$html <div class='alert alert-warning' style='margin:10px'>Aucune information !</div></fieldset>";

        $lignes = LignesCommande::where( 'idCommande',$cmd->id )->get(); 


        $html .= "<table class='table text-light' style='margin:0'  id='tabInfosEtab'>";
        $html .=    "<thead>"; 

        $html .=        "<tr>";
        $html .=            "<th>Ouvrages</th>";
        $html .=            "<th>: ".count($lignes)."</th>";
        $html .=        "</tr>";

        $html .=        "<tr>";
        $html .=            "<th>Total HT</th>";
        $html .=            "<th>: $cmd->prixTotalHT DHs</th>";
        $html .=        "</tr>";

        $html .=        "<tr>";
        $html .=            "<th>Total TTC</th>";
        $html .=            "<th>: $cmd->prixTTC DHs</th>";
        $html .=        "</tr>"; 

        $html .=        "<tr>";
        $html .=            "<th>Date</th>";
        $html .=            "<th>: $cmd->created_at</th>";
        $html .=        "</tr>"; 

        $html .=        "<tr>";
        $html .=            "<th>Confirmé</th>";
        $html .=            "<th>: ".( $cmd->confirme==1?"<font color='green'>Oui</font>":"<font color='black'>Non</font>" )."</th>";
        $html .=        "</tr>";

        $html .=        "<tr>";
        $html .=            "<th>
                                <a  style='margin:0;'
                                    target='_blanc' class='btn btn-success' 
                                    href='".url('/administration/commande/'.$cmd->id)."'>
                                    Détails &nbsp;
                                    <i class='fa fa-external-link' aria-hidden='true'></i>
                                </a>
                            </th>"; 
        $html .=        "</tr>";

        $html .=    "</thead>";
        $html .=    "<tbody>";/*
        $html .=        "<tr>";
        $html .=            "<td></td>";
        $html .=        "</tr>";*/
        $html .=    "</tbody>";
        $html .= "<table></fieldset>";

        return $html;
        
    }

    
    

}
