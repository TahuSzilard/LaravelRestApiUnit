<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->method() == 'PATCH'){
            return [
                'title' => 'nullable|string|min:2',
                'director_id' => 'nullable|numeric',
                'release_date'=> 'nullable|string|min:2',
                'description' => 'nullable|string|min:10',
                'image' => 'nullable|string|min:2',
                'type_id' => 'nullable|numeric',
                'length' => 'nullable|nullable|numeric',
                'created_at' => 'nullable|string',
                'updated_at'=> 'nullable|string',
            ];          
        }
 
        return [
            'title' => 'required|string|min:2',
            'director_id' => 'required|numeric',
            'release_date'=> 'string|min:2',
            'description' => 'required|string|min:10',
            'image' => 'string|min:2',
            'type_id' => 'required|numeric',
            'length' => 'required|nullable|numeric',
            'created_at' => 'string',
            'updated_at'=> 'string',
        ];
    }
}
