<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'initial_commission' => 'sometimes|numeric|min:0|max:100',
            'final_commission' => 'sometimes|numeric|min:0|max:100|gte:initial_commission',
            'description' => 'sometimes|string',
            'type' => 'sometimes|string',
            'status' => 'sometimes|string',
            'current_turnover' => 'nullable|numeric',
            'target_turnover' => 'nullable|numeric',
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
            'platform_id' => 'sometimes|exists:platforms,id',
            'cash_company_profit' => 'nullable|numeric',
            'cash_jackpot' => 'nullable|numeric',
            'cash_tree' => 'nullable|numeric',
            'cash_cashback' => 'nullable|numeric',
            'requested_by' => 'required|exists:users,id',
            'user_id' => 'sometimes|exists:users,id',
        ];
    }
}

