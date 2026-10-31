<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // all user can access
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $uniqueName = Rule::unique('brands', 'name');
        $brandId = $this->route('id');

        if ($brandId !== null) {
            $uniqueName->ignore($brandId, '_id');
        }

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', $uniqueName],
            'status' => 'required|boolean',
            'description' => 'nullable|string|min:3|max:255',
        ];
    }
}
