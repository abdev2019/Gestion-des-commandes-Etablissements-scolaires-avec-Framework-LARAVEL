@extends('template')


@section('titre') 
    Administration
@stop



@section('contenu') 
 

<style>.container .btn{margin-bottom:10px}</style>
<div style="color:white; padding:20px" class="container"  >  
 
<style>a{margin-right:5px; margin-bottom:20px;}</style> 
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
    <br><br>

 
    
    <fieldset style="border:solid 0.01em;">
    <legend style="width:200px; display: table; margin-left:20px" class="text-center"  > <?php if(isset($table)) echo $table; ?></legend>
    <div> 

        <table id="tableDynamique" class="table table-responsive table-hover" style="margin-bottom:0;color:rgb(230,230,230)">
            <thead>
                <?php if(isset($head)) echo $head; ?>
            </thead>
            <tbody>
        <?php   
            if(isset($data)) echo $data;
        ?>  
        </tbody></table>

        <script> sortTable('#tableDynamique',0); </script>
        
    </div>   
    </fieldset> 

    </div>



    <!-- Modal mis à jour etablissement -->
    <div class="modal fade" id="form_modificationEtablissement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="titreFormEtablissement">Mise à jour</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary">  
                <div class="alert"> 
                    <div id="resultModifierEtablissement"></div>
                    <table class="table table-responsive">
                        <tr><td>Nom</td> <td><input type="text" id="nomEtablissement" class="form-control" /></td></tr>
                        <tr>
                            <td>Niveau scolaire</td>    
                            <td>
                                <select id="niveauScolaire"  class="custom-select form-control">
                                    <option  value="primaire">primaire</option>
                                    <option  value="college" >college</option> 
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Commune</td>
                            <td>
                                <select id="commune" class="custom-select form-control">
                                    <?php 
                                        if(isset($communes))
                                        foreach($communes as $commune)
                                            echo "<option value='$commune->id'>$commune->nom</option>";
                                    ?>
                                </select>
                            </td>
                        </tr> 
                    </table> 

                    <input type="hidden"  id="idEtablissement" />


                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" onchange="$('#addBtnModifier').toggle();" name="confirme" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> confirmé</span>
                    </label> 
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:"> 
                <button style="display:none;width:100px" onclick="modifierEtablissement();" id="addBtnModifier">Modifier</button>
                <button style="width:100px" type="button"  data-dismiss="modal">Annuler</button>
            </div> 
        </div>
        </div>  
    </div>


    <!-- Modal mis à jour ouvrage -->
    <div class="modal fade" id="form_modificationOuvrage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="titreFormOuvrage">Mise à jour</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary">  
                <div class="alert"> 
                    <div id="resultModifierOuvrage"></div>
                    <table class="table table-responsive"> 
                        <tr> 
                            <td>Designation</td> 
                            <td><input type="text" id="designation" class="form-control" /></td>
                        </tr> 

                        <tr>
                            <td>Matiere</td>
                            <td>
                                <select id="matiere" class="custom-select form-control">
                                    <?php 
                                        if(isset($matieres))
                                        foreach($matieres as $matiere)
                                            echo "<option value='$matiere->id'>$matiere->nom</option>";
                                    ?>
                                </select>
                            </td>
                        </tr> 

                        <tr>
                            <td>Niveau scolaire</td>    
                            <td>
                                <select id="niveauScolaireOuvrage"  class="custom-select form-control">
                                    <option  value="primaire">primaire</option>
                                    <option  value="college" >college</option> 
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Niveau</td>    
                            <td>
                                <select id="niveauOuvrage"  class="custom-select form-control">
                                    <option  value="1" >NIVEAU 1</option>
                                    <option  value="2" >NIVEAU 2</option>
                                    <option  value="3" >NIVEAU 3</option>
                                    <option  value="4" >NIVEAU 4</option>
                                    <option  value="5" >NIVEAU 5</option>
                                    <option  value="6" >NIVEAU 6</option> 
                                    <option  value="0" >TOUS</option>
                                </select>
                            </td>
                        </tr>

                        <tr> 
                            <td>Unite</td> 
                            <td><input type="text" id="unite" class="form-control" /></td>
                        </tr>
                        <tr> 
                            <td>Prix</td> 
                            <td><input type="text" id="prix" class="form-control" /></td>
                        </tr>
                        <tr> 
                            <td>TVA</td> 
                            <td><input type="text" id="tva" class="form-control" /></td>
                        </tr>
                        <tr> 
                            <td>Code</td> 
                            <td><input type="text" id="code" class="form-control" /></td>
                        </tr>
                    </table> 

                    <input type="hidden"  id="idOuvrage" />

                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" onchange="$('#addBtnModifierOuvrage').toggle();" name="confirme" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> confirmé</span>
                    </label> 
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:">

                    <button style="display:none;width:100px" onclick="modifierOuvrage();" id="addBtnModifierOuvrage">Modifier</button>
                    <button style="width:100px" type="button"  data-dismiss="modal" >Annuler</button>
                
            </div> 
        </div>
        </div>  
    </div>



    <!-- Modal suppression -->
    <div class="modal fade" id="form_Supprimer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Supression</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary" style="padding:0">  
                <div class="alert">

                    <input type="hidden"  id="idObjet" />
                    <input type="hidden"  id="nomObjet" /> 
                    Vous allez supprimer <span id="nomObjet2"></span>, S&ucirc;re ?

                    <!-- <label class="custom-control custom-checkbox">
                        <input type="checkbox" onchange="$('#addBtnSupprimer').toggle();" name="confirme" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> confirmé</span>
                    </label> -->
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:"> 
                <button style=";width:100px" onclick="supprimer();" id="addBtnSupprimer">Confirmer</button>
                <button style="width:100px" type="button"  data-dismiss="modal">Annuler</button>
            </div> 
        </div>
        </div>  
    </div>

    <!-- Modal confirmer utilisateur -->
    <div class="modal fade" id="form_confirmerUtilisateur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation du compte</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary">  
                <div class="alert">

                    <input type="hidden"  id="idUtilisateur" /> 
                    <input type="hidden"  id="valConfirmation" />  

                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" onchange="$('#addBtnConfirmerUser').toggle();" name="confirme" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> confirmé</span>
                    </label> 
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:"> 
                <button style="display:none;width:100px" onclick="confirmerUtilisateur();" id="addBtnConfirmerUser">Confirmer</button>
                <button style="width:100px" type="button"  data-dismiss="modal">Annuler</button>
            </div> 
        </div>
        </div>  
    </div>


    <!-- Modifier Cercle + Commune -->
    <div class="modal fade" id="form_MACC" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary">  
                <div class="alert"> 
                    <div id="resultModifierOuvrage"></div>
                    <table class="table table-responsive"> 
                        <tr> 
                            <td>Nom</td> 
                            <td><input type="text" id="nomCC" class="form-control" /></td>
                        </tr> 

                        <tr id="trCercle" style="display:none">
                            <td>Cercle</td>
                            <td>
                                <select id="cercleCommune" class="custom-select form-control">
                                    <?php 
                                        if(isset($cercles))
                                        foreach($cercles as $cercle)
                                            echo "<option value='$cercle->id'>$cercle->nom</option>";
                                    ?>
                                </select>
                            </td>
                        </tr> 
   
                    </table> 

                    <input type="hidden"  id="idCC" />

                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" onchange="$('#addBtnMACC').toggle();" name="confirme" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"> confirmé</span>
                    </label> 
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:">

                    <button style="display:none;width:100px" onclick="gererCC();" id="addBtnMACC">Modifier</button>
                    <button style="width:100px" type="button"  data-dismiss="modal">Annuler</button>
                
            </div> 
        </div>
        </div>  
    </div>





    <div id="test"></div>
    <input type="hidden" id="url" value="{{url('administration')}}" /> 

    <script>

        // etablissement
        function formModifierEtablissement(id, nom, nScolaire, commune)
        { 
            $("#resultModifierEtablissement").html(""); 
            $("#nomEtablissement").val(nom); 
            $("#idEtablissement").val(id); 
            $("#niveauScolaire").val(nScolaire);
            $("#commune").val(commune);
        } 

        function modifierEtablissement()
        {     
            var url = $("#url").val()+'/etablissement';
            
            if( $("#idEtablissement").val() != 0 ) 
                 url += '/modifier';
            else url += '/ajouter' ;

            $.ajax({
                type: "POST",

                url : url,

                data: {
                    id:$("#idEtablissement").val(),
                    nom:$("#nomEtablissement").val(), 
                    niveau:$("#niveauScolaire").val(), 
                    commune : $("#commune").val(),
                    _token:$("#token").val()
                },

                success: function( msg ) { 
                    $('#resultModifierEtablissement').html(msg);  
                },

                error:function( xhr, status ) { 
                    $('#resultModifierEtablissement').html(xhr.responseText);  
                }
            }); 
        }



        // ouvrage
        function formModifierOuvrage(id, designation, matiere, niveau, niveauScolaire,unite,prix, code,tva)
        { 
            $("#resultModifierOuvrage").html(""); 

            $("#designation").val(designation); 
            $("#idOuvrage").val(id); 
            $("#niveauScolaireOuvrage").val(niveauScolaire);
            $("#niveauOuvrage").val(niveau);
            $("#matiere").val(matiere);
            $("#unite").val(unite);
            $("#prix").val(prix);
            $('#tva').val(tva);
            $("#code").val(code);
        }

        function modifierOuvrage()
        {     
            var url = $("#url").val()+'/ouvrage'
            if($("#idOuvrage").val() != 0) url += '/modifier';
            else url += '/ajouter';

            $.ajax({
                type: "POST",

                url : url,

                data: {
                    id:             $("#idOuvrage").val(),
                    designation:    $("#designation").val(), 
                    niveauScolaire: $("#niveauScolaireOuvrage").val(), 
                    prix :          $("#prix").val(),
                    niveau :        $("#niveauOuvrage").val(),
                    unite :         $("#unite").val(),
                    code :          $("#code").val(),
                    matiere :       $("#matiere").val(),
                    tva :           $('#tva').val(),
                    _token:         $("#token").val()
                },

                success: function( msg ) { 
                    $('#resultModifierOuvrage').html(msg);  
                },

                error:function( xhr, status ) { 
                    $('#resultModifierOuvrage').html(xhr.responseText);  
                }
            }); 
        }



        function supprimer()
        { 
            $.ajax({
                type: "POST",

                url : $("#url").val()+'/'+$("#nomObjet").val()+'/supprimer',

                data: {
                    id:     $("#idObjet").val(),
                    _token: $("#token").val()
                },

                success: function( msg ) {  
                    alert($("#nomObjet").val()+' '+$("#idObjet").val()+' supprimé !');
                    location.reload();
                },

                error:function( xhr, status ) {  
                }
            }); 
        }



        function confirmerUtilisateur()
        { 
            $.ajax({ 
                type: "POST",

                url : $("#url").val()+'/utilisateur/confirmer', 

                data: {
                    id:     $("#idUtilisateur").val(),
                    valConfirmation : $( "#valConfirmation"+$("#idUtilisateur").val() ).val(),
                    _token: $("#token").val()
                },

                success: function( msg ) {   
                    alert("Utilisateur est mis à jour !");
                    location.reload();
                },

                error:function( xhr, status ) {
                    //$("#test").html(xhr.responseText);
                }
            }); 
        }
 

        function gererCC()
        {
            var url = $("#url").val(); 

            if($("#idCC").val() != 0)
            {
                if( $("#cercleCommune").val()==null ) url += "/cercle/modifier"; 
                else url += "/commune/modifier";
            }
            else
            {
                if( $("#cercleCommune").val()==null ) url += "/cercle/ajouter";
                else  url += "/commune/ajouter";
            }
 
            $.ajax({ 
                type: "POST",

                url : url, 

                data: {
                    id     : $("#idCC").val(),
                    nom    : $("#nomCC" ).val(),
                    cercle : $("#cercleCommune").val(),
                    _token : $("#token").val()
                },

                success: function( msg ) {   
                    alert("Operation Réussite !");
                    location.reload();
                },

                error:function( xhr, status ) {  
                    alert("Operation echoué !"); 
                }
            }); 
        }


        

    </script>

@stop













 