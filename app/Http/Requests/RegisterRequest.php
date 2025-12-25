<?php

namespace App\Http\Requests;

use App\DTOs\RegisterDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'brand_name' => ['required', 'string', 'max:255', 'unique:brands,name'],
        ];
    }

    /**
     * Create DTO from request.
     */
    public function createRegisterDTO(): RegisterDTO
    {
        $dto = new RegisterDTO();
        $dto->setName($this->name)
            ->setEmail($this->email)
            ->setPassword($this->password)
            ->setBrandName($this->brand_name)
            ->setBrandSlug(Str::slug($this->brand_name));

        return $dto;
    }
}
