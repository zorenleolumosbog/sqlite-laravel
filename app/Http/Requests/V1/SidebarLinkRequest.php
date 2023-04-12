<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SidebarLinkRequest extends FormRequest
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
            'title' => 'sometimes|required|max:255',
            'link' => [
                'sometimes',
                'required',
                'url',
                'max:255',
                Rule::unique('sidebar_links')->where(function ($query) {
                    $query->where('id', '<>', request()->route('sidebar_link')?->id)
                        ->whereNull('deleted_at');
                }),
            ],
            'bg_color' => 'sometimes|required|max:255',
            'file_icon' => 'sometimes|required|image|mimes:jpg,jpeg,png|dimensions:max_width=80,max_height=80|max:5120', // limit 5mb
        ];
    }
}
