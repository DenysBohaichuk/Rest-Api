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
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email|regex:/^[\w\.]+@([\w-]+\.)+[\w-]{2,4}$/|unique:users',
            'phone' => [
                'required',
                'string',
                'regex:/^(\+380)[0-9]{9}$/',
                'max:13',
            ],
            'position_id' => 'required|integer|exists:positions,id',
            'photo' => 'required|image|mimes:jpeg,jpg|dimensions:min_width=70,min_height=70|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле Ім\'я є обов\'язковим.',
            'name.string' => 'Поле Ім\'я повинно бути строкою.',
            'name.min' => 'Поле "Ім\'я" повинно містити не менше 2 символів.',
            'name.max' => 'Поле "Ім\'я" не може перевищувати 60 символів.',

            'email.required' => 'Поле Email є обов\'язковим.',
            'email.email' => 'Поле Email повинно містити дійсну адресу електронної пошти.',
            'email.regex' => 'Поле "Email" повинно відповідати стандарту RFC2822.',
            'email.unique' => 'Цей Email вже існує в системі.',

            'phone.required' => 'Поле Телефон є обов\'язковим.',
            'phone.string' => 'Поле Телефон повинно бути строкою.',
            'phone.regex' => 'Телефон повинен починатися з +380',
            'phone.max' => 'Телефон не може перевищувати 13 символів у форматі +380.',

            'position_id.required' => 'Поле "Посада" є обов\'язковим.',
            'position_id.exists' => 'Вказана посада не існує в системі.',

            'photo.required' => 'Поле "Фото" є обов\'язковим.',
            'photo.image' => 'Поле "Фото" повинно бути зображенням.',
            'photo.mimes' => 'Фото повинно бути формату JPEG або JPG.',
            'photo.dimensions' => 'Фото повинно мати мінімальні розміри 70x70 пікселів.',
            'photo.max' => 'Розмір фото не може перевищувати 5 МБ.',
        ];
    }

}
