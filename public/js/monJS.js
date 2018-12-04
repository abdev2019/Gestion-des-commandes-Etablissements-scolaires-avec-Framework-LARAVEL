        langue = 
        {
            "lengthMenu": "Afficher _MENU_ lignes par page",
            "zeroRecords": "Aucun résultat trouvé !",
            "info": "page _PAGE_ / _PAGES_",
            "infoEmpty": "Aucune ligne trouvé !",
            "infoFiltered": "(Filtré de _MAX_ total lignes)",
            "search" : "Recherche",
            "emptyTable":     "Aucune donnée !",
            "loadingRecords": "Chargement...",
            "processing":     "Traitement...",
            "paginate": {
                "first":      "Premier",
                "last":       "Dernier",
                "next":       "Suivant",
                "previous":   "Précédent"
            },
            "aria": {
                "sortAscending":  ": Activer pour trier la colonne en ascendant",
                "sortDescending": ": Activer pour trier la colonne en descendant"
            }
        };

        function sortTable(id, col=1)
        { 
            doms=   "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>><'clearfix'><'alert'>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>"; 

            var tmp = $(id).DataTable({  
                fixedHeader: true, 
                responsive:true,
                dom: doms,  
                "scrollY": "435px", 
                "language": langue,
                "order": [[ col, "asc" ]]
                
            }); 
            
            return tmp;
        }


        function modifierInformations()
        {  
            $("#emailError").html("");
            $("#nomError").html("");
            $("#motdepasseError").html("");

            $.ajax({ 
                type: "POST",

                url : $("#urlUser").val()+'/utilisateur/modifier', 

                data: {
                    id:     $("#idCompte").val(),
                    nom :   $( "#nomUser" ).val(),
                    email : $( "#emailUser" ).val(),
                    motdepasse : $( "#motdepasseUser" ).val(),
                    _token: $("#token").val()
                },

                success: function( msg ) {   
                    alert("Utilisateur est mis à jour !");
                    location.reload();
                },

                error:function( xhr, status ) 
                {   
                    if( xhr.responseText.includes("nom") )
                    {
                        $("#nomError").html(
                            '<small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>\
                            <div style="display:inline-table">Le nom n\'est pas au bon format, il doit &ecirc;tre au moins 6 charactères.</div></small>'
                        ); 
                    }
                    
                    if( xhr.responseText.includes("email") )
                    {
                        $("#emailError").html(
                            '<small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>\
                            <div style="display:inline-table">L\'e-mail n\'est pas aux bon format.</div></small>'
                        ); 
                    }
                    
                    if( xhr.responseText.includes("motdepasse") )
                    {
                        $("#motdepasseError").html(
                            '<small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>\
                            <div style="display:inline-table">Le mot de passe doit &ecirc;tre fort, et contient au moins 6char.</div></small>'
                        ); 
                    }                         
                }
            }); 
        }