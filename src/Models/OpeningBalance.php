<?php

namespace Zerp\Account\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpeningBalance extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'account_id',
        'financial_year',
        'opening_balance',
        'balance_type',
        'effective_date',
        'creator_id',
        'created_by'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'effective_date' => 'date'
    ];

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }
}