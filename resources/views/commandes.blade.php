@extends('template')


@section('titre') 
    {{ 
        (Session::get('etablissement')!=null)? 
            session('etablissement')->nom : "" 
    }} : Commandes
@stop



@section('contenu') 
 


<div style="color:white; padding:20px" class="container" >  
  


    <div class="col text-right" style="padding:5px 0 5px 0"> 
        <button type="button" style="height:40px; width:100px;color:" 
        onclick="location.href='{{url('etablissement')}}';" >
        Accueil</span>
        </button>  
        <br> 

        <input type="hidden" value="{{url('')}}" id='url' />  
    </div> 
 
    <h5>Commande effectué pour cette année :
        @if(session()->has('maCommande'))
            <a class='text-primary' href={{ url('/commande/'.session('maCommande')) }} >Consulter</a>
        @endif 
    </h5> <br><br>
        
    
    <fieldset style="border:solid aqua 0.01em;">
    <legend style=" width:270px; margin-left:20px; text-align:center">Anciennes commandes</legend>

    <br>

    <div> 

        <style>.table td{border:none} .table td+td+td+td+td+td+td,th+th+th+th+th+th+th{background-color:rgb(100,105,100)}</style>
        <table id="tableCmds" class="table table-responsive table-hover" style="margin-bottom:0;color:rgb(230,230,230)">
            <thead>
                <th>#Num</th>
                <th>Etablissement</th>
                <th>Directeur</th>
                <th>Date</th>
                <th></th>
            </thead>
            <tbody>
        <?php   
            $re = '';
            
            if( isset($commandes) ){
                $total = 0;
                foreach($commandes as $commande)
                {
                    $re .= "<tr>\n";
                        $re .= "<td>$commande->id</td>\n";
                        $re .= "<td>{$commande->etablissement->nom}</td>\n"; 
                        $re .= "<td>{$commande->directeur->nom}</td>\n";
                        $re .= "<td>$commande->created_at</td>\n";
                        $re .= "<td><a href='".url('commande/'.$commande->id)."' class='btn btn-info'>Details</a></td>\n"; 
                    $re .= "</tr>\n"; 
                }
            }    
            echo $re; 
        ?>  
        </tbody></table>

        <script> sortTable('#tableCmds'); </script>
        
    </div>    </div>   
    </fieldset>


    
        
        <!-- Modal 1 -->
    <div class="modal fade" id="confirmCommande" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body alert-light">  
                <div class="alert">
                    <i class="fa fa-exclamation-triangle"  aria-hidden="true"></i> 
                    <div class="col-md-10" style='display:inline-table'>
                        Vous allez envoyer une commande d'ouvrages.
                    <br>Veuillez verifier les quantités demandées.</div>
                    <br><br>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" onchange="$('#addBtnConfirmer').toggle();" name="confirme" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description"> confirmé</span>
                </label> 
                </div>
            </div>

            <div class="modal-footer">
                <form method="POST" action="{{url('commande/ajouter')}}">
                    <button name="envoyerCommande" style="display:none" id="addBtnConfirmer" type="submit" class="btn-primary btn" >
                        Confirmer
                    </button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button> 
            </div> 
        </div>
        </div>  
    </div>
@stop

















<!--  

/*
    include 'excel_reader.php';     // include the class

    // creates an object instance of the class, and read the excel file data
    $excel = new PhpExcelReader;
    $excel->setOutputEncoding("UTF-8"); 
    
    $excel->read('example.xls');
    

    // this function creates and returns a HTML table with excel rows and columns data
    // Parameter - array with excel worksheet data
    function sheetData($sheet) {
    $re = '<table class=" table-hover table table-striped table-responsive" style="color:white;">';     // starts html table
    $re .= "<thead class='alert-primary'><tr>\n"; 
        $re .= "<th>NIVEAU SCOlAIRE</th>\n"; 
        $re .= "<th>NIVEAU</th>\n"; 
        $re .= "<th>CODE</th>\n"; 
        $re .= "<th>MATIERE</th>\n"; 
        $re .= "<th>DESIGNATION DES OUVRAGES</th>\n"; 
        $re .= "<th>Quantité</th>\n";  
    $re .= "</tr></thead><tbody>\n"; 
 
    $x = 1;
    while($x <= $sheet['numRows']) {   
        if($x==1) {$x=2; continue;}
        $re .= "<tr>\n";

        $y = 1;
        while($y <= $sheet['numCols']) {
            $cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : ''; 
            $re .= " <td>$cell</td>\n";  
 
            $y++;
            if($y==6) 
            {
                if($x==1) {
                    $re .= "<td>Quantité</td>\n";
                    break;
                }
                $re .= " <td><input name='quantite' type='text' class='form-control' /></td>\n"; 
                break;
            }
        }  
        
        $re .= "</tr>\n";
            
        $x++; 
        if($x==177)break;
    }

    return $re .'</tbody></table>';     // ends and returns the html table
    }

    $nr_sheets = count($excel->sheets);       // gets the number of worksheets
    $excel_data = '';              // to store the the html tables with data of each sheet

    // traverses the number of sheets and sets html table with each sheet data in $excel_data
    for($i=0; $i<$nr_sheets; $i++) 
    {
        $excel_data .=  
        sheetData($excel->sheets[$i]) ;  
    }

    echo ($excel_data); 
*/


$x = 2;  
    while($x <= $sheet['numRows']) {
        if($x==177) return;

        try
        {
            $ouvrage = new App\Ouvrage;
            
            $idMatier = (App\Matier::where('nom', $sheet['cells'][$x][4])->get());

            $ouvrage->niveauScolaire    = $sheet['cells'][$x][1];
            $ouvrage->niveau            = intval(substr($sheet['cells'][$x][2], -1));
            $ouvrage->code              = $sheet['cells'][$x][3];
            $ouvrage->idMatier          = $idMatier[0]['id'];
            $ouvrage->designation       = $sheet['cells'][$x][5];
            $ouvrage->unite = 1; 
            $ouvrage->save();
        }
        catch(Exception $e){};
        return;
            $x++;
    }
    return; -->