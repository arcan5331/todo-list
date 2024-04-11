<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'filled',
                Rule::unique(Category::class, 'name')
                    ->ignoreModel($this->category)
                    ->whereNull('deleted_at')
                    ->where('user_id', $this->user()->id)
            ],
            'category_id' => [
                'filled',
                Rule::prohibitedIf($this->category->category_id == null),
                Rule::exists(Category::class, 'id')
                    ->whereNot('id',$this->category->id)
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
