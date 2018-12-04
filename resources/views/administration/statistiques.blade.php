@extends('template')


@section('titre') 
    Statistiques
@stop



@section('contenu') 
 
<style>
    a{margin-right:5px; margin-bottom:20px;}
    .sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
    }
</style>

<script src="{{ URL::asset('js/stickyfill.min.js') }}"></script>

 

<div style="color:white; padding:20px" class="container"  > 
    <div class="col"  style="padding:5px 5px 5px 5px; box-shadow:0px 10px 10px 0px rgb(0,0,50)">
        <div class="row"> 
                    <div class="col-md-10">
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
                    

                    <style>button{height:40px} .col a{color:white}</style>  
        </div>  
    </div>

    <br>
    <table class="table table-responsive" style="color:white"  id="table1" border=1>
    
        <thead>
            <tr>
                <th>Nombre d'établissements</th>
                <th>Nombre de communes</th>
                <th>Nombre de cercles</th> 
            </tr>
        </thead>
    
        <tbody>
            <tr border=1>
                <th>{{ ( $infos['etablissements'] ) }}</th>
                <th>{{ ( $infos['communes'] ) }}</th>
                <th>{{ count( $cercles ) }}</thtd> 
            </tr>
        </tbody>

    </table>
    <br><br>  
 
    <div style="float:right; color:white;">
            
    </div>
    <h2 class="sticky" style="color: rgb(255,255,255);
                        background:rgb(50,50,205); z-index:100"   
    >
        <a class="navbar-toggler btn" 
                style="margin:0;cursor:pointer"
                type="button" 
                data-toggle="collapse" 
                data-target="#blocMenu" 
                aria-controls="navbarToggleExternalContent" 
                aria-expanded="false" 
                aria-label="Toggle navigation"
                onclick="$('#blocStats').toggleClass(' col-md-8');">
        <span class="fa fa-1x fa-list" style="color:rgb(100,255,100)"></span>
        </a> 
        Statistiques
    </h2> 


<div style="margin-bottom:100px;color:white">

  
<div class="col" >
    <div class="row"> 
        <div class="col-md-4 collapse" style="padding:0;padding-right:20px;" id="blocMenu"  >   
            <div class="sticky" style="top:50px" > 
            <table class="table" style="color:white;margin-top:0;" id="menuFilt">
                <thead>
                <tr>
                    <th  style="border:none; vertical-align:top" ><span>Année univ</span></th>
                    <th  style="border:none"><select class="custom-select col" id="annee"  
                         onchange='filtrerS( document.getElementById("cercle") ); filtrerC( document.getElementById("communes") );'>
                        <?php 
                            $thisYear = intval( date("Y") ); 
                            for($i=2012;$i<$thisYear;$i++  )
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        ?>
                    </select></th>
                </tr>
                
                <tr>
                    <th  style="border:none; vertical-align:top"><label>Cercle :</label></th>
                    <th  style="border:none"><select class="custom-select col"  onchange="filtrerS(this);getCommunes();" id="cercle">
                        <?php
                            if(isset($cercles))
                            foreach($cercles as $cercle) 
                                echo "<option value='$cercle->id'>$cercle->nom</option>"; 
                        ?>
                    </select></th>
                </tr>

                <tr>
                    <td>Commune</td>
                    <td>
                        <select required="" name="commune"  id="communes"  
                                class="custom-select form-control" 
                                onchange="getEtablissements(); filtrerC(this);">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Niveau</td>
                    <td>
                        <select required="" name="niveauScolaire"  id="niveauScolaire"  class="custom-select form-control"  onchange="getEtablissements();" >
                            <option value="primaire">Primaire</option>
                            <option value="college">College</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>&Eacute;tablissement</td>
                    <td>
                        <select required="" name="etablissement" id="etablissement"  class="custom-select form-control" onchange="getInfosEtab(this);">
                        </select>
                    </td>
                </tr>  

                </thead>
            </table> 
            <div id="nomEtacb"></div>
            <div id="infosEtab"></div>
            </div>
        </div> 
    
        <div class="col-md-8 col" style="padding:0;" id='blocStats'>
            
            <!--<table class="table table-responsive"  style="color:white" border=1>
            
                <thead>
                    <tr>  
                        <th>&Eacute;tablissements</th>
                        <th>Nombre de commandes</th>
                        <th>Ouvrages commandés</th>
                        <th>Montant TOTAL</th> 
                    </tr>
                </thead>
            
                <tbody>
                    <tr> 
                        <th>{{ ( $infos['commandes'] ) }}</th>
                        <th>0</th>
                        <th>0</th> 
                        <th>0 MAD</th>
                    </tr>
                </tbody>

            </table> 
            <br>-->

            <div  >
                <fieldset style="border:solid rgb(100,100,255); padding:10px;" >
                    <legend style=";width:230px; margin-left:20px" class="text-center">
                        % Commande/cercle
                    </legend>  
                        <h5>Cercle: <span id="nomCercle">Sélectionner une cercle.</span></h5>  
                    <div id="chart-1" class="text-center"></div>
                </fieldset>
            </div><br>

            <div >
                <fieldset style="border:solid rgb(100,100,255); padding:10px" class="col">
                    <legend style=";width:280px; margin-left:20px" class="text-center">
                        % Commande/commune
                    </legend>  
                        <h5>Commune : <span id="nomCommune">Sélectionner une etablissement.</span> </h5> 
                    <div id="chart-2" class="text-center"></div>
                </fieldset>
            </div>
            <br><br><br>
        </div>   
    </div> 
