@extends('template')


@section('titre') 
    Connexion
@stop



@section('contenu') 

 
    <script>$(document).ready(function() {$('#footer').addClass('fixed-bottom');});</script>
    

 
    <div id="cont" style="color:white;margin-bottom:14.6%"  > 
            <div id="login-container "  class="col-md-5" style="margin: 0 auto;" >
            <div id="login-sub-container"   >
                    <div id="login-sub-header"  >
                        Connexion
                    </div>
            <div id="login-sub" style="padding:20px"   >  

                    <form action="{{url('/connexion')}}" method="POST" name="formInit" style="margin:0;" class="col">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                    <?php if (session()->has('alert')) echo session('alert'); ?>
                    <table class="table" style="color:white ; margin:0;" > 
                        <tr>
                            <td>E-mail</td>
                            <td>
                                <input name="email" type="text" class="form-control" value="{{ session()->has('oldEmail') ? session('oldEmail') : '' }}" /> 
                            </td>
                        </tr>

                        <tr>
                            <td>Mot de passe</td>
                            <td >
                                <input name="motdepasse" type="password" class="form-control" /> 
                            </td>
                        </tr> 

                        <tr>
                            <td></td>
                            <td >
                                <button style="width:100%;height:40px; margin:0">Connecter</button> 
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <a href="#" style="color:beige" onclick="$('#formOublie').modal();">Oublié ?</a><br>
                                <a href="#" style="color:beige" onclick="$('#formDemandeCompte').modal();">Demander un compte</a>
                            </td> 
                        </tr>
                    </table>  
                    </form>
                    
            </div>   
            </div>
            </div>  
    </div> 
  

    <!-- Modal new compte --> 
    @if (isset($errors) && !$errors->isEmpty()) <script>$(document).ready(function(){ $('#formDemandeCompte').modal(); });</script>  @endif
    <div class="modal fade" id="formDemandeCompte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
    <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Demande du compte</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>
            <form action="{{url('utilisateur/creer')}}" method="POST" style="margin:0">
                <div class="modal-body alert-primary">  
                    <div class="alert"> 
                            E-mail :<br> 
                            <input name="email" type="text" class="form-control" value="{{ old('newEmailUser') }}" /> 
                            {!! $errors->first('email') !!}
                            <br>

                            Nom :<br> 
                            <input name="nom" type="text" class="form-control" value="{{ old('newNomUser') }}" />
                            {!! $errors->first('nom') !!}
                            <br>

                            Mot de passe :<br> 
                            <div class="col">
                            <div class="row">
                                <input id="newMotdepasseUser" name="motdepasse" type="password" 
                                    class="form-control col-md-11" 
                                    style="border-radius:5px 0 0 5px; border-right:none" 
                                    value="{{ old('newMotdepasseUser') }}" />
                                <span 
                                    onclick="if(newMotdepasseUser.type=='text')newMotdepasseUser.type='password'; 
                                        else newMotdepasseUser.type='text';
                                        $(this).toggleClass('alert-danger');" 
                                    class="btn btn-light text-success col-md-1" 
                                    style="padding:10px 0;border:solid 0.01em; border-radius:0 5px 5px 0;">
                                <i class="fa fa-1x fa-eye" ></i>
                                </span>
                            </div>  
                            </div> 
                            {!! $errors->first('motdepasse') !!}
                            <br> 
                    </div>

                <div class="modal-footer alert-primary"  >
                    <button style="display" id="addBtnDemander" type="submit"  class="btn-primary btn" >
                        Demander
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button> 
                </div> 

                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form> 
    </div>
    </div>  
    </div>
    

    
    <!-- Modal oublier -->
    <div class="modal fade" id="formOublie" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
        <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Récuperation du compte</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

            <div class="modal-body alert-primary">  
                <div class="alert"> 
                    <div>
                    <div id="resultDemande"></div>
                    E-mail :<br> 
                    <input id="emailOublie" type="text" class="form-control" />  
                </div>
            </div>

            <div class="modal-footer alert-primary" style="padding-bottom:0">
                    <button style="display" id="addBtnOublie" onclick="recupererCompte();"  class="btn-primary btn" >
                        Envoyer
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <br><br>
            </div> 
            
        </div>
        </div>  
    </div>
    </div>



    <script>
        function recupererCompte()
        { 
            $('#resultDemande').html(
            "<div class='progress'>\
                <div class='progress-bar progress-bar-striped progress-bar-animated'\
                 role='progressbar' \
                 aria-valuenow='75' \
                 aria-valuemin='0' \
                 aria-valuemax='100'></div></div>"
            ); 


            var $progress = $('.progress');
            var $progressBar = $('.progress-bar');
            var $alert = $('.alert');

            setTimeout(function() {
                $progressBar.css('width', '10%');
                setTimeout(function() {
                    $progressBar.css('width', '30%');
                    setTimeout(function() {
                        $progressBar.css('width', '99%'); 
                    }, 1000); // WAIT 2 seconds
                }, 1000); // WAIT 1 seconds
            }, 1000); // WAIT 1 second

            $.ajax({
                type: "POST",

                url : $("#url").val()+'/utilisateur/recuperer',

                data: {
                    email : $("#emailOublie").val(),
                    _token: $("#token").val()
                },

                success: function( msg ) {  
                    $('#resultDemande').html(msg); 
                },

                error:function( xhr, status ) {
                    $('#resultDemande').html(
                        "<div class='text-danger alert-danger alert'>Probleme de connexion</div>"
                    //+xhr.responseText
                    );  
                }
            }); 
        }  
    </script>


@stop