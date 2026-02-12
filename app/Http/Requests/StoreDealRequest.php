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
     * Prepare the data for validation by merging query parameters.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Merge query parameters with request data
        $this->merge($this->query());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'initial_commission' => 'required|numeric|min:0|max:100',
            'final_commission' => 'required|numeric|min:0|max:100|gte:initial_commission',
            'description' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string',
            'target_turnover' => 'nullable|numeric',
            'second_target_turnover' => 'nullable|numeric',
            'current_turnover' => 'nullable|numeric',
            'items_profit_average' => 'nullable|numeric',
            'is_turnover' => 'nullable|boolean',
            'discount' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
            'notes' => 'nullable|string|max:1000',
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
            'initial_commission' => __('initial commission'),
            'final_commission' => __('final commission'),
            'description' => __('description'),
            'type' => __('deal type'),
            'status' => __('deal status'),
            'target_turnover' => __('target turnover'),
            'second_target_turnover' => __('second target turnover'),
            'current_turnover' => __('current turnover'),
            'items_profit_average' => __('items profit average'),
            'is_turnover' => __('turnover flag'),
            'discount' => __('discount'),
            'start_date' => __('start date'),
            'end_date' => __('end date'),
            'platform_id' => __('platform'),
            'created_by' => __('creator'),
            'notes' => __('notes'),
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
            'initial_commission.required' => __('The initial commission is required'),
            'final_commission.required' => __('The final commission is required'),
            'final_commission.gte' => __('The final commission must be greater than or equal to the initial commission'),
            'description.required' => __('The deal description is required'),
            'platform_id.required' => __('Please select a platform'),
            'platform_id.exists' => __('The selected platform does not exist'),
            'end_date.after_or_equal' => __('The end date must be after or equal to the start date'),
            'start_date.required' => __('The start date is required'),
            'end_date.required' => __('The end date is required'),
        ];
    }
}

