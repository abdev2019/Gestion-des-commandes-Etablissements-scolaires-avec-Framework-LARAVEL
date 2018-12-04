<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtablissementRequest extends FormRequest
{ 
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            //
        ];
    }
}
