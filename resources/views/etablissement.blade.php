@extends('template')


@section('titre') 
    {{ 
        (Session::get('etablissement')!=null)? 
            session('etablissement')->nom : "" 
    }}
@stop



@section('contenu')  

<div  class="container" style="color:white; padding:20px;" >  

       
    <form method="POST" name="form1" id="form1">
    <input type="hidden" name="ouvrages" id="ouvrages" value="" />
    
     

        <br>

        <div>
        <div class="row"  style="padding:15px;">
            
            <div class="col-md-7" style="padding:0"> 
            @if( session()->has('commandeEpuise') )
                <?php echo Session::get('commandeEpuise')."<br>"; ?>
            @elseif(isset($commandeEpuise)) <?php echo "$commandeEpuise<br>"; ?>
            @else
                <?php 
                   // if(session()->has('tentativeEpuise')) echo Session::get('tentativeEpuise');
                    if( !session()->has('ModificationCommande') ){ ?>
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
                <h5>Veuillez saisit les quantités des ouvrages et le fournitures, ainsi le nombre des élèves de chaque niveau au-dessous.</h5>
            @endif
            </div>
            <div class="col text-right" style="padding:5px 0 5px 0;"> 
                    <button  style="margin-left:0;" type="button" onclick="location.href='{{url('commandes')}}';">
                        Commandes
                    </button>
                    <input type="hidden" value="{{url('')}}" id='url' />  
            </div> 

        </div> 
        </div>
  
        @if(!isset($commandeEpuise))
        <br>
        <fieldset style="border: 1px aqua solid; ">
            <legend style="margin-left: 1em; padding: 12px; width:250 ">Nombre d'éleves :</legend>
            <div class="col">

                <?php if( session()->has('etablissement') && session('etablissement')->niveau=="primaire"){ ?>
                <div class="row"  id="DivnbrEleves"> 
                    <script>
                        function valider(event){
                            var key = window.event ? event.keyCode : event.which; 
                            if (event.keyCode == 8 || event.keyCode == 46
                            || event.keyCode == 37 || event.keyCode == 39) {
                                return true;
                            }
                            else if ( key < 48 || key > 57 ) {
                                return false;
                            }
                            else return true;
                        } 
                    </script>
                        <div class="col">
                            NIVEAU 1 : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('N1') }}" id='n1' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            NIVEAU 2 : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('N2') }}" id='n2' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            NIVEAU 3 : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('N3') }}" id='n3' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            NIVEAU 4 : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('N4') }}" id='n4' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            NIVEAU 5 : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('N5') }}" id='n5' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            NIVEAU 6 : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('N6') }}" id='n6' placeholder="0" type="text" class="form-control">
                        </div>
                </div>
                <?php }else{ ?>
                    <div class="row"> 
                        <div class="col">
                            Première : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('PREMIER') or '0' }}" id='n1' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            Deuxième : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('DEUXIEME') or '0' }}" id='n2' placeholder="0" type="text" class="form-control">
                        </div>
                        <div class="col">
                            Troisième : <input onkeypress="return valider(event);" onblur="setNbrEleves();" value="{{Session::get('TROISIEME') or '0' }}" id='n3' placeholder="0" type="text" class="form-control">
                        </div> 
                    </div>
                <?php } ?>
                    
                
            </div>
            <br>
       
        <br><br>


        <fieldset style="border-top: 1px aqua solid; ">
            <legend style="margin-left: 1em; padding: 0.2em 0.8em; width:200 ">Les ouvrages</legend>


            <div id="menu">   
            <div class="row container"  style="padding-right:0">

                <!-- niveau -->  
                <div class="col-md-3" > 
                    Niveau : 
                    <?php 
                        $n = 0; $m=0;
                        if(isset($_POST['niveau'])) $n=$_POST['niveau'];
                        if(isset($_POST['matiere'])) $m=$_POST['matiere'];
                    ?>
                    <select id="niveau" class="custom-select" name="niveau" onchange='filtrer();' >
                        <option  value="0">TOUS</option>
                        <option  value="1" <?php if($n==1) echo "selected" ?>>NIVEAU 1</option>
                        <option  value="2" <?php if($n==2) echo "selected" ?>>NIVEAU 2</option>
                        <option  value="3" <?php if($n==3) echo "selected" ?>>NIVEAU 3</option>
                        <option  value="4" <?php if($n==4) echo "selected" ?>>NIVEAU 4</option>
                        <option  value="5" <?php if($n==5) echo "selected" ?>>NIVEAU 5</option>
                        <option  value="6" <?php if($n==6) echo "selected" ?>>NIVEAU 6</option>  
                    </select> 
                </div>  

                <!-- Matiere -->  
                <div class="col-md-3"  style="padding:5px 0 5px 0">
                    Matière :  
                    <select class="custom-select" id="matiere" name="matiere" onchange='filtrer()' >
                        <option value="0">TOUS</option>
                        <?php
                            if(isset($matiers))
                            foreach($matiers as $k=>$matier)  
                                echo "<option  value=\"$k\" ".( ($m==$k)? "selected": "" ).">$matier</option>";
                        ?>   
                    </select> 
                </div> 

                <div class="col-md-3"  style="padding:5px 0 5px 0">
                    <button style="margin-left:0" type="button" onclick="filtrer();">
                        Actualiser
                    </button>
                    <input type="hidden" value="{{url('')}}" id='url' />  
                </div> 
                
                <div class="col text-right" style="padding:0">
                    <button type="button"   style="color:rgb(255,190,190)" onclick="location.href='{{url('panier/effacer')}}';" >
                    Effacer
                    </button>

                    <button type="button" style="height:40px; width:100px;color:rgb(100,255,200)" onclick="location.href='{{url('commandeActuel')}}';" >
                    Apper&ccedil;u <span id="nbrOuvrages" class="badge badge-info" style="padding:5px"> <?php echo sizeof(session()->get('ouvrages')); ?> </span>
                    </button>
                </div> 

            </div>  
            </div>
            <br>    
            <div id="resultFiltrage"></div>   

        </fieldset>
        </fieldset>
        @else <script>$(document).ready(function() {$('#footer').addClass('fixed-bottom');});</script>
        @endif
        </form>
        
        </div> 
