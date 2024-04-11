<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'filled',
            'description' => 'nullable',
            'due_date' => 'filled|date|after_or_equal:today',
            'status' => 'filled|in:todo,over_due,doing,done',
            'tags' => 'array|filled',
            'tags.*' => Rule::exists(Tag::class, 'id')
                ->where('user_id', $this->user()->id)
                ->whereNull('deleted_at'),
            'category_id' => [
                'filled',
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
