<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique(Category::class, 'name')
                    ->whereNull('deleted_at')
                    ->where('user_id', $this->user()->id)
            ],
            'category_id' => [
                'nullable',
                Rule::exists(Category::class, 'id')
                    ->whereNull('deleted_at')
                    ->where('user_id', $this->user()->id)
            ],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
