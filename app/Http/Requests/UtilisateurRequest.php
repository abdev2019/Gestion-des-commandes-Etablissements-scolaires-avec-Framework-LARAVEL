<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtilisateurRequest extends FormRequest
{ 
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'email'      => 'required|email',
            'nom'        => 'required|min:5|regex:/^[\pL\s\-]+$/u',
            'motdepasse' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [ 
            'email.required' => '
            <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            <div style="display:inline-table">L\'e-mail est obligatoire.</div></small><br>
            ',
            'email.email' => '
            <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            <div style="display:inline-table">L\'adresse email n\'est pas valide.</div></small><br>
            ',
            'nom.required' => '
                <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <div style="display:inline-table">Le nom est obligatoire.</div></small><br>    
            ',
            'nom.regex' => '
                <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <div style="display:inline-table">Le nom n\'est pas valide, il doit &ecirc;tre au moins 6 lettres.</div></small><br>    
            ',
            'nom.min' => '
                <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <div style="display:inline-table">Le nom n\'est pas valide, il doit &ecirc;tre au moins 6 lettres.</div></small><br>    
            ',

            'motdepasse.required' =>'
                <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <div style="display:inline-table">Le mot de passe est obligatoire.</div></small> <br>   
            ',
            'motdepasse.min' => '
                <small class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <div style="display:inline-table">Le mot de passe doit &ecirc;tre fort, et contient au moins 6 charactères.</div></small> <br>   
            ',
        ];
    }
}
