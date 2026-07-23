<?php

namespace Zerp\Account\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class StoreChartOfAccountApiRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'account_code' => 'required|string|max:255|unique:chart_of_accounts,account_code,NULL,id,created_by,' . creatorId(),
            'account_name' => 'required|string|max:255',
            'level' => 'nullable|integer|min:1',
            'normal_balance' => 'required|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'current_balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'account_type_id' => 'nullable|exists:account_types,id,created_by,' . creatorId(),
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id,created_by,' . creatorId()
        ];
    }
}
