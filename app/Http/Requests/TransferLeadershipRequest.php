<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferLeadershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puede transferir el líder actual, o un admin (por si el líder no puede entrar ese día)
        return ($this->user()?->isLider() ?? false) || ($this->user()?->isAdmin() ?? false);
    }

    public function rules(): array
    {
        return [
            'guia_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'guia')],
        ];
    }
}
