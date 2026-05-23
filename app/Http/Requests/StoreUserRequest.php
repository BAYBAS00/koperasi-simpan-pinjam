<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'username' => ['required', 'min:4', 'max:50', 'unique:users,username'],
            'password' => ['required', 'min:6'],
            'role' => ['required', 'in:pengurus,anggota'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Password minimal 6 karakter.',
            'password.required' => 'Password wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ];
    }
}
