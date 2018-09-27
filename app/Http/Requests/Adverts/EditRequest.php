<?php

namespace App\Http\Requests\Adverts;

use App\Entity\Advert\Category;
use App\Entity\Region;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Category $category
 * @property Region $region
 */
class EditRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'content' => 'required|string',
            'price' => 'required|integer',
            'address' => 'required|string',
        ];
    }
}
