<html>
    <head>
        <title>@yield('titre')</title> 
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/ubuntu.css') }}">
        <link href="{{ URL::asset('css/font-awesome.css') }}"  rel="stylesheet"  type='text/css'>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap.min.css') }}">    
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/css.css') }}">
        <script src="{{ URL::asset('js/jquery-3.2.1.slim.min.js') }}" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="{{ URL::asset('js/popper.min.js') }}" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>  
        
        <!-- sorted table + fixed header --> 
        <link href="{{ URL::asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ URL::asset('css/fixedHeader.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/responsive.bootstrap.min.css') }}">
        <link href="{{ URL::asset('css/monCSS.css') }}" rel="stylesheet"/>

        <script src="{{ URL::asset('js/jquery-1.12.4.js') }}" ></script>
        <script src="{{ URL::asset('js/jquery.dataTables.min.js') }}" ></script>
        <script src="{{ URL::asset('js/dataTables.bootstrap4.min.js') }}" ></script>
        <script src="{{ URL::asset('js/dataTables.fixedHeader.min.js') }}" ></script>
        <script src="{{ URL::asset('js/dataTables.responsive.min.js') }}" ></script>
        <script src="{{ URL::asset('js/responsive.bootstrap.min.js') }}" ></script>
            
        <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('js/monJS.js') }}"></script>
    </head>

 
