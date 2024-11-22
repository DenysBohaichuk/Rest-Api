<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'uuid' => 'required|string|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => [
                'required',
                'string',
                'regex:/^(\+380|0)[0-9]{9}$/',
                'max:13',
            ],
            'profile_image' => 'required|image',
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => 'Поле ID є обов\'язковим.',
            'uuid.string' => 'Поле ID повинно бути строкою.',
            'uuid.unique' => 'Цей ID вже існує в системі.',

            'name.required' => 'Поле Ім\'я є обов\'язковим.',
            'name.string' => 'Поле Ім\'я повинно бути строкою.',
            'name.max' => 'Поле Ім\'я не може перевищувати 255 символів.',

            'email.required' => 'Поле Email є обов\'язковим.',
            'email.email' => 'Поле Email повинно містити дійсну адресу електронної пошти.',
            'email.unique' => 'Цей Email вже існує в системі.',

            'phone.required' => 'Поле Телефон є обов\'язковим.',
            'phone.string' => 'Поле Телефон повинно бути строкою.',
            'phone.regex' => 'Телефон повинен починатися з +380 або 0 та містити 9 цифр.',
            'phone.max' => 'Телефон не може перевищувати 13 символів у форматі +380.',

            'profile_image.required' => 'Поле Аватар профілю є обов\'язковим.',
            'profile_image.image' => 'Поле Аватар профілю повинно містити зображення.',
        ];
    }

}
