<?php 
namespace App\Http\Controllers;

global $char128asc,$char128charWidth;

use Illuminate\Http\Request;



use App\Ouvrage;
use App\Commande;
use App\LignesCommande;
use App\Utilisateur;
use App\Etablissement; 
use App\Commune;
use Session;
use App; 


class CommandeController extends Controller
{ 

    public function afficherCommandeActuel()
    {  
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $ouvrages = array();
        $ar = Session::get('ouvrages');
        if(isset($ar))
        foreach( $ar as $id=>$qte )
        {
            $id = substr($id,3);
            $ouvrages[$id] = Ouvrage::find($id);
            $ouvrages[$id]->qte = $qte;
        } 

        $nbrsEleves = array();
        for($i=1;$i<=6;$i++)
        {
            if( Session::get('N'.$i)!=null && Session::get('N'.$i)!=0 )
            $nbrsEleves[$i] = Session::get('N'.$i);
        }
        

        return view('panier', ['ouvrages'=>$ouvrages, 'nbrsEleves'=>$nbrsEleves]);
    }


    public function afficherCommande($id)
    {  
        if (!session()->has('utilisateur')) 
            return redirect('connexion'); 

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $commande = Commande::where( ['id'=>$id, 'idEtablissement'=>Session::get('etablissement')->id, 'idUtilisateur'=>Session::get('utilisateur')->id ] )->get()[0]; 

        if ( !$commande ) 
            return redirect('commandes');

        $commande->etablissement = Etablissement::find($commande->idEtablissement);
        $commande->etablissement->commune = Commune::find($commande->etablissement->idCommune);
        $commande->directeur     = Utilisateur::find($commande->idUtilisateur);
        
        $lignesCommande = LignesCommande::where('idCommande',$id)->get();

        $ouvrages = array(); 

        foreach($lignesCommande as $ligneCommande)
        {
            $ouvrages[$ligneCommande->idOuvrage]      = Ouvrage::find($ligneCommande->idOuvrage);
            $ouvrages[$ligneCommande->idOuvrage]->qte = $ligneCommande->quantite;
        }

        $ouvragesTrie = array(); 
        $nbrOparN = array();
        for($i=0;$i<=6;$i++)
        {
            $k=0;
            $nbrOparN[ $i ] = 0;
            foreach($ouvrages as $ouvrage)
            {
                if($ouvrage->niveau==$i)
                {
                    $ouvragesTrie[$ouvrage->id] = $ouvrage;
                    $k++;
                    $nbrOparN[ $i ] = $k;
                }
            }
        }

        $nbrsEleves = array(); 
        $elvs = $commande->nbrEleves; 

        $nbrsEleves = explode( ',',$elvs);
        
        return view('commande', ['ouvrages'=>$ouvragesTrie, 'commande'=>$commande, 'nbrsEleves'=>$nbrsEleves]);
    }


    public function afficherLesCommandes()
    { 
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $commandes = Commande::where( 
            ['idEtablissement'=>
            Session::get('etablissement')->id , 
            'idUtilisateur'=>
            Session::get('utilisateur')->id] 
        )->get(); 
        
        $test = true;
        foreach($commandes as $commande)
        {
            $commande->directeur = Utilisateur::find($commande->idUtilisateur);
            $commande->etablissement = Etablissement::find($commande->idEtablissement); 
            if( $test && substr($commande->created_at,0,4) === date("Y") ){
                Session::put('maCommande',$commande->id);
                $test=false;
            }
        }
        return view("commandes", ['commandes'=>$commandes]);
    }

    

    public function ajouterAuSession(Request $r)
    {    
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $key = '_qo'.$r->input('id'); 
        $val = $r->input('val'); if($val=="") return;
        $ouvrages  = Session::get('ouvrages'); 
        /*if(!isset($ouvrages))
            $ouvrages = array();*/

        if( array_key_exists($key, $ouvrages) )   
            unset( $ouvrages[$key] ); 

        $ar = array_merge_recursive($ouvrages,[$key=>$val]); 
        
        Session::put( 'ouvrages' , $ar );
        return $key." : ".$val;
    }

    public function retirerDeSession(Request $r)
    {
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $key = '_qo'.$r->input('id'); 
        
        $ar = Session::get('ouvrages');
        if( !array_key_exists($key, $ar) ) return;
        
        unset($ar[$key]);
        Session::put('ouvrages', $ar);

        return "retirer : _qo".$r->input('id');
    }

