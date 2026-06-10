<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IssueTemporaryPasswordRequest;
use App\Http\Requests\Admin\StoreAgencyUserRequest;
use App\Http\Requests\Admin\UpdateAgencyUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->where('role', 'agency')
            ->withCount([
                'clients',
                'campaigns',
            ])
            ->withSum('llmLogs', 'input_tokens')
            ->withSum('llmLogs', 'output_tokens');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        switch ($request->sort) {
            case 'campaigns_desc':
                $query->orderByDesc('campaigns_count');
                break;

            case 'campaigns_asc':
                $query->orderBy('campaigns_count');
                break;

            case 'clients_desc':
                $query->orderByDesc('clients_count');
                break;

            case 'clients_asc':
                $query->orderBy('clients_count');
                break;

            case 'tokens_desc':
                $query->orderByRaw(
                    'COALESCE(llm_logs_sum_input_tokens, 0) + COALESCE(llm_logs_sum_output_tokens, 0) DESC'
                );
                break;

            case 'tokens_asc':
                $query->orderByRaw(
                    'COALESCE(llm_logs_sum_input_tokens, 0) + COALESCE(llm_logs_sum_output_tokens, 0) ASC'
                );
                break;

            case 'name_asc':
                $query->orderBy('name');
                break;

            case 'name_desc':
                $query->orderByDesc('name');
                break;

            case 'oldest':
                $query->oldest();
                break;

            default:
                $query->latest();
                break;
        }

        $users = $query->get();

        $users->each(function ($user) {
            $user->total_tokens_used =
                ($user->llm_logs_sum_input_tokens ?? 0)
                +
                ($user->llm_logs_sum_output_tokens ?? 0);
        });

        $allAgencyUsers = User::query()
            ->where('role', 'agency')
            ->get();

        return view('admin.dashboard', [
            'users' => $users,
            'totalAgencies' => $allAgencyUsers->count(),
            'activeAccounts' => $allAgencyUsers->where('status', 'active')->count(),
            'inactiveAccounts' => $allAgencyUsers->where('status', 'inactive')->count(),
        ]);
    }

    public function store(StoreAgencyUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->temporary_password,
            'role' => 'agency',
            'tier' => 'standard',
            'status' => 'inactive',
            'must_change_password' => true,
            'client_limit' => $request->client_limit,
            'daily_ai_assist_limit' => 50,
        ]);

        return back()->with('success', 'Agency user created successfully.');
    }

    public function show(User $user): View
    {
        abort_if(! $user->isAgency(), 404);

        $user->load([
            'clients.personas',
            'clients.campaigns',
        ]);

        $user->loadCount([
            'clients',
            'campaigns',
        ]);

        return view('admin.users.show', [
            'agencyUser' => $user,
        ]);
    }

    public function update(UpdateAgencyUserRequest $request, User $user): RedirectResponse
    {
        abort_if(! $user->isAgency(), 404);

        $user->update([
            'client_limit' => $request->client_limit,
        ]);

        return back()->with('success', 'Client limit updated successfully.');
    }

    public function issueTemporaryPassword(
        IssueTemporaryPasswordRequest $request,
        User $user
    ): RedirectResponse {
        abort_if(! $user->isAgency(), 404);

        $user->forceFill([
            'password' => $request->temporary_password,
            'must_change_password' => true,
            'status' => 'inactive',
            'password_changed_at' => null,
        ])->save();

        return back()->with('success', 'Temporary password issued successfully.');
    }

    public function suspend(User $user): RedirectResponse
    {
        abort_if(! $user->isAgency(), 404);

        $user->update([
            'status' => 'suspended',
        ]);

        return back()->with('success', 'Agency user suspended successfully.');
    }

    public function reactivate(User $user): RedirectResponse
    {
        abort_if(! $user->isAgency(), 404);

        $user->update([
            'status' => 'active',
            'must_change_password' => false,
        ]);

        return back()->with('success', 'Agency user reactivated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(! $user->isAgency(), 404);

        $user->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Agency user deleted successfully.');
    }
}