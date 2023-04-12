<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'gender' => 'sometimes|required||in:male,female',
            'age' => 'sometimes|required|integer',
            'height' => 'sometimes|required|max:255',
            'current_weight' => 'sometimes|required|integer|gt:desired_weight_goal',
            'desired_weight_goal' => 'sometimes|required|integer',
            'how_active_are_you' => 'sometimes|required|max:255',
            'hours_of_sleep_at_night' => 'sometimes|required||max:255',
            'stress_level_out_of_10' => 'sometimes|required|integer|in:1,2,3,4,5,6,7,8,9,10',
            'medications_supplements' => 'sometimes|required|max:255',
            'injuries_illnesses' => 'sometimes|required|max:255'
        ];
    }
}
