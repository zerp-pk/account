<?php

namespace Zerp\Account\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zerp\Account\Models\JournalEntry;

class JournalEntryApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            if (!Auth::user()->can('manage-journal-entry')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $query = JournalEntry::with(['items.account'])
                ->where(function($q) {
                    if (Auth::user()->can('manage-any-journal-entry')) {
                        $q->where('created_by', creatorId());
                    } elseif (Auth::user()->can('manage-own-journal-entry')) {
                        $q->where('creator_id', Auth::id());
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                });

            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('journal_id', 'like', '%' . $request->search . '%')
                      ->orWhere('reference', 'like', '%' . $request->search . '%');
                });
            }
            if ($request->date_from) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->sortSafe($sortField, $sortDirection);

            $entries = $query->paginate($request->get('per_page', 10))->withQueryString();

            return $this->paginatedResponse($entries, __('Journal entries retrieved successfully'));
        } catch (\Exception $e) {
            return $this->errorResponse(__('Something went wrong'), $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            if (!Auth::user()->can('manage-journal-entry')) {
                return $this->errorResponse(__('Permission denied'), null, 403);
            }

            $entry = JournalEntry::with(['items.account'])
                ->where('id', $id)
                ->where(function($q) {
                    if (Auth::user()->can('manage-any-journal-entry')) {
                        $q->where('created_by', creatorId());
                    } elseif (Auth::user()->can('manage-own-journal-entry')) {
                        $q->where('creator_id', Auth::id());
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                })
                ->first();

            if (!$entry) {
                return $this->errorResponse(__('Journal entry not found'), null, 404);
            }

            return $this->successResponse($entry, __('Journal entry details retrieved successfully'));
        } catch (\Exception $e) {
            return $this->errorResponse(__('Something went wrong'), $e->getMessage(), 500);
        }
    }
}