</div>   

 
    <script>  
        var table1;  
        
        $(document).ready(function() { 
            table1 = sortTable('#tableListeOuvrages'); 

            <?php if(session()->has('ModificationCommande')){ ?>
                filtrer(3); 
            <?php }else{ ?>
                filtrer(1); 
            <?php } ?>   
        } ); 
           
        function filtrer(col=1)
        { 
            var data = table1.$('input, select').serialize();  
            $("#ouvrages").val( $("#ouvrages").val()+" "+data ); 

            var matiere = $("#matiere").val();
            var niveau  = $("#niveau").val();
            var host    = $("#url").val();
            
            var url = host + '/accueil/niveau/' + niveau + '/matiere/' + matiere;

            $.ajax({
                type: "POST",
                url : url,
                data: {niveau:niveau, matiere:matiere, _token:$("#token").val()},

                success: function( msg ) { 
                    $('#resultFiltrage').html(msg); 
                    setTimeout( function(){   sortTable('#tableListeOuvrages',col);  } , 0 );
                },

                error:function( xhr, status ) {
                    alert(xhr.responseText);
                    $('#resultFiltrage').html(xhr.responseText);  
                }
            }); 
        }
        

        function addQte(id, add)
        { 
            var cont = "";

            if(add==1){
                $("#nbrOuvrages").html(eval( $("#nbrOuvrages").html()+"+1" )); 
                cont = 
                "<div class=\"input-group\">\
                <input  onkeypress=\"return valider(event);\" placeholder=0 name='_qo"+id+"' type=\"text\" class=\"form-control form-control-sm\" style=\"width:10%\" onblur=\"gererSession(1,"+id+",this.value)\" autofocus>\
                <div class=\"input-group-addon btn-secondary btn-sm\" onclick='addQte("+id+",0); gererSession(0,"+id+",0);' >\
                <span class=\"fa fa-close\"></span></div></div>";
                setTimeout(function() {
                    $("input[name=_qo"+id+"]").focus(); 
                }, 0);
            }
            else{
                cont = "<i class=\"fa fa-plus btn-info btn-sm\" onclick='addQte("+id+",1);' ></i>";
                $("#nbrOuvrages").html(eval( $("#nbrOuvrages").html()+"-1" ));
            }

            $("#td"+id).html(cont);
        }

        function gererSession(x, id, v)
        {
            if(x==1 && (v=="" || v==0) ) { addQte(id, 0); return; }
            var url = $("#url").val();

            if(x==1)
                url += "/ouvrage/ajouterAuSession";
            else 
                url += "/ouvrage/retirerDeSession";

            $.ajax({
                type: "POST",
                url : url,
                data: {id:id , val:v, _token:$("#token").val()},

                success: function( msg ) { 
                },

                error:function( xhr, status ) {
                }
            });
        } 
        

        function setNbrEleves()
        {  
            data = { N1:$('#n1').val(), N2:$('#n2').val(), 
                     N3:$('#n3').val(), N4:$('#n4').val(), 
                     N5:$('#n5').val(), N6:$('#n6').val(), 
                     _token:$("#token").val() 
            };
 
            url = $('#host').val()+'/eleves/setNombre';
            
            $.ajax({
                type: "POST",
                url : url,
                data: data,

                success: function( msg ) {  
                },

                error:function( xhr, status ) { 
                }
            });
        } 

        
            

    </script>
 

@stop















