<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TelegramLinkRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'link' => [
                'required',
                'url',
                'max:255',
                Rule::unique('telegram_links')->where(function ($query) {
                    $query->where('id', '<>', request()->route('telegram_link')?->id)
                        ->whereNull('deleted_at');
                }),
            ]
        ];
    }
}
