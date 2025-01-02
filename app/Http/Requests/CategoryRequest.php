<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    protected $categoryId = null;

    public function setCategoryId($categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('crud.categories.validation.name_required'),
            'name.max' => __('crud.categories.validation.name_max'),
            'description.required' => __('crud.categories.validation.description_required'),
            'category_id.exists' => __('crud.categories.validation.category_exists'),
        ];
    }
}