    public function setNbrEleves(Request $r) 
    {    
        if (!session()->has('utilisateur')) 
            return redirect('connexion'); 

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        for($i=1;$i<=6;$i++) 
        { 
            if( $r->input('N'.$i) != null  ) 
                Session::put('N'.$i, $r->input('N'.$i) ); 
        } 
    }  


    public function modifierCommande($n)
    { 
        if (!session()->has('utilisateur')) 
            return redirect('connexion');

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 
            
        $cmd = Commande::find($n);

        if($cmd->tentative==0)
            return Redirect()->back()
            ->with('tentativeEpuise',
                "<span class='text-danger ' style='padding:5px; text-shadow:0.3px 0.3px red'>  
                Vous avez depasser le nombre maximal de mis à jour ! Merci de contacter l'administration
                </span>"
            );
        
        $lignesCommande = LignesCommande::where('idCommande',$cmd->id)->get();
        Session::put('ModificationCommande',$cmd->id);
        $ar = array();

        foreach( $lignesCommande as $ligne ) 
            $ar['_qo'.$ligne->idOuvrage] = $ligne->quantite;  
        Session::put( 'ouvrages' , $ar );
 
        $elvs       = $cmd->nbrEleves;  
        $nbrsEleves = explode( ',',$elvs); 

        for($i=1;$i<=6;$i++) 
        { 
            $val = substr( $nbrsEleves[$i-1] , 10 );  
                Session::put('N'.$i, $val ); 
        } 

        return Redirect('/etablissement');
    }


    public function effacerPanier()
    {
        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        Session::forget('ModificationCommande');

        for($i=1;$i<=6;$i++) 
            Session::forget('N'.$i); 

        $ar = Session::get('ouvrages');
        unset($ar);
        Session::put('ouvrages', array());
        return redirect('etablissement');
    }
 




    public function ajouterCommande()
    {
        if (!session()->has('utilisateur'))
            return redirect('connexion');

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $test = \DB::select( '
            select * from commandes  where   
            commandes.idEtablissement = ? and substr(created_at,1,4)=?' , 
            [Session::get('etablissement'), date("Y")]
        );

        if( count($test) > 0 ) 
            return redirect('etablissement')->with(
                'commandeEpuise',
                "<span  style='padding:5px' class='text-danger alert-danger'>Vous avez déja effectuer une commande ! Merci de contacter l'administration</span><br>");
        


        $ouvrages = Session::get('ouvrages');

        if( session()->has('ModificationCommande') ){
            $commande = Commande::find(Session::get('ModificationCommande'));
            if($commande->tentative==0)
                return redirect()->back()->with('tentativeEpuise',"<span  style='padding:5px' class='text-danger alert-danger'>Vous avez depasser le nombre maximal de mis à jour ! <br>Merci de contacter l'administration</span>");
            $commande->tentative--;
        }
        else $commande = new Commande;

        $commande->idUtilisateur   = (Session::get('utilisateur'))->id;
        $commande->idEtablissement = (Session::get('etablissement'))->id;
        $commande->nbrOuvrages     = sizeof($ouvrages); 
        $commande->prixTotalHT     = 0;
        $commande->confirme     = 0;

        $nbrsEleves = "";
        if( Session::get('N1')!=null && Session::get('N1')!=0 )
            $nbrsEleves = "Niveau 1 : ".Session::get('N1');
        else $nbrsEleves = "Niveau 1 : 0";

        for($i=2;$i<=6;$i++)
        {
            if( Session::get('N'.$i)!=null && Session::get('N'.$i)!=0 )
                $nbrsEleves .= ",Niveau $i : ".Session::get('N'.$i);
            else $nbrsEleves .= ",Niveau $i : 0";
        }

        $commande->nbrEleves = $nbrsEleves;

        $commande->save();

        $total = 0;
        $ttc = 0;
        foreach($ouvrages as $id => $qte)
        {
            $ouvrage = Ouvrage::find(substr($id,3));
            if(!$ouvrage) return;
            
            $ligneCommande = new LignesCommande;
            $ligneCommande->idCommande = $commande->id;
            $ligneCommande->idOuvrage  = $ouvrage->id;
            $ligneCommande->quantite   = $qte; 
            $ligneCommande->save();
            
            $tmp    = $qte * $ouvrage->prix * $ouvrage->unite;
            $total += $tmp;
            $ttc   += $tmp*$ouvrage->tva / 100.0;
        }

        $ttc = $total + $ttc;

        $commande->prixTotalHT = $total;
        $commande->prixTTC     = $ttc;
        $commande->save();

        $this->effacerPanier();
        return redirect("commande/$commande->id");
    } 


