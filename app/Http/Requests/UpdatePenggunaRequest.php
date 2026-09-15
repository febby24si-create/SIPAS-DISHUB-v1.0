<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenggunaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role?->name === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('pengguna'); // Asumsi parameter route adalah {pengguna}

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => ['required', 'exists:roles,id'],
            'pegawai_id' => ['nullable', 'exists:pegawai,id', Rule::unique('users')->ignore($user->id)],
        ];
    }

    public function messages(): array
    {
        return [
            'pegawai_id.unique' => 'Pegawai ini sudah terhubung dengan akun pengguna lain.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ];
    }
}
