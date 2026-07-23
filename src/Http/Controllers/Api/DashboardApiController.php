<?php

namespace Zerp\Account\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zerp\Account\Models\Customer;
use Zerp\Account\Models\Vendor;
use Zerp\Account\Models\CustomerPayment;
use Zerp\Account\Models\VendorPayment;
use Zerp\Account\Models\Revenue;
use Zerp\Account\Models\Expense;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            if (!Auth::user()->can('manage-account-dashboard')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $creatorId = creatorId();

            $totalClients = Customer::where('created_by', $creatorId)->count();
            $totalVendors = Vendor::where('created_by', $creatorId)->count();
            $totalRevenue = (float) Revenue::where('created_by', $creatorId)->sum('amount');
            $totalExpense = (float) Expense::where('created_by', $creatorId)->sum('amount');

            $totalCustomerPayments = (float) CustomerPayment::whereHas('customer', function($q) use ($creatorId) {
                $q->where('created_by', $creatorId);
            })->sum('payment_amount');

            $totalVendorPayments = (float) VendorPayment::whereHas('vendor', function($q) use ($creatorId) {
                $q->where('created_by', $creatorId);
            })->sum('payment_amount');

            $netProfit = $totalRevenue - $totalExpense;

            $recentRevenues = Revenue::where('created_by', $creatorId)
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id' => $item->id,
                    'title' => $item->revenue_number ?? 'REV-' . $item->id,
                    'description' => $item->description ?? 'Revenue transaction',
                    'amount' => (float) $item->amount,
                    'date' => $item->created_at?->format('Y-m-d H:i:s'),
                ]);

            $recentExpenses = Expense::where('created_by', $creatorId)
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id' => $item->id,
                    'title' => $item->expense_number ?? 'EXP-' . $item->id,
                    'description' => $item->description ?? 'Expense transaction',
                    'amount' => (float) $item->amount,
                    'date' => $item->created_at?->format('Y-m-d H:i:s'),
                ]);

            $monthlyCustomerPayments = [];
            $monthlyVendorPayments = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthName = $date->format('M');

                $customerPayments = (float) CustomerPayment::whereHas('customer', function($q) use ($creatorId) {
                    $q->where('created_by', $creatorId);
                })
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('payment_amount');

                $vendorPayments = (float) VendorPayment::whereHas('vendor', function($q) use ($creatorId) {
                    $q->where('created_by', $creatorId);
                })
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('payment_amount');

                $monthlyCustomerPayments[] = [
                    'month' => $monthName,
                    'customer_payments' => $customerPayments
                ];

                $monthlyVendorPayments[] = [
                    'month' => $monthName,
                    'vendor_payments' => $vendorPayments
                ];
            }

            return $this->successResponse([
                'stats' => [
                    'total_clients' => $totalClients,
                    'total_vendors' => $totalVendors,
                    'total_revenue' => $totalRevenue,
                    'total_expense' => $totalExpense,
                    'total_customer_payment' => $totalCustomerPayments,
                    'total_vendor_payment' => $totalVendorPayments,
                    'net_profit' => $netProfit
                ],
                'monthlyCustomerPayments' => $monthlyCustomerPayments,
                'monthlyVendorPayments' => $monthlyVendorPayments,
                'recentRevenues' => $recentRevenues,
                'recentExpenses' => $recentExpenses
            ], __('Dashboard retrieved successfully'));
        } catch (\Exception $e) {
            return $this->errorResponse(__('Something went wrong'), $e->getMessage(), 500);
        }
    }
}
