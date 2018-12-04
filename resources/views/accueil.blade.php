@extends('template')


@section('titre') 
    Sélection d'Etablissement
@stop



@section('contenu') 


        <style>   
            .table td{ border:none } 
        </style>
        <script>$(document).ready(function() {$('#footer').addClass('fixed-bottom');});</script>


<div style="color:white; margin-bottom:4%" >  
 

        <div id="login-container"  class="col-md-5"  >
        <div id="login-sub-container" >
                <div id="login-sub-header" style="padding:15px 20px 0 20px" > 
                        <div class="col">Selectioner l'etablissement</div> 
                </div>
        <div id="login-sub" style="padding:10;"> 
                <form action="{{url('accueil')}}" method="POST" name="formInit">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-responsive" style="color:white" >
                    <tr>
                        <td>Cercle</td>
                        <td>
                            <select id="cercle" name="cercle"  class="custom-select form-control" onchange="getCommunes();">
                                <?php 
                                    if(isset($cercles))
                                    foreach($cercles as $cercle)
                                        echo "<option value=\"$cercle->id\">$cercle->nom</option>";
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Commune</td>
                        <td>
                            <select required="" name="commune"  id="commune"  class="custom-select form-control" onchange="getEtablissements();">
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
                            <select required="" name="etablissement" id="etablissement"  class="custom-select form-control">
                            </select>
                        </td>
                    </tr> 

                    <tr><td></td><td class="">
                        <button style="width:100%;height:40px;margin:0">Entrer</button> 
                    </td></tr>
                </table>  
                </form>
                <hr>


            <div class="row"  >
                <div class="col">
                <button onclick="$('#formModifierInformations').modal();" style="color:rgb(200,200,255)">Compte</button>
                </div>
                <div class="col text-right" style="padding-right:25px">
                    <form action="{{url('/deconnexion')}}" method="POST">
                    <button type="submit" name="deconnexion" style="color:rgb(255,200,200)">Deconnexion</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </div>
            
        </div>   
        </div>
        </div>


</div>




<input type="hidden" value="{{url('')}}" id="idHost" />
<input type="hidden" name="_token" id="idToken" value="{{ csrf_token() }}">
<input type="hidden" name="_token" id="idToken2" value="{{ csrf_token() }}">

<div id='res'></div>
<script>
    function getCommunes()
    {
        $("#etablissement").html("");
        $("#commune").html("");

        var cercle = $("#cercle").val(); 

        $.ajax({
            type: "POST",
            url : $("#idHost").val() + '/accueil/commune',
            data: {cercle:cercle , _token:$("#idToken").val() },

            success: function( msg ) { 
                $("#commune").html(msg);
                $("#commune").val("");
            }
        }); 
    }

    function getEtablissements()
    {
        $("#etablissement").html("");
        var commune = $("#commune").val(); 
         

        $.ajax({
            type: "POST",
            url : $("#idHost").val() + '/accueil/etablissement',
            data: {commune:commune, niveauScolaire:$("#niveauScolaire").val() , _token:$("#idToken2").val() },

            success: function( msg ) { 
                $("#etablissement").html(msg);
                $("#etablissement").val("");
            } 
        });
    }
</script>

@stop