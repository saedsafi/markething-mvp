<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\LlmLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LlmLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = LlmLog::query()
            ->with([
                'user',
                'campaign',
                'promptVersion',
            ])

            ->when($request->filled('call_type'), function ($query) use ($request) {
                $query->where('call_type', $request->call_type);
            })

            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })

            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })

            ->when($request->filled('campaign_id'), function ($query) use ($request) {
                $query->where('campaign_id', $request->campaign_id);
            })

            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })

            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.logs.index', [
            'logs' => $logs,

            'agencies' => User::query()
                ->where('role', 'agency')
                ->orderBy('name')
                ->get(),

            'campaigns' => Campaign::query()
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}