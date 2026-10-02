<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateActivityRequest extends StoreActivityRequest
{
    public function rules(): array
    {
        $activity = $this->route('activity');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('activities', 'code')->ignore($activity),
            ],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'activity_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