    public function confirmerCommande(Request $req)
    { 
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');
        
        $commande = Commande::find( $req->input('id') );
        $commande->confirme = 1;
        $commande->save();
    }

    public function supprimer(Request $req)
    {
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        LignesCommande::where('idCommande', $req->input('id') )->delete(); 
        Commande::where('id', $req->input('id') )->delete();                 
    }


    public function afficherCommandeAdmin($id)
    {    
        if( !Session::get('utilisateur') || Session::get('utilisateur')->type!='admin' )
        return redirect('connexion');

        $commande = Commande::find($id); 

        if ( !$commande ) return redirect('administration/commandes');

        $commande->etablissement = Etablissement::find($commande->idEtablissement);
        $commande->etablissement->commune = Commune::find($commande->etablissement->idCommune);
        $commande->directeur     = Utilisateur::find($commande->idUtilisateur);
        
        $lignesCommande = LignesCommande::where('idCommande',$id)->get();

        $ouvrages = array(); 

        foreach($lignesCommande as $ligneCommande)
        { 
            $ouvrages[$ligneCommande->idOuvrage]      = Ouvrage::find($ligneCommande->idOuvrage);
            $ouvrages[$ligneCommande->idOuvrage]->qte = $ligneCommande->quantite;
        }
        
        return view('administration/commande', ['ouvrages'=>$ouvrages, 'commande'=>$commande]);
    }

    public function imprimer($n)
    {  
        $id = $n;
        if (!session()->has('utilisateur')) 
        return redirect('connexion'); 

        if (session('utilisateur')->type !== 'admin' && !session()->has('etablissement')) 
            return redirect('accueil'); 

        if( session('utilisateur')->type !== 'admin' )
            $commande = Commande::where( [
                            'id'=>$id, 
                            'idEtablissement'=>Session::get('etablissement')->id, 
                            'idUtilisateur'=>Session::get('utilisateur')->id 
                        ] )->get()[0]; 
        else 
            $commande = Commande::where( [
                'id'=>$id
            ] )->get()[0]; 

        if ( !$commande ) 
            return redirect('commandes');

        $commande->etablissement = Etablissement::find($commande->idEtablissement);
        $commande->etablissement->commune = Commune::find($commande->etablissement->idCommune);
        $commande->directeur     = Utilisateur::find($commande->idUtilisateur);
        
        $lignesCommande = LignesCommande::where('idCommande',$id)->get();

        $ouvrages = array(); 

        foreach($lignesCommande as $ligneCommande)
        {
            $ouvrages[$ligneCommande->idOuvrage]      = Ouvrage::find($ligneCommande->idOuvrage);
            $ouvrages[$ligneCommande->idOuvrage]->qte = $ligneCommande->quantite;
        }

        $ouvragesTrie = array();  
        for($i=0;$i<=6;$i++)
        {
            $k=0; 
            foreach($ouvrages as $ouvrage)
            {
                if($ouvrage->niveau==$i)
                {
                    $ouvragesTrie[$ouvrage->id] = $ouvrage;
                    $k++; 
                }
            }
        }

        $logo = '<img src="'.( url('img/logo.png') ).'" style="width:18%" />';

        $head = 
            '<table style="font-size:12px;margin-bottom:0; " > 
            <thead><th></th> <th></th> <th></th></thead>    
            <tbody>
            <tr>
                <th style="width:32%; text-align:center" > 
                        Royaume du Maroc<br>
                        Ministère de l\'Education Nationale, de la Formation Professionnelle, 
                        de l\'Enseignement Supérueur et de la Recherche Scientifique 
                    </th>  
                <th class="text-center" style="width:38%; " class="text-center">'.$logo.'</th> 
               
                <th  style="width:27.5%;  " class="text-center" >
                Région SOUSS MASSA<br>
                Délégué provincial TAROUDANT<br>Commune '.$commande->etablissement->commune->nom.'<br>Ecole '.$commande->etablissement->nom.'</th>
            </tr>"
            </tbody>
            </table>';



        $footer = "N.B : Ne pas écrire avec un stylo ou un crayon sur cette papier.
        <barcode  code='CMD{$commande->id}E{$commande->etablissement->id}' type='C39' size='0.5' height='1.5' />";



        $out  = '
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
                <link rel="stylesheet" type="text/css" 
                href="'.( url('css/bootstrap.min.css') ).'">    
                
            </head>
            <body style=""><center>   ';


        $nbrsEleves = explode(',',$commande->nbrEleves); 
              
        $styles = array( 'background:white','background:aliceblue' );
        $i=0;

 
        $exist = false;
        foreach($ouvragesTrie as $ouvrage)
            if($ouvrage->niveau==0) { $exist=true; break; }

        if($exist)
        {
            $out .= "   
                <table   class='table' style='border-bottom:solid;'> 
                <thead> <th></th> <th></th> </thead>    
                <tbody> 
                <tr> <td colspan=2><b><h5 style='font-weight:bold'>Fornitures</h5></b></td></tr>
                <tr style='border:0.01em solid;'>
                <th  style='border-right:0.01em solid;border-left:0.01em solid;'>Designation<br></th>  
                <th  style='border-right:0.01em solid;width:15%'>Quantité<br></th></tr>";
    
            foreach($ouvragesTrie as $ouvrage)
            {
                if($ouvrage->niveau != 0) break;  
                if($i==2) $i=0; 
    
                $out .= "<tr style='".$styles[$i++]."' >\n";  
                    $out .= "<td style='border-right:0.01em solid;border-left:0.01em solid;'>$ouvrage->designation</td>\n";
                    $out .= "<td style='border-right:0.01em solid'>$ouvrage->qte</td>\n";
                $out .= "</tr>\n";
            }
        }
        else $out .= '<table   class="table" style="border-bottom:solid"> 
             <thead><th></th> <th></th></thead> ';

        $last = -1;
        foreach($ouvragesTrie as $ouvrage)
        {
            if($ouvrage->niveau == 0) continue;

            if( $ouvrage->niveau != $last )
            {
                $out .= '
                <tr><td style="border-top:solid" colspan=2><br><br></td></tr>  
                    <tr> <th><h5>Niveau '.$ouvrage->niveau.': </h5></th> <th>'.substr($nbrsEleves[$ouvrage->niveau-1],10).' élèves</th></tr>
                        <tr style="border:0.01em solid;">
                        <th  style=\'border-right:0.01em solid;border-left:0.01em solid;\'>Designation<br></th>  
                        <th  style=\'border-right:0.01em solid;\'>Quantité<br></th></tr>';
                $last = $ouvrage->niveau;
            }

            $out .= "<tr style='".$styles[$i++]."'>\n";  
                $out .= "<td style='border-right:0.01em solid;border-left:0.01em solid;'>$ouvrage->designation</td>\n";
                $out .= "<td style='border-right:0.01em solid'>$ouvrage->qte</td>\n";
            $out .= "</tr>\n";

            if($i==2) $i=0;
        }

        $out .= '</tbody></table></center></body></html>';
            
  
        require_once '../vendor/mpdf/mpdf/mpdf.php'; 
        $mpdf = new \mPDF('utf-8','A4','','','10','10','6','38');
        $mpdf->setAutoTopMargin = true;

        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $mpdf->SetHeader($head); 
        $mpdf->setFooter($footer); 
        $mpdf->WriteHTML($out);
        $mpdf->Output();
    }

