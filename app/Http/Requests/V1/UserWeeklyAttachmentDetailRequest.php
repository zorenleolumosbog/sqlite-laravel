<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserWeeklyAttachmentDetailRequest extends FormRequest
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
            'user_weekly_attachment_id' => 'sometimes|required|exists:user_weekly_attachments,id',
            'description' => 'sometimes|nullable|max:255',
            'file_weekly_photo' => 'sometimes|required|image|mimes:jpg,jpeg,png|max:25600', // limit 25mb
        ];
    }
}
