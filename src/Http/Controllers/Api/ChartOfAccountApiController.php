<?php

namespace Zerp\Account\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Zerp\Account\Models\ChartOfAccount;
use Zerp\Account\Http\Requests\Api\StoreChartOfAccountApiRequest;
use Zerp\Account\Http\Requests\Api\UpdateChartOfAccountApiRequest;
use Zerp\Account\Events\CreateChartOfAccount;
use Zerp\Account\Events\UpdateChartOfAccount;
use Zerp\Account\Events\DestroyChartOfAccount;

class ChartOfAccountApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            if (!Auth::user()->can('manage-chart-of-accounts')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $chartofaccounts = ChartOfAccount::query()
                ->with(['account_type', 'parent_account'])
                ->where(function($q) {
                    if (Auth::user()->can('manage-any-chart-of-accounts')) {
                        $q->where('created_by', creatorId());
                    } elseif (Auth::user()->can('manage-own-chart-of-accounts')) {
                        $q->where('creator_id', Auth::id());
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                })
                ->when($request->account_code, function($q) use ($request) {
                    $q->where(function($query) use ($request) {
                        $query->where('account_code', 'like', '%' . $request->account_code . '%');
                        $query->orWhere('account_name', 'like', '%' . $request->account_code . '%');
                    });
                })
                ->when($request->account_type_id && $request->account_type_id !== 'all', fn($q) => $q->where('account_type_id', $request->account_type_id))
                ->when($request->normal_balance && $request->normal_balance !== 'all', fn($q) => $q->where('normal_balance', $request->normal_balance))
                ->when($request->is_active !== null && $request->is_active !== 'all', fn($q) => $q->where('is_active', $request->is_active === '1'))
                ->sortSafe($request->get('sort'), $request->get('direction'))
                ->paginate($request->get('per_page', 10))
                ->withQueryString();

            return $this->paginatedResponse($chartofaccounts, __('Chart of accounts retrieved successfully'));
        } catch (\Exception $e) {
            Log::error('Chart of Account API index error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }

    public function store(StoreChartOfAccountApiRequest $request)
    {
        try {
            if (!Auth::user()->can('create-chart-of-accounts')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active', true);

            $chartofaccount = new ChartOfAccount();
            $chartofaccount->account_code = $validated['account_code'];
            $chartofaccount->account_name = $validated['account_name'];

            if (!empty($validated['parent_account_id']) && $validated['parent_account_id'] !== '0') {
                $chartofaccount->level = 2;
                $chartofaccount->parent_account_id = $validated['parent_account_id'];
            } else {
                $chartofaccount->level = 1;
                $chartofaccount->parent_account_id = null;
            }

            $chartofaccount->normal_balance = $validated['normal_balance'];
            $chartofaccount->opening_balance = $validated['opening_balance'] ?? 0;
            $chartofaccount->current_balance = $validated['current_balance'] ?? 0;
            $chartofaccount->is_active = $validated['is_active'];
            $chartofaccount->description = $validated['description'] ?? null;
            $chartofaccount->account_type_id = $validated['account_type_id'];
            $chartofaccount->creator_id = Auth::id();
            $chartofaccount->created_by = creatorId();
            $chartofaccount->save();

            CreateChartOfAccount::dispatch($request, $chartofaccount);

            return $this->successResponse($chartofaccount, __('Chart of account created successfully'), 201);
        } catch (\Exception $e) {
            Log::error('Chart of Account API store error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }

    public function show($id)
    {
        try {
            if (!Auth::user()->can('manage-chart-of-accounts')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $account = ChartOfAccount::with(['account_type', 'parent_account'])
                ->where('id', $id)
                ->where(function($q) {
                    if (Auth::user()->can('manage-any-chart-of-accounts')) {
                        $q->where('created_by', creatorId());
                    } elseif (Auth::user()->can('manage-own-chart-of-accounts')) {
                        $q->where('creator_id', Auth::id());
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                })
                ->first();

            if (!$account) {
                return $this->errorResponse(__('Chart of account not found'), null, 404);
            }

            return $this->successResponse($account, __('Chart of account details retrieved successfully'));
        } catch (\Exception $e) {
            Log::error('Chart of Account API show error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }

    public function update(UpdateChartOfAccountApiRequest $request, $id)
    {
        try {
            if (!Auth::user()->can('edit-chart-of-accounts')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $chartofaccount = ChartOfAccount::where('id', $id)
                ->where('created_by', creatorId())
                ->first();

            if (!$chartofaccount) {
                return $this->errorResponse(__('Chart of account not found'), null, 404);
            }

            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active', true);

            if ($chartofaccount->is_system_account != 1) {
                $chartofaccount->account_code = $validated['account_code'];
                $chartofaccount->account_name = $validated['account_name'];
            }

            if (!empty($validated['parent_account_id']) && $validated['parent_account_id'] !== '0') {
                $chartofaccount->level = 2;
                $chartofaccount->parent_account_id = $validated['parent_account_id'];
            } else {
                $chartofaccount->level = 1;
                $chartofaccount->parent_account_id = null;
            }

            $chartofaccount->normal_balance = $validated['normal_balance'];
            $chartofaccount->opening_balance = $validated['opening_balance'] ?? 0;
            $chartofaccount->current_balance = $validated['current_balance'] ?? 0;
            $chartofaccount->is_active = $validated['is_active'];
            $chartofaccount->description = $validated['description'] ?? null;
            $chartofaccount->account_type_id = $validated['account_type_id'];
            $chartofaccount->save();

            UpdateChartOfAccount::dispatch($request, $chartofaccount);

            return $this->successResponse($chartofaccount, __('Chart of account updated successfully'));
        } catch (\Exception $e) {
            Log::error('Chart of Account API update error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (!Auth::user()->can('delete-chart-of-accounts')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $chartofaccount = ChartOfAccount::where('id', $id)
                ->where('created_by', creatorId())
                ->first();

            if (!$chartofaccount) {
                return $this->errorResponse(__('Chart of account not found'), null, 404);
            }

            if ($chartofaccount->is_system_account == 1) {
                return $this->errorResponse(__('System accounts cannot be deleted'), null, 400);
            }

            DestroyChartOfAccount::dispatch($chartofaccount);
            $chartofaccount->delete();

            return $this->successResponse(null, __('Chart of account deleted successfully'));
        } catch (\Exception $e) {
            Log::error('Chart of Account API destroy error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }
}
