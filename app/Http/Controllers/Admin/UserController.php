<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IssueTemporaryPasswordRequest;
use App\Http\Requests\Admin\StoreAgencyUserRequest;
use App\Http\Requests\Admin\UpdateAgencyUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where('role', 'agency')
            ->withCount(['clients', 'campaigns'])
            ->latest()
            ->get();

        return view('admin.dashboard', [
            'users' => $users,
            'totalAgencies' => $users->count(),
            'activeAccounts' => $users->where('status', 'active')->count(),
            'inactiveAccounts' => $users->where('status', 'inactive')->count(),
        ]);
    }

    public function store(StoreAgencyUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->temporary_password,
            'role' => 'agency',
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
}