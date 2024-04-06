<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'nullable',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'nullable|in:todo,over_due,doing,done'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
