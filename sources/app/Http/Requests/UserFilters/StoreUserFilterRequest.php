<?php

namespace App\Http\Requests\UserFilters;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserFilterRequest extends FormRequest
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
            'type' => 'nullable|string|max:255',
            'rooms' => 'nullable|integer|min:1|max:10',
            'price' => 'nullable|string|max:255',
            'floors' => 'nullable|string|max:255',
            'area_full' => 'nullable|string|max:255',
            'area_living' => 'nullable|string|max:255',
            'area_plot' => 'nullable|string|max:255',
            'ceiling_height' => 'nullable|string|max:255',
            'house_type' => 'nullable|string|max:255',
            'finishing' => 'nullable|string|max:255',
            'bathroom' => 'nullable|string|max:255',
            'features' => 'nullable|string|max:255',
            'security' => 'nullable|string|max:255',
            'water_supply' => 'nullable|string|max:255',
            'electricity' => 'nullable|string|max:255',
            'sewerage' => 'nullable|string|max:255',
            'heating' => 'nullable|string|max:255',
            'gasification' => 'nullable|string|max:255',
            'to_metro' => 'nullable|string|max:255',
            'to_center' => 'nullable|string|max:255',
            'to_busstop' => 'nullable|string|max:255',
            'to_train' => 'nullable|string|max:255',
            'near' => 'nullable|string|max:255',
            'garden_community' => 'nullable|boolean',
            'in_city' => 'nullable|boolean',
            'payment_method' => 'nullable|string|max:255',
            'mortgage' => 'nullable|string|max:255',
            'installment_plan' => 'nullable|string|max:255',
            'down_payment' => 'nullable|string|max:255',
            'mortgage_programs' => 'nullable|string|max:255'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userKey = $this->query('user_key');

            if (!$userKey) {
                $validator->errors()->add('user_key', 'Параметр user_key обязателен');
                return;
            }

            // Проверяем формат UUID
            if (!preg_match('/^[a-f\d]{8}-([a-f\d]{4}-){3}[a-f\d]{12}$/i', $userKey)) {
                $validator->errors()->add('user_key', 'Неверный формат user_key');
                return;
            }

            // Проверяем существование пользователя
            $userExists = \App\Models\User::where('key', $userKey)->exists();
            if (!$userExists) {
                $validator->errors()->add('user_key', 'Пользователь с таким ключом не найден');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rooms.integer' => 'Количество комнат должно быть числом',
            'rooms.min' => 'Количество комнат должно быть не менее 1',
            'rooms.max' => 'Количество комнат должно быть не более 10',
            'garden_community.boolean' => 'Поле "Загородный поселок" должно быть true или false',
            'in_city.boolean' => 'Поле "В черте города" должно быть true или false',
            'user_key.required' => 'Параметр user_key обязателен',
            'user_key.uuid' => 'Параметр user_key должен быть валидным UUID'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'type' => 'Тип недвижимости',
            'rooms' => 'Количество комнат',
            'price' => 'Цена',
            'floors' => 'Этажи',
            'area_full' => 'Общая площадь',
            'area_living' => 'Жилая площадь',
            'area_plot' => 'Площадь участка',
            'ceiling_height' => 'Высота потолков',
            'house_type' => 'Тип дома',
            'finishing' => 'Отделка',
            'bathroom' => 'Санузел',
            'features' => 'Особенности',
            'security' => 'Охрана',
            'water_supply' => 'Водоснабжение',
            'electricity' => 'Электричество',
            'sewerage' => 'Канализация',
            'heating' => 'Отопление',
            'gasification' => 'Газоснабжение',
            'to_metro' => 'До метро',
            'to_center' => 'До центра',
            'to_busstop' => 'До остановки',
            'to_train' => 'До вокзала',
            'near' => 'Рядом с',
            'garden_community' => 'Загородный поселок',
            'in_city' => 'В черте города',
            'payment_method' => 'Способ оплаты',
            'mortgage' => 'Ипотека',
            'installment_plan' => 'Рассрочка',
            'down_payment' => 'Первоначальный взнос',
            'mortgage_programs' => 'Ипотечные программы'
        ];
    }
}
