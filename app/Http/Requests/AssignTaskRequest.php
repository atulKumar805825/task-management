<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ];
    }
}