<body class="container-fluid" style="background:black; padding:0px;"> 
 
    <center>           
        <div  style="float:right;color:white; position:absolute; right:0; padding-right:100px">Aujourd'hui <?php echo date("d/m/Y"); ?></div>
        <div id="bg"></div>
        <div class="container-fluid"><img class="col" src='{{ URL::asset("img/logo_minestre.png") }}' style="WIDTH:45%;" /></div>
    </center>
    <br>


	<div id="contenu" style="">
        
        <!-- header -->
        <?php if(session()->has('etablissement')){ ?> 
            <div class="container"  >
            <div class="row"  style="padding:20px;">
                
                <div class="col-md-4"  style="padding:5px 0 5px 0">  
                    <table style="font-weight:bold;color:white">
                    <tr>
                        <td  valign=top>Etablissement</td>
                        <td> : <?php if (session()->has('etablissement')) echo session('etablissement')->nom; ?></td>
                    </tr>
                    <tr>
                    <td  valign=top>Niveau Scolaire</td>
                    <td> : <?php if (session()->has('etablissement')) echo session('etablissement')->niveau; ?></td>
                    </tr>
                    <tr><td colspan=2><a class="text-warning" href="{{url('/accueil')}}">Changer l'établissement</a></td></tr>
                    </table>
                </div> 

                <div class="col-md-4"  style="padding:5px 0 5px 0">
                    <table style="font-weight:bold;color:white">
                    <tr>
                    <td valign=top>Cercle</td>
                    <td> : <?php if (session()->has('cercle')) echo session('cercle')->nom; ?></td>
                    </tr>
                    <tr>
                    <td  valign=top>Commune</td> 
                    <td> : <?php if (session()->has('commune')) echo session('commune')->nom; ?></td>
                    </tr>
                    </table>
                </div>
                
                <div class="col-md-2"  style="padding:5px 0 5px 0;color:white">
                    <b>Date :    <?php echo date('d/m/Y');//if (session()->has('etablissement')) echo session('etablissement'); ?></b>
                </div>
                
                <div class="col text-right" style="padding:5px 0 5px 0">
                    <!-- deconnexion -->
                    <form action="{{url('/deconnexion')}}" method="POST" >
                        <button type="submit" name="deconnexion" style="float:rdight; height:40px;color:rgb(255,200,200)">Deconnexion</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                    <button onclick="$('#formModifierInformations').modal();" style="color:rgb(200,200,255)">Compte</button>
                </div> 

            </div> 
            </div>
        <?php } ?>
    
            @yield('contenu')
    
        </div>
    
    
        <!-- footer -->
        <div id="footer" class="container-fluid" 
            style="background:black; color:white;marging-right:0;   border-top:solid; " >
            <div class="row" > 

                <div class="col-md-6">
                    ⵜⴰⴳⵍⴷⵉⵜ ⵏ ⵍⵎⵖⵔⵉⴱ - ⵜⴰⵎⴰⵡⴰⵙⵜ ⵏ ⵓⵙⴳⵎⵉ ⴰⵏⴰⵎⵓⵔ ⴷ 
                    ⵓⵙⵎⵓⵜⵜⴳ ⴰⵣⵣⵓⵍⴰⵏ ⴷ ⵓⵙⵙⵍⵎⴷ ⴰⵏⴰⴼⵍⵍⴰ ⴷ ⵓⵔⵣⵣⵓ ⴰⵎⴰⵙⵙⴰⵏ 
                </div>

                <div class="col-md-6 text-right">
                    المملكة المغربية - وزارة التربية الوطنية و 
                    التكوين المهني و التعليم العالي و البحث العلمي 
                    <br>
                    <a target="_blanc" href="http://www.men.gov.ma/Ar/Pages/Accueil.aspx" style="color:orange">
                    الموقع الرسمي للوزارة
                    </a>
                </div> 
            
            </div>
        </div>
    
    </div>

 

     
     
    <!-- Modal modifier compte -->
    <div class="modal fade" id="formModifierInformations" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black">
    <div class="modal-dialog  alert-info" role="document">
    <div class="modal-content ">

            <div class="modal-header alert-primary" style="border-color:black">
                <h5 class="modal-title" id="exampleModalLabel">Mis à jour informations du compte</h5>
                <span type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>

                <div class="modal-body alert-primary">  
                    <div class="alert"> 
                            E-mail :<br> 
                            <input id="emailUser" 
                            value="{{ Session::get('utilisateur')!=null?Session::get('utilisateur')->email:'' }}" 
                            type="text" class="form-control" />
                            <div id="emailError"></div> <br>

                            Nom :<br> 
                            <input id="nomUser" 
                            value="{{ Session::get('utilisateur')!=null?Session::get('utilisateur')->nom : '' }}" 
                            type="text" class="form-control" />
                            <div id="nomError"></div><br>

                            Mot de passe :<br> 
                            <div class="col">
                            <div class="row">
                                <input id="motdepasseUser" name="motdepasseUser" type="password" class="form-control col-md-11" style="border-radius:5px 0 0 5px; border-right:none" />
                                <span 
                                    onclick="if(motdepasseUser.type=='text')motdepasseUser.type='password'; 
                                        else motdepasseUser.type='text';
                                        $(this).toggleClass('alert-danger');" 
                                    class="btn btn-light text-success col-md-1" 
                                    style="padding:10px 0;border:solid 0.01em; border-radius:0 5px 5px 0;">
                                <i class="fa fa-1x fa-eye" ></i>
                                </span>
                            </div> 
                            <div id="motdepasseError"></div>
                            </div><br>
                                    
                            <label class="custom-control custom-checkbox" >
                                <input type="checkbox" onchange="$('#addBtnModifierInfos').toggle();" name="confirme" class="custom-control-input"  >
                                <span class="custom-control-indicator" style="border:solid;width:20px;height:20px"></span>
                                <span class="custom-control-description" style="height:20px;"> Confirmé</span>
                            </label> 
                    </div>

                <div class="modal-footer alert-primary" >
                    <button style="display:none" id="addBtnModifierInfos" onclick="modifierInformations();" class="btn-primary btn" >
                        Modifier
                    </button>
                    <input type="hidden" id="urlUser" value="{{url('')}}" />
                    <input id="idCompte" type="hidden" value="{{ Session::get('utilisateur')!=null?Session::get('utilisateur')->id:-1 }}" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button> 
                </div>  
                </div>

    </div>
    </div>  
    </div>
         
    <input type="hidden" value="{{url('')}}" id="host" />
    <input type="hidden" value="{{url('')}}" id='url' />
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">


</body> 

</html>