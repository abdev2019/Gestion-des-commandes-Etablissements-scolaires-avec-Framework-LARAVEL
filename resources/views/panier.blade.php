@extends('template')


@section('titre') 
    {{ 
        (Session::get('etablissement')!=null)? 
            session('etablissement')->nom : "" 
    }}
@stop



@section('contenu') 
 


<div style="color:white; padding:20px" class="container" >  
    
    <br> 

    <div class="row">
        <div class="col-md-5">
                <?php if( !session()->has('ModificationCommande') ){ ?>
                <h4>Nouvelle commande :</h4>
                <?php }else{ ?>
                    <h4 class="text-primary"><u>
                        Mis à jour du commande {{ Session::get('ModificationCommande') }}</u>
                        <span type="button" class="btn close"  
                            style="border:solid 0.01em white; border-radius:100%;background:orange;width:30px; height:30px"
                            onclick="location.href='{{url('panier/effacer')}}';">
                            <center style="position:relative; top:-5"> &nbsp;&times;</center>
                        </span> 
                </h4>
                <?php } ?>
        </div>
        <div class="col text-right" style=""> 
            <button  style="margin-left:0;height:40px;" type="button" onclick="location.href='{{url('commandes')}}';">
            Commandes
            </button> 
            <button type="button" style="height:40px; width:100px;color:" 
            onclick="location.href='{{url('etablissement')}}';" >
            Accueil
            </button> 
        </div> 
    </div>
 
    <br>
        <fieldset style="border: 1px aqua solid; ">
        <legend style="margin-left: 1em; padding: 12px; width:220px ">Nombre d'éleves :</legend>
            
            <div class="col">
            <div class="row"> 
            <?php  
                if(isset($nbrsEleves))
                {
                    if(count($nbrsEleves)==0)
                        echo "<div class='col'><h4 class='alert text-center'>Aucun élève.</h4></div>";
                    else foreach($nbrsEleves as $niv => $nbr){
                        echo '  <div class="col text-center" style="border-right:solid 0.01em">
                                Niveau '.$niv.' : '.$nbr.' élèves
                                </div>';
                    } 
                } 
                
            ?>
            </div>
            </div>
            <br>
        
    <br>
    
    <fieldset style="border-top:solid aqua 0.01em;">
    <legend style=" width:270px; margin-left:20px;text-align:center">Ouvrages selectionnés</legend>

    <div class="col"> 
        <div class="row " >
            <h6 class="col text-left"><a class="alert-info btn" style="color:black">
                 <?php echo sizeof( isset($ouvrages)?$ouvrages:0 ); ?> Ouvrages selectionnés
            </a></h6>  
            <div class=" col text-right">
                <button type="button" style="height:40px; width:100px;color:" 
                onclick="location.href='{{url('panier/effacer')}}';" >
                Effacer</span>
                </button>

                <button type="button" style="height:40px; width:100px;color:rgb(100,255,200)" 
                data-toggle="modal" data-target="#confirmCommande" onclick="document.form1.action='{{url('ouvrage/commande')}}';" >
                Confirmer</span>
                </button> 
            </div>
        </div>
    </div>
    <br> 

    <div>   
        <style> .table td+td+td+td+td+td,th+th+th+th+th+th{background-color:rgb(100,105,100)}</style>
        <table id="tableInfo" class="table table-responsive table-hover" style="margin-bottom:0;color:rgb(230,230,230); ">
            <thead style="font-weight:bold" CLASS="alert-primary"> 
            <th>Designation</th>
            <th>Niveau</th>
            <th>Quantité</th>
            </thead>
            <tbody>
        <?php   
            $re = '';
            
            if( isset($ouvrages) ){
                $total = 0;
                foreach($ouvrages as $ouvrage)
                {
                    $re .= "<tr>\n"; 
                        $re .= "<td>$ouvrage->designation</td>\n";
                        $re .= "<td>".( ($ouvrage->niveau==0)?"Fournitures":"NIVEAU $ouvrage->niveau" )."</td>\n"; 
                        //$re .= "<td>$ouvrage->unite</td>\n";
                        //$re .= "<td>$ouvrage->prix</td>\n";
                        $re .= "<td>$ouvrage->qte</td>\n";
                        //$re .= "<td>".( floatval($ouvrage->qte) * $ouvrage->prix )."</td>\n"; 
                    $re .= "</tr>\n";
                    $total += floatval($ouvrage->qte) * $ouvrage->prix;
                }
            }    
            echo $re; 
        ?>  
        </tbody></table>
        <script> sortTable('#tableInfo'); </script>
        </div>   </div>   

    </fieldset>
    </fieldset>


    
        
    <!-- Modal 1 -->
    <div class="modal fade" id="confirmCommande" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary">  
                <div class="alert">
                    <i class="fa fa-exclamation-triangle"  aria-hidden="true"></i> 
                    <div class="col-md-10" style='display:inline-table'>
                        Vous allez envoyer une commande d'ouvrages.
                    <br>Veuillez verifier les quantités demandées.<br>Une fois vous confirmez, vous aurez seulement trois fois la possibilité de modifier la commande.</div>
                    
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:0">
                <form method="POST" action="{{url('commande/ajouter')}}">
                    <button name="envoyerCommande" style="display" id="addBtnConfirmer" type="submit" class="btn-primary btn" >
                        Confirmer
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>  
            </div> 
        </div>
        </div>  
    </div> 
    <input type="hidden" value="{{url('')}}" id='url' /> 
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