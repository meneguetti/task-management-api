<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'description' => 'sometimes',
            'status' => ['sometimes', Rule::in(Task::STATUSES)],
            'priority' => ['sometimes', Rule::in(Task::PRIORITIES)],
            'due_date' => 'sometimes|after_or_equal:today|date_format:Y-m-d',
        ];
    }
}
