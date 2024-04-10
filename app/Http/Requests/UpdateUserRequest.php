<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['filled'],
            'email' => ['filled', 'email', 'max:254',
                Rule::unique(User::class)->ignoreModel($this->user())
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
