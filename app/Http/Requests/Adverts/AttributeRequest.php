<?php

namespace App\Http\Requests\Adverts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $items = [];

        // $this - request context
        // $request->category - route binding

        foreach ($this->category->allAtributes() as $attribute) {
            $rules = [
                $attribute->required ? 'required' : 'nullable'
            ];

            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()) {
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
            }

            if ($attribute->isSelect()) {
                $rules[] = Rule::in($attribute->variants);
            }

            $rules['attribute.' . $attribute->id] = $rules;
        }

        return $items;
    }
}
