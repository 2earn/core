<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'plan_labels_id' => 'required|integer|exists:plan_labels,id',
            'description' => 'required|string',
            'validated' => 'required|boolean',
            'type' => 'required|string',
            'status' => 'required|string',
            'target_turnover' => 'nullable|numeric',
            'current_turnover' => 'nullable|numeric',
            'is_turnover' => 'nullable|boolean',
            'discount' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'earn_profit' => 'nullable|numeric',
            'jackpot' => 'nullable|numeric',
            'tree_remuneration' => 'nullable|numeric',
            'proactive_cashback' => 'nullable|numeric',
            'total_commission_value' => 'nullable|numeric',
            'total_unused_cashback_value' => 'nullable|numeric',
            'platform_id' => 'required|exists:platforms,id',
            'cash_company_profit' => 'nullable|numeric',
            'cash_jackpot' => 'nullable|numeric',
            'cash_tree' => 'nullable|numeric',
            'cash_cashback' => 'nullable|numeric',
            'created_by' => 'required|exists:users,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name' => __('deal name'),
            'commission_formula_id' => __('commission formula'),
            'description' => __('description'),
            'validated' => __('validation status'),
            'type' => __('deal type'),
            'status' => __('deal status'),
            'target_turnover' => __('target turnover'),
            'current_turnover' => __('current turnover'),
            'is_turnover' => __('turnover flag'),
            'discount' => __('discount'),
            'start_date' => __('start date'),
            'end_date' => __('end date'),
            'platform_id' => __('platform'),
            'created_by' => __('creator'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => __('The deal name is required'),
            'commission_formula_id.required' => __('Please select a commission formula'),
            'commission_formula_id.exists' => __('The selected commission formula does not exist'),
            'description.required' => __('The deal description is required'),
            'platform_id.required' => __('Please select a platform'),
            'platform_id.exists' => __('The selected platform does not exist'),
            'end_date.after_or_equal' => __('The end date must be after or equal to the start date'),
        ];
    }
}

