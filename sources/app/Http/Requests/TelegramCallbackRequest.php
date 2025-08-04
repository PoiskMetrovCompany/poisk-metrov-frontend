<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramCallbackRequest extends FormRequest
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
        $messageRules = [
            'update_id' => 'numeric|required',
            'message' => 'array',
            'message.message_id' => 'numeric',
            'message.date' => 'numeric',
            'message.text' => 'string',
            'message.document' => 'array',

            'message.from' => 'array',
            'message.from.id' => 'numeric',
            'message.from.is_bot' => 'boolean',
            'message.from.first_name' => 'string',
            'message.from.language_code' => 'string',

            'message.chat' => 'array',
            'message.chat.id' => 'numeric',
            'message.chat.first_name' => 'string',
            'message.chat.type' => 'string',

            'message.caption' => 'string',
            'message.photo' => 'array',

            'message.contact' => 'array',

            'callback_query' => 'array',
            'callback_query.id' => 'string',
            'callback_query.data' => 'string',
            'callback_query.message.chat.id' => 'numeric',
        ];

        $messageRules['reply_to_message'] = $messageRules;

        return $messageRules;
    }
}