    public function iqmprimer($n)
    {  
        $id = $n;
        if (!session()->has('utilisateur')) 
        return redirect('connexion'); 

        if (!session()->has('etablissement')) 
            return redirect('accueil'); 

        $commande = Commande::where( ['id'=>$id, 'idEtablissement'=>Session::get('etablissement')->id, 'idUtilisateur'=>Session::get('utilisateur')->id ] )->get()[0]; 

        if ( !$commande ) 
            return redirect('commandes');

        $commande->etablissement = Etablissement::find($commande->idEtablissement);
        $commande->etablissement->commune = Commune::find($commande->etablissement->idCommune);
        $commande->directeur     = Utilisateur::find($commande->idUtilisateur);
        
        $lignesCommande = LignesCommande::where('idCommande',$id)->get();

        $ouvrages = array(); 

        foreach($lignesCommande as $ligneCommande)
        {
            $ouvrages[$ligneCommande->idOuvrage]      = Ouvrage::find($ligneCommande->idOuvrage);
            $ouvrages[$ligneCommande->idOuvrage]->qte = $ligneCommande->quantite;
        }

        $ouvragesTrie = array();  
        for($i=0;$i<=6;$i++)
        {
            $k=0; 
            foreach($ouvrages as $ouvrage)
            {
                if($ouvrage->niveau==$i)
                {
                    $ouvragesTrie[$ouvrage->id] = $ouvrage;
                    $k++; 
                }
            }
        } 

 

        $logo = '<img src="'.( url('img/logo.png') ).'" style="width:18%" />';

        $header =  '
        <div>
        <table style="font-size:12px;margin-bottom:0;" > 
        <tbody>
        <tr>
            <th style="width:35%; text-align:center" > 
                    Royaume du Maroc<br>
                    Ministère de l\'Education Nationale, de la Formation Professionnelle, 
                    de l\'Enseignement Supérueur et de la Recherche Scientifique 
                </th> 
            <th class="text-center" style="width: ; " class="text-center">'.$logo.'</th> 
            
            <th  style="width:30%;  " class="text-center " >
            Région SOUSS MASSA<br>Délegué provincial TAROUDANT<br>Commune '.$commande->etablissement->commune->nom.'<br>Ecole '.$commande->etablissement->nom.'</th>
        </tr>"
        </tbody>
        </table></div>';

        $footer = "N.B : Ne pas écrire avec un stylo ou un crayon sur cette papier.
        <barcode  code='CMD{$commande->id}E{$commande->etablissement->id}' type='C39' size='0.5' height='1.5' />";


            
 

        $out  = '
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
            <link rel="stylesheet" type="text/css" 
            href="'.( url('css/bootstrap.min.css') ).'">    
            <style>  
                div.b128{
                    border-left: 1px black solid;
                    height: 30px;
                }	 
            </style>
        </head>
        <body style=""><center>   ';

        $nbrsEleves = explode(',',$commande->nbrEleves); 
              
        $styles = array( 'background:white','background:aliceblue' );
        $i=0;

 
        $exist = false;
        foreach($ouvragesTrie as $ouvrage)
            if($ouvrage->niveau==0) { $exist=true; break; }

        if($exist)
        $out .= "   <table   class='table' style='border-bottom:solid;'> 
                    <thead> <th></th> <th></th> </thead>    
                    <tbody>
                    <tr> <td colspan=2><h5><b>Fornitures</b></h5></td></tr>
                    <tr style='border:0.01em solid;'>
                    <th  style='border-right:0.01em solid;border-left:0.01em solid;'>Designation<br></th>  
                    <th  style='border-right:0.01em solid;width:15%'>Quantité<br></th></tr>";

        $last = -1;

        if($exist)
        foreach($ouvragesTrie as $ouvrage)
        {
            if($ouvrage->niveau != 0) break;  
            if($i==2) $i=0; 
 
            $out .= "<tr style='".$styles[$i++]."' >\n";  
                $out .= "<td style='border-right:0.01em solid;border-left:0.01em solid;'>$ouvrage->designation</td>\n";
                $out .= "<td style='border-right:0.01em solid'>$ouvrage->qte</td>\n";
            $out .= "</tr>\n";
        }

        if($exist) $out .= "</tbody></table>";
        
        $out .= "<table style='display:none'><tbody></tbody></table>";
        $out .= '
            <table   class="table" style="border-bottom:solid"> 
            <thead><th></th> <th></th></thead>    
            <tbody>';

        foreach($ouvragesTrie as $ouvrage)
        {
            if($ouvrage->niveau == 0) continue;

            if( $ouvrage->niveau != $last )
            {
                $out .= ' 
                    <tr ><td style="border-top:solid" colspan=2><br><br></td></tr>
                    <tr> <th><h5>Niveau '.$ouvrage->niveau.': </h5></th> <th>'.substr($nbrsEleves[$ouvrage->niveau-1],10).' élèves</th></tr>
                        <tr style="border:0.01em solid;">
                        <th  style=\'border-right:0.01em solid;border-left:0.01em solid;\'>Designation<br></th>  
                        <th  style=\'border-right:0.01em solid;\'>Quantité<br></th></tr>';
                $last = $ouvrage->niveau;
            }

            $out .= "<tr style='".$styles[$i++]."'>\n";  
                $out .= "<td style='border-right:0.01em solid;border-left:0.01em solid;'>$ouvrage->designation</td>\n";
                $out .= "<td style='border-right:0.01em solid'>$ouvrage->qte</td>\n";
            $out .= "</tr>\n";

            if($i==2) $i=0;   
        }

        $out .= '</tbody></table></center></body></html>';

 
        
        require_once '../vendor/mpdf/mpdf/mpdf.php'; 
        $mpdf = new \mPDF('utf-8','A4','','','10','10','100','100');
        $mpdf->setAutoTopMargin = true;  

        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true; 
         
        $mpdf->SetHeader($header); 
        $mpdf->WriteHTML($out);
        $mpdf->setFooter($footer);
 
        $mpdf->Output();
    }

 
}

?> 