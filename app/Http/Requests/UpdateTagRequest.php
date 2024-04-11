<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'filled',
                Rule::unique(Tag::class)
                    ->ignoreModel($this->tag)
                    ->where('user_id', $this->user()->id)
            ],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
