<?php

namespace Zerp\Account\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Zerp\Account\Models\VendorPayment;

class VendorPaymentApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            if (!Auth::user()->can('manage-vendor-payments')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $query = VendorPayment::with(['vendor', 'bankAccount', 'allocations.invoice', 'debitNoteApplications.debitNote'])
                ->where(function($q) {
                    if (Auth::user()->can('manage-any-vendor-payments')) {
                        $q->where('created_by', creatorId());
                    } elseif (Auth::user()->can('manage-own-vendor-payments')) {
                        $q->where('creator_id', Auth::id())->orWhere('vendor_id', Auth::id());
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                });

            if ($request->vendor_id) {
                $query->where('vendor_id', $request->vendor_id);
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->search) {
                $query->where('payment_number', 'like', '%' . $request->search . '%');
            }
            if ($request->date_from) {
                $query->whereDate('payment_date', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('payment_date', '<=', $request->date_to);
            }
            if ($request->bank_account_id) {
                $query->where('bank_account_id', $request->bank_account_id);
            }

            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->sortSafe($sortField, $sortDirection);

            $payments = $query->paginate($request->get('per_page', 10))->withQueryString();

            return $this->paginatedResponse($payments, __('Vendor payments retrieved successfully'));
        } catch (\Exception $e) {
            Log::error('Vendor Payment API index error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }

    public function show($id)
    {
        try {
            if (!Auth::user()->can('manage-vendor-payments')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $payment = VendorPayment::with(['vendor', 'bankAccount', 'allocations.invoice', 'debitNoteApplications.debitNote'])
                ->where('id', $id)
                ->where(function($q) {
                    if (Auth::user()->can('manage-any-vendor-payments')) {
                        $q->where('created_by', creatorId());
                    } elseif (Auth::user()->can('manage-own-vendor-payments')) {
                        $q->where('creator_id', Auth::id())->orWhere('vendor_id', Auth::id());
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                })
                ->first();

            if (!$payment) {
                return $this->errorResponse(__('Vendor payment not found'), null, 404);
            }

            return $this->successResponse($payment, __('Vendor payment details retrieved successfully'));
        } catch (\Exception $e) {
            Log::error('Vendor Payment API show error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (!Auth::user()->can('delete-vendor-payments')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $payment = VendorPayment::where('id', $id)
                ->where('created_by', creatorId())
                ->first();

            if (!$payment) {
                return $this->errorResponse(__('Vendor payment not found'), null, 404);
            }

            $payment->delete();

            return $this->successResponse(null, __('Vendor payment deleted successfully'));
        } catch (\Exception $e) {
            Log::error('Vendor Payment API destroy error', ['e' => $e]);
            return $this->errorResponse(__('Something went wrong'), null, 500);
        }
    }
}