</div>

<br>





 



 
<script type="text/javascript" src="{{ url('js/fusioncharts/fusioncharts.js') }}"></script>
<script>
    
    function filtrerS(e)
    {  
        $("#nomCercle").html( $('#cercle option:selected').text() );

        var url    = $("#host").val()+'/administration/commandes/statistiques'; 
        
        $.ajax({
            type: "POST",
            url : url,  
            data: { cercle:e.value, annee:$('#annee').val(), _token:$("#token").val() },
            success: function( msg ) 
            {     
                var json = JSON.parse(msg);
                 
                    var revenueChart = new FusionCharts({
                        type: 'doughnut2d',/*column2ddoughnut2d*/
                        renderAt: 'chart-1',
                        width: '100%',
                        height: '400',
                        dataFormat: 'json',
                        dataSource: json.lastyear
                    }).render();   
                //else $("#chart-1").html("Aucune Etablissement n'a effectuée la commande.");
            },

            error:function( xhr, status ) { 
                $("#chart-1").html(xhr.responseText);
            }
        }); 
    }
 
    function filtrerC(e)
    {  
        $("#nomCommune").html( $('#communes option:selected').text() );

        var url    = $("#host").val()+'/administration/commandes/statistiques/commune'; 
        
        $.ajax({
            type: "POST",
            url : url,  
            data: { commune:e.value, annee:$('#annee').val(), _token:$("#token").val() },
            success: function( msg ) 
            {     
                var json = JSON.parse(msg);

                var revenueChart = new FusionCharts({
                        type: 'doughnut2d',/*column2ddoughnut2d*/
                        renderAt: 'chart-2',
                        width: '100%',
                        height: '400',
                        dataFormat: 'json',
                        dataSource: json.lastyear
                    }).render();  
                
                //else $("#chart-2").html("Aucune Etablissement n'a effectuée la commande.");
            },

            error:function( xhr, status ) { 
                $("#chart-2").html(xhr.responseText);
            }
        }); 
    }

    function getInfosEtab(e)
    {   
        var url    = $("#host").val()+'/administration/commandes/statistiques/etablissement'; 
         
        $.ajax({
            type: "POST",
            url : url,  
            data: { etab:e.value, annee:$('#annee').val(), _token:$("#token").val() },
            success: function( msg ) 
            {     
                $("#infosEtab").html(msg);
                $("#nomEtab").html( "<b>"+$('#etablissement option:selected').text()+"</b>" );
            },

            error:function( xhr, status ) { 
                $("#infosEtab").html(xhr.responseText);
            }
        }); 
    } 
    
    function getCommunes()
    {
        $("#etablissement").html("");
        $("#commune").html("");

        var cercle = $("#cercle").val(); 

        $.ajax({
            type: "POST",
            url : $("#host").val() + '/accueil/commune',
            data: {cercle:cercle , _token:$("#token").val() },

            success: function( msg ) { 
                $("#communes").html(msg);   
                $("#communes").val("35"); 
                filtrerC( document.getElementById("communes") );
                getEtablissements();
            },
            error : function(xhr, status){ 
            }
        }); 
    }

    function getEtablissements()
    {
        $("#etablissement").html("");
        var commune = $("#communes").val(); 
         

        $.ajax({
            type: "POST",
            url : $("#host").val() + '/accueil/etablissement',
            data: {commune:commune, niveauScolaire:$("#niveauScolaire").val() , _token:$("#token").val() },

            success: function( msg ) { 
                $("#etablissement").html(msg);
                $("#etablissement").val('29');
                getInfosEtab( document.getElementById("etablissement") );
            } 
        });
    }

    $(document).ready(function() { 
        $('#annee').val({{date("Y")}});

        $("#cercle").val("2"); 
        getCommunes();     
        filtrerS( document.getElementById("cercle") );

        $('#blocMenu').collapse({ toggle: true });
    });

</script>

    
 
</div>

</div>
@stop