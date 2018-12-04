@extends('template')


@section('titre') 
@stop 



@section('contenu') 
 


<div style="color:white; padding:20px" class="container" >  
    
<style>#liens a{margin-right:5px; margin-bottom:20px; color:white}</style> 

<div class="col"  style="padding:5px 5px 5px 5px; box-shadow:0px 10px 10px 0px rgb(0,0,50)">
<div class="row"> 
            <div class="col-md-10" id="liens">
                <div class=" row" > 
                <div class="col"><a href="{{url('administration/etablissements')}}" class="btn-primary btn">
                Etablissements</a> 
                
                <a href="{{url('administration/directeurs')}}" class="btn-primary btn">
                Directeurs</a>
                
                <a href="{{url('administration/ouvrages')}}" class="btn-primary btn">
                Ouvrages</a>
                
                <a href="{{url('administration/cercles')}}" class="btn-primary btn">
                Cercles</a>
               
                <a href="{{url('administration/communes')}}" class="btn-primary btn">
                Communes</a></div> 
                </div>
 
                <div class="row">
                    <div class="col"><a href="{{url('administration/commandes')}}" class="btn-primary btn">
                    Commandes
                    </a> 
                    <a href="{{url('administration/commandes/statistiques')}}" class="btn-primary btn">
                    Statistiques
                    </a></div>
                </div> 
            </div>

            <div class="col text-right" > 
                <!-- deconnexion -->
                <form action="{{url('/deconnexion')}}" method="POST" >
                    <button type="submit" name="deconnexion" style="float:rdight; height:40px;color:rgb(255,200,200)">Deconnexion</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form> 
                <button onclick="$('#formModifierInformations').modal();">Compte</button>
            </div>
            
   
</div> 
  
