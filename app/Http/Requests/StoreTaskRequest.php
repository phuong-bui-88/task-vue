<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }

    public function getValidatorInstance()
    {
        $this->setDefaultDate();

        return parent::getValidatorInstance();
    }

    protected function setDefaultDate()
    {
        if (!$this->request->has('start_date')) {
            $this->merge(['start_date' => now()]);
        }

        if (!$this->request->has('end_date')) {
            $this->merge(['end_date' => now()]);
        }
    }
}
