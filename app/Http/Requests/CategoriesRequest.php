<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:5,255',
            'slug' => 'required|alpha_dash|between: 3,50|unique:news_categories,slug',
            'description' => 'required|string|between: 10,1000'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Наименование каьегории',
            'slug' => 'slug',
            'description' => 'Краткое описание'
        ];
    }
}
