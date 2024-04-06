<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'nullable',
            'description' => 'nullable',
            'du_date' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|in:todo,over_du,doing,done'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
