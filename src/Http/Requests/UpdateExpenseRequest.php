<?php

namespace Zerp\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:expense_categories,id,created_by,' . creatorId(),
            'bank_account_id' => 'required|exists:bank_accounts,id,created_by,' . creatorId(),
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id,created_by,' . creatorId(),
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255',
        ];
    }
}
