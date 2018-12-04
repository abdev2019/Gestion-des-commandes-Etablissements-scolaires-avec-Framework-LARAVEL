@extends('template')


@section('titre') 
    {{ 
        (Session::get('etablissement')!=null)? 
            session('etablissement')->nom." : Commande ".$commande->id : "" 
    }} 
@stop
<!--Etablissement : <?php if( isset(Session::get('etablissement')->nom) ) echo Session::get('etablissement')->nom; ?>
-->



@section('contenu') 
 


<div style="color:white; padding:20px" class="container" >  
 
       
    <br> 


    <div class="col text-right" style="padding:0">
        <h4 style="float:left;border-bottom:solid">Commande #{{$commande->id}}</h4>
        <button type="button" style="height:40px; width:100px;" 
            onclick="location.href='{{url('etablissement')}}';" >
            Accueil</span>
        </button>
        &nbsp;&nbsp;
        <button style="margin-left:0; height:40px;width:100px;" type="button" onclick="location.href='{{url('commandes')}}';">
            Commandes
        </button> 
    </div> 

    <br>
           
    <fieldset style="border: 1px aqua solid; ">
    <legend style=" width:200px; margin-left:20px" class="text-center">Informations</legend>
    <br>
    <div class="col">
            @if(Session::has('tentativeEpuise')) 
                {!! session('tentativeEpuise')  !!}
            @endif 
            <div class="row" style="padding:15px"> 
            <?php 
                if($commande->confirme==0)
                    echo "<div style='padding:15px 0 10px 0px; margin-bottom:10px' class='col alert-prdimary text-primary'><h4><i class='fa fa-exclamation-circle'></i> La commande n'est pas encore confirmé par l'administration !</h4></div>";
                else 
                    echo "<div style='padding:15px 0 10px 0px; margin-bottom:10px' class='col alert-succdess text-success'><h4><i class='fa fa-check'></i> La commande est confirmé par l'administration !</h4></div>"; 
                echo '<div class="col-md-2 text-right" style="padding:0;"><a href="'.url('commande/'.$commande->id.'/imprimer').'"  class="btn-info btn">Imprimer</a><br><small  class="text-secondary">(PDF)</small></div>';
            ?>  
            </div> 
        <br>
        
        <div class="row " >
            <div class="col">Effectué par : {{$commande->directeur->nom}}</div>
            <div class="col">&Agrave; : {{$commande->etablissement->nom}}</div> 
            <div class="col text-left"> 
                <?php echo sizeof( isset($ouvrages)?$ouvrages:0 ); ?> Ouvrages commandés
            </div>   
            <div class="col  text-right">
                <form action="{{url('')}}/administration/commande/{{$commande->id}}/modifier" method="POST">
                    @if($commande->tentative>0 &&   substr($commande->created_at,0,4) === date("Y")   ) 
                        <button>Modifier</button><br>
                        <small class="text-secondary">({{$commande->tentative}} tentatives)</small>
                    @endif  
                    <br> 
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
        <br>
    </div>
    <br> 

    <div>  
        <style>.table td+td+td+td+td+td,th+th+th+th+th+th{background-color:rgb(100,105,100)}</style>
    
    <fieldset style="border-top:solid aqua 0.01em;">
    <legend style="margin-left: 1em; padding: 12px; width:250 " class="text-center">Nombre d'éleves :</legend>
        
        <div class="col">
        <div class="row"> 
        <?php  
            if(isset($nbrsEleves)){  
                $test = false;
                foreach($nbrsEleves as $niv => $nbr)
                { 
                    if(substr($nbr,10)=="0") continue;
                    $test = true;
                    echo '  <div class="col" style="border-right:solid 0.01em">
                                    '.$nbr.' élèves
                            </div>';
                }
                if(!$test) echo "<div class='col'><h4 class='alert text-center'>Aucun élève.</h4></div>"; 
            }  
        ?>
        </div>
        </div> 
    </fieldset>
    
