<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
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
            'telegram_link_id' => 'nullable|exists:telegram_links,id',
            'name' => 'sometimes|required|max:255',
            'email' => 'sometimes|email|required_with:password|unique:users|max:255',
            'password' => 'sometimes|required_with:email|confirmed|min:8|max:255',
            'is_admin' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if((!Auth::user() && request()->is_admin) || (!Auth::user()?->is_admin && request()->is_admin)) {
                        $fail(__('The :attribute is invalid.'));
                    }
                },
            ],
            'telegram_link_url' => 'nullable',

            'current_password' => [
                'sometimes',
                'required_with:new_password',
                function ($attribute, $value, $fail) {
                    if(!Hash::check(request()->current_password, Auth::user()->password)) {
                        $fail(__('The :attribute is incorrect.'));
                    }
                },
            ],
            'new_password' => [
                'sometimes',
                'required_with:current_password',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                Rule::notIn([request()->current_password]), // ensure the new password is not the same as the current password
            ],
        ];
    }
}
