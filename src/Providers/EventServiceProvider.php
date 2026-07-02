<?php

namespace Zerp\Account\Providers;

use App\Events\ApprovePurchaseReturn;
use App\Events\ApproveSalesReturn;
use App\Events\CreateTransfer;
use App\Events\DefaultData;
use App\Events\DestroyTransfer;
use App\Events\GivePermissionToRole;
use App\Events\PostPurchaseInvoice;
use App\Events\PostSalesInvoice;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Zerp\Account\Listeners\BankAccountFieldUpdate;
use Zerp\Account\Listeners\CreateDebitNoteFromReturn;
use Zerp\Account\Listeners\CreateCreditNoteFromReturn;
use Zerp\Account\Listeners\UpdateMobileServicePaymentStatusLis;
use Zerp\Account\Listeners\DataDefault;
use Zerp\Account\Listeners\PostPurchaseInvoiceListener;
use Zerp\Account\Listeners\CreateTransferListener;
use Zerp\Account\Listeners\DestroyTransferListener;
use Zerp\Account\Listeners\GiveRoleToPermission;
use Zerp\Account\Listeners\PostSalesInvoiceListener;
use Zerp\Account\Listeners\UpdateRetainerPaymentStatusListener;
use Workdo\Retainer\Events\UpdateRetainerPaymentStatus;
use Zerp\Account\Listeners\UpdateCommissionPaymentStatusListener;
use Workdo\Commission\Events\UpdateCommissionPaymentStatus;
use Zerp\Account\Listeners\PaySalaryListener;
use Zerp\Hrm\Events\PaySalary;
use Zerp\Account\Listeners\CreatePosListener;
use Workdo\Fleet\Events\MarkFleetBookingPaymentPaid;
use Workdo\MobileServiceManagement\Events\UpdateMobileServicePaymentStatus;
use Zerp\Pos\Events\CreatePos;
use Zerp\Account\Listeners\MarkFleetBookingPaymentPaidListener;
use Workdo\Fleet\Events\CraeteFleetBookingPayment;
use Workdo\MobileServiceManagement\Events\CreateMobileServicePayment;
use Zerp\Account\Listeners\BeautyBookingPaymentListener;
use Workdo\DairyCattleManagement\Events\CreateDairyCattlePayment;
use Workdo\DairyCattleManagement\Events\UpdateDairyCattlePaymentStatus;
use Zerp\Paypal\Events\BeautyBookingPaymentPaypal;
use Zerp\Stripe\Events\BeautyBookingPaymentStripe;
use Zerp\Account\Listeners\UpdateDairyCattlePaymentStatusListener;
use Workdo\CateringManagement\Events\CreateCateringOrderPayment;
use Workdo\CateringManagement\Events\UpdateCateringOrderPaymentStatus;
use Zerp\Account\Listeners\UpdateCateringOrderPaymentStatusListener;
use Zerp\Account\Listeners\UpdatePropertyPaymentStatusListener;
use Zerp\Account\Listeners\UpdateSalesAgentCommissionPaymentStatusLis;
use Zerp\Account\Listeners\ApproveSalesAgentCommissionAdjustmentLis;
use Zerp\Account\Listeners\ConvertSalesRetainerListener;
use Workdo\Commission\Events\CreateCommissionPayment;
use Workdo\PropertyManagement\Events\CreatePropertyPayment;
use Workdo\PropertyManagement\Events\UpdatePropertyPaymentStatus;
use Zerp\Hrm\Events\CreatePayroll;
use Zerp\Hrm\Events\UpdatePayroll;
use Workdo\Retainer\Events\ConvertSalesRetainer;
use Workdo\SalesAgent\Events\CreateSalesAgentCommissionPayment;
use Workdo\SalesAgent\Events\UpdateSalesAgentCommissionPaymentStatus;
use Workdo\SalesAgent\Events\ApproveSalesAgentCommissionAdjustment;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Add your event listeners here
        DefaultData::class => [
            DataDefault::class,
        ],
        GivePermissionToRole::class => [
            GiveRoleToPermission::class,
        ],
        PostPurchaseInvoice::class => [
            PostPurchaseInvoiceListener::class,
        ],
        PostSalesInvoice::class => [
            PostSalesInvoiceListener::class,
        ],
        CreateTransfer::class => [
            CreateTransferListener::class,
        ],
        DestroyTransfer::class => [
            DestroyTransferListener::class,
        ],
        ApprovePurchaseReturn::class => [
            CreateDebitNoteFromReturn::class,
        ],
        ApproveSalesReturn::class => [
            CreateCreditNoteFromReturn::class,
        ],
        UpdateRetainerPaymentStatus::class => [
            UpdateRetainerPaymentStatusListener::class,
        ],
        ConvertSalesRetainer::class => [
            ConvertSalesRetainerListener::class,
        ],
        CreateCommissionPayment::class => [
            BankAccountFieldUpdate::class,
        ],
        UpdateCommissionPaymentStatus::class => [
            UpdateCommissionPaymentStatusListener::class,
        ],
        PaySalary::class => [
            PaySalaryListener::class,
        ],
        CreatePos::class => [
            BankAccountFieldUpdate::class,
            CreatePosListener::class,
        ],
        CreateMobileServicePayment::class => [
            BankAccountFieldUpdate::class,
        ],
        UpdateMobileServicePaymentStatus::class => [
            UpdateMobileServicePaymentStatusLis::class,
        ],
        CraeteFleetBookingPayment::class => [
            BankAccountFieldUpdate::class,
        ],
        MarkFleetBookingPaymentPaid::class => [
            MarkFleetBookingPaymentPaidListener::class,
        ],
        BeautyBookingPaymentStripe::class => [
            BeautyBookingPaymentListener::class,
        ],
        BeautyBookingPaymentPaypal::class => [
            BeautyBookingPaymentListener::class,
        ],
        CreateDairyCattlePayment::class => [
            BankAccountFieldUpdate::class,
        ],
        UpdateDairyCattlePaymentStatus::class => [
            UpdateDairyCattlePaymentStatusListener::class,
        ],
        CreateCateringOrderPayment::class => [
            BankAccountFieldUpdate::class,
        ],
        UpdateCateringOrderPaymentStatus::class => [
            UpdateCateringOrderPaymentStatusListener::class,
        ],
        CreatePropertyPayment::class => [
            BankAccountFieldUpdate::class,
        ],
        CreatePayroll::class => [
            BankAccountFieldUpdate::class,
        ],
        UpdatePayroll::class => [
            BankAccountFieldUpdate::class,
        ],
        CreateSalesAgentCommissionPayment::class => [
            BankAccountFieldUpdate::class,
        ],
        UpdateSalesAgentCommissionPaymentStatus::class => [
            UpdateSalesAgentCommissionPaymentStatusLis::class,
        ],
        ApproveSalesAgentCommissionAdjustment::class => [
            ApproveSalesAgentCommissionAdjustmentLis::class,
        ],

    ];
}