<br><br>

    <fieldset style="border-top:solid aqua 0.01em;">
    <legend style="margin-left: 1em; padding: 12px; width:280 " class="text-center">Ouvrages commandés</legend>
        <table id="tableInfo" class="table table-responsive table-hover" style="margin-bottom:0;color:white">
             
            <thead class="alert-primary"> 
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
                            $re .= "<td>$ouvrage->qte</td>\n";
                        $re .= "</tr>\n";
                        $total += floatval($ouvrage->qte) * $ouvrage->prix;
                    }
                }    
                echo $re; 
            ?>  
        </tbody>
        </table> 
        </fieldset>

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
        
        <script type="text/javascript" language="javascript" 
            src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js">
        </script>
        
        <script type="text/javascript" language="javascript" 
            src="//cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js">
        </script>
        
        <script type="text/javascript" language="javascript" 
            src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
        </script>
        
        <script type="text/javascript" language="javascript" 
            src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js">
        </script>
        
        <script type="text/javascript" language="javascript" 
            src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js">
        </script>
        
        <script type="text/javascript" language="javascript" 
            src="//cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js">
        </script>
        
        <script type="text/javascript" language="javascript" 
            src="//cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js">
        </script>

        <script type="text/javascript" src="{{ URL::asset('js/jquery.table2excel.js') }}"></script>
        
        <script>  
            var xlsBuilder; 
            function tableExcel()
            {
                $(document).ready( function() {
                    xlsBuilder = {
                        filename: 'Ouvrages_{{$commande->id}}',
                        sheetName: 'Ouvrages_{{$commande->id}}',
                        customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var downrows = 2;
                        var clRow = $('row', sheet);
                        var msg; 

                        //update Row
                        clRow.each(function() {
                            var attr = $(this).attr('r');
                            var ind = parseInt(attr);
                            ind = ind + downrows;
                            $(this).attr("r", ind);
                        });

                        // Update  row > c
                        $('row c ', sheet).each(function() {
                            var attr = $(this).attr('r');
                            var pre = attr.substring(0, 1);
                            var ind = parseInt(attr.substring(1, attr.length));
                            ind = ind + downrows;
                            $(this).attr("r", pre + ind);
                        });

                        function Addrow(index, data) { 
                            msg = '<row r="' + index + '">';
                            for (var i = 0; i < data.length; i++) {
                            var key = data[i].k;
                            var value = data[i].v;
                            msg += '<c t="inlineStr" r="' + key + index + '">';
                            msg += '<is>';
                            msg += '<t>' + value + '</t>';
                            msg += '</is>';
                            msg += '</c>';
                            }
                            msg += '</row>';
                            return msg;
                        }
                         

                        var r2 = Addrow(2, [{
                            k: 'A',
                            v: 'Etablissement : {{ $commande->etablissement->nom  }}'
                        }, {
                            k: 'B',
                            v: 'Commune : {{ $commande->etablissement->commune->nom  }}'
                        },{
                            k: 'E',
                            v: 'Date : <?php echo date("d/m/Y"); ?>'
                        } ]); 

                        sheet.childNodes[0].childNodes[1].innerHTML = r2 + sheet.childNodes[0].childNodes[1].innerHTML;
                         
                        },
                        exportOptions: {
                        columns: [0, 1, 2]
                        }
                    };

                    $('#tableInfo').DataTable({
                        dom:  "<'row'<'col'B><'col'l><'col'f>><'clearfix'><'alert'>" +
                                "<'row'<'col-sm-12'tr>><'clearfix'><'alert'>" +
                                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        buttons: [
                            $.extend(true, {}, xlsBuilder, {
                                extend: 'excel'
                            }) 
                        ],
                        fixedHeader: true, 
                        responsive:true,
                        "scrollY": "435px", 
                        "language": langue,
                        "order": [[ 1, "asc" ]]
                        //"ordering": false
                    });

                });
            }
            tableExcel();
        </script>

        </div> 
 

        </div>   
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






 