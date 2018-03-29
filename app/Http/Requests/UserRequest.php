<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . \Auth::id(),
            'email' => 'required|email',
            'introduction' => 'min:5',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名必须',
            'name.between' => '用户名必须在3~25个字符之间',
            'name.unique' => '用户名已存在',
            'name.regex' => '用户名只支持数字，大小写字母，横线和下划线',
            'introduction.min' => '个人简介至少5个字符'
        ];
    }
}
