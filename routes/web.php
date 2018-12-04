<?php



Route::get('/test', function () { return view('test'); });

// formulaire connexion
Route::get('/', function () { return view('connexion'); });
Route::get('/connexion', function () { return view('connexion'); });


// connexion control
Route::post('/connexion','SessionController@connexion');
Route::post('/deconnexion','SessionController@deconnexion');


// vue accueil apres la connexion
Route::post('/accueil','EtablissementController@init');
Route::get('/accueil', 'AccueilController@afficher' );

Route::post('/accueil/commune','AccueilController@getCommunes');
Route::post('/accueil/etablissement','AccueilController@getEtablissements');



// vue etablissement   
Route::get('/etablissement','EtablissementController@afficher'); 
Route::post('/etablissement','EtablissementController@filtrer'); 
Route::get('/panier/effacer','CommandeController@effacerPanier'); 



// filtrage de ouvrages
Route::post('/accueil/niveau/{n}/matiere/{m}','EtablissementController@filtrer');


// commande ouvrages
Route::get('/commandeActuel', 'CommandeController@afficherCommandeActuel' );
Route::get('/commande/{id}', 'CommandeController@afficherCommande' );
Route::post('/commande/ajouter','CommandeController@ajouterCommande'); 
Route::post('/ouvrage/ajouterAuSession','CommandeController@ajouterAuSession'); 
Route::post('/ouvrage/retirerDeSession','CommandeController@retirerDeSession'); 
Route::post( '/eleves/setNombre', 'CommandeController@setNbrEleves'  );


Route::get('/commandes', 'CommandeController@afficherLesCommandes');

Route::get(
    '/commande/{n}/imprimer',
    'CommandeController@imprimer'
);














/****************************** Administration **************************************/

Route::get('/administration',       'AdministrationController@index');
Route::get('/administration/{table}',       'AdministrationController@getTable');


// ajout des objets
Route::post('/administration/etablissement/ajouter', 'EtablissementController@gerer');
Route::post('/administration/ouvrage/ajouter',       'OuvrageController@gerer');
Route::post('/administration/cercle/ajouter',        'CercleController@ajouter');
Route::post('/administration/commune/ajouter',       'CommuneController@ajouter');

// modification des objets
Route::post('/administration/etablissement/modifier', 'EtablissementController@gerer');
Route::post('/administration/ouvrage/modifier',       'OuvrageController@gerer');
Route::post('/administration/cercle/modifier',        'CercleController@modifier');
Route::post('/administration/commune/modifier',       'CommuneController@modifier');

// suppression des objets
Route::post('/administration/etablissement/supprimer', 'EtablissementController@supprimer');
Route::post('/administration/ouvrage/supprimer',       'OuvrageController@supprimer');
Route::post('/administration/cercle/supprimer',        'CercleController@supprimer');
Route::post('/administration/commune/supprimer',       'CommuneController@supprimer');

// utilisateur
Route::post('/administration/utilisateur/confirmer',   'UtilisateurController@confirmerUtilisateur');
Route::post('/administration/utilisateur/supprimer',   'UtilisateurController@supprimerUtilisateur');
Route::post('/utilisateur/creer',       'UtilisateurController@creerCompte');
Route::post('/utilisateur/recuperer',   'UtilisateurController@recupererCompte');
Route::post('/utilisateur/modifier',   'UtilisateurController@modifierInformations');



// commandes
Route::get('/administration/commande/{n}',              'CommandeController@afficherCommandeAdmin');
Route::post('/administration/commande/supprimer',       'CommandeController@supprimer');
Route::post('/administration/commande/confirmer',       'CommandeController@confirmerCommande');

Route::post('/administration/commande/{n}/modifier',    'CommandeController@modifierCommande');
Route::get('/administration/commande/{n}/modifier', function(){ return redirect('/administration/commandes'); } );


// statiqtiques
Route::get('/administration/commandes/statistiques',     'AdministrationController@afficherStatistiques');

Route::post('/administration/commandes/statistiques',     
        'AdministrationController@filtrerStatistiques');
Route::post('/administration/commandes/statistiques/commune',     
        'AdministrationController@filtrerStatistiquesParCommune');

Route::post('/administration/commandes/statistiques/etablissement',     
'AdministrationController@getStatistiquesEtablissement');
        


 


Route::get('/{x}', function () { return view('404'); });