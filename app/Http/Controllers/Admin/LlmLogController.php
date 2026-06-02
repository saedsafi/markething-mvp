<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LlmLog;
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

            ->when(
                $request->filled('call_type'),
                fn ($query) =>
                    $query->where('call_type', $request->call_type)
            )

            ->when(
                $request->filled('status'),
                fn ($query) =>
                    $query->where('status', $request->status)
            )

            ->latest()

            ->paginate(20);

        return view('admin.logs.index', [
            'logs' => $logs,
        ]);
    }
}