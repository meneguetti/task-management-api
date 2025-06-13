<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|max:255',
            'status' => 'sometimes|array',
            'status.*' => [
                'required_with:status',
                Rule::in(Task::STATUSES),
            ],
            'priority' => 'sometimes|array',
            'priority.*' => [
                'required_with:priority',
                Rule::in(Task::PRIORITIES),
            ],
            'due_date_from' => 'sometimes|date_format:Y-m-d',
            'due_date_to' => 'sometimes|date_format:Y-m-d',
        ];
    }
}
