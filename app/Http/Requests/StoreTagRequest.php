<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique(Tag::class)->where('user_id', $this->user()->id)],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