</div>
    <br><br>
 
 
     
    <br>
    <fieldset style="border:solid 0.01em;">
    <legend style=" width:200px; margin-left:20px" class="text-center">Commande #{{$commande->id}}</legend>
    <br>

    <div class="col alert-primary" style="padding:10px"> 
        <h5>
        <div class="row" > 
            
            <?php 
                if($commande->confirme==0)
                { 
                    echo "<div class='col'>La commande n'est pas encore confirmé ! </div>"; 
            ?>
                
                <div class="col text-right">
                    <button onclick="$('#form_ConfirmationCommande').modal(); $('#operation').val('confirmer')">Confirmer</button>
                </div>  

            
            <?php }  else echo "<div class='col'>La commande est dèja confirmé !</div>"; ?>
       
        </div>
        </h5>
    </div>

    <br>

    <div class="col"> 
        <div class="row " >
            <div class="col">Effectué par : {{$commande->directeur->nom or ''}}</div>
            <div class="col">&Agrave; : {{$commande->etablissement->nom or ''}}</div>
            <div class="col"> 
                <?php echo sizeof( isset($ouvrages)?$ouvrages:0 ); ?> Ouvrages commandés
            </div>  
            <div class="col text-right">
                <button onclick="$('#form_ConfirmationCommande').modal(); $('#operation').val('supprimer')" style="color:rgb(255,200,200)">Supprimer</button><br><br> 
                <button onclick="location.href='{{url('commande/'.$commande->id.'/imprimer')}}'" >Imprimer</button>
                <br><small  class="text-secondary">(PDF)</small>
        </div>
    </div>
    <br> 

    <div>  
        <style>.table td+td+td+td+td+td,th+th+th+th+th+th{background-color:rgb(100,105,100)}</style>
        
        <table id="tableInfo" class="table table-responsive table-hover" style="margin-bottom:0;color:white">
             
            <thead class="alert-primary"> 
                <th>Designation</th>
                <th>Niveau</th>
                <th>Unite</th>
                <th>Prix (MAD)</th>
                <th>Quantité</th>
                <th  style="color:white">TVA(%)</th> 
                <th style="color:white">Total (MAD)</th>
                <th style="color:white">TTC</th> 
            </thead> 
 
            <tbody>
            <?php   
                $re = '';
                
                if( isset($ouvrages) ){ 
                    foreach($ouvrages as $ouvrage)
                    {
                        $re .= "<tr>\n";  
                            $re .= "<td>$ouvrage->designation</td>\n";
                            $re .= "<td>NIVEAU $ouvrage->niveau</td>\n";
                            $re .= "<td>$ouvrage->unite</td>\n";
                            $re .= "<td>$ouvrage->prix</td>\n";
                            $re .= "<td>$ouvrage->qte</td>\n";
                            $re .= "<td>$ouvrage->tva</td>\n"; 
                            $t = floatval($ouvrage->unite*floatval($ouvrage->qte) * $ouvrage->prix);
                            $tc = $t + ($t*$ouvrage->tva)/100.0;
                            $re .= "<td class='text-right'>$t</td>\n"; 
                            $re .= "<td>$tc</td>\n"; 
                        $re .= "</tr>\n"; 
                    }
                }    
                echo $re; 
            ?>  
        </tbody>
        </table> 


    <!-- Modal suppression -->
    <div class="modal fade" id="form_ConfirmationCommande" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
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

                    <input type="hidden"  id="idCommande" value="{{ $commande->id }}" /> 
                    <input type="hidden"  id="operation" /> 
                    
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" onchange="$('#addBtnConfirmerCommande').toggle();" name="confirme" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> confirmé</span>
                    </label> 
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:"> 
                <button style="display:none;width:100px" onclick="confirmer();" id="addBtnConfirmerCommande">Confirmer</button>
                <button style="width:100px" type="button"  data-dismiss="modal">Annuler</button>
            </div> 
        </div>
    </div>  
    </div>


    <div id="test"></div>
    <input type="hidden" value="{{ csrf_token() }}" id="token" /> 
    <input type="hidden" id="url" value="{{url('administration')}}" />

    </div> 


    </fieldset>

    <h4 class="col alert row" style="border-top:solid;background-color:rgb(100,105,100);border-radius:0;margin:0">
            <div class="col">Total HT :</div>
            <div class="col text-right">{{$commande->prixTotalHT}} DHs</div>
        </h4>
        <h4 class="col alert row" style="border-top:solid;background-color:rgb(100,105,100);border-radius:0;margin:0">
            <div class="col">Total TTC :</div>
            <div class="col text-right">{{$commande->prixTTC}} DHs</div>
        </h4>
    </div>

     
    <script>
        function confirmer()
        {
            
            $.ajax({
                type: "POST",

                url : $("#url").val()+'/commande/'+$("#operation").val(),

                data: {
                    id:     $("#idCommande").val(),
                    _token: $("#token").val()
                },

                success: function( msg ) {  
                    alert('Commande '+$("#idCommande").val()+' '+$("#operation").val()+' !');
                    location.href = $("#url").val()+'/commandes';
                },

                error:function( xhr, status ) { 
                    $("#test").html(xhr.responseText);
                }
            }); 
        }
    </script>

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
                            v: 'Etablissement : {{ $commande->etablissement->nom or "" }}'
                        }, {
                            k: 'B',
                            v: 'Commune : {{ $commande->etablissement->commune->nom or "" }}'
                        },{
                            k: 'E',
                            v: 'Date : <?php echo $commande->created_at; ?>'
                        } ]);

                        var r3 = Addrow( clRow.length+3 , [{
                            k: 'A',
                            v: 'Total : '
                        }, {
                            k: 'F',
                            v: '{{$commande->prixTotalHT}} MAD'
                        } ]);

                         

                        sheet.childNodes[0].childNodes[1].innerHTML = r2 + sheet.childNodes[0].childNodes[1].innerHTML + r3;
                         
                        },
                        exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
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
                    });

                });
            }
            tableExcel();
        </script>

       
@stop













 