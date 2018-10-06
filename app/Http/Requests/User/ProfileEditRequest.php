<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|regex:/^\d+$/s',
        ];
    }
}

/**
 * @SWG\Definition(
 *     definition="ProfileEditRequest",
 *     type="object",
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="last_name", type="string"),
 *     @SWG\Property(property="phone", type="string"),
 * )
 */