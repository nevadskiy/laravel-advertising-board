<?php

namespace App\Http\Requests\Banner;

use App\Entity\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
//        [$width, $height] = $this->input('format')
//            ? explode('x', $this->input('format'))
//            : [0, 0];
//
//        return [
//            'name' => 'required|string',
//            'limit' => 'required|integer',
//            'url' => 'required|url',
//            'format' => ['required', 'string', Rule::in(Banner::formatsList())],
//            'file' => 'required|image|mimes:jpg,jpeg,png|dimensions:width=' . $width . ',height=' . $height,
//        ];
    }
}
