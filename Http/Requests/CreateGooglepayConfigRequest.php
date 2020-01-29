<?php

namespace Modules\Icommercegooglepay\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateGooglepayConfigRequest extends BaseFormRequest
{
    public function rules()
    {
        return [];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
