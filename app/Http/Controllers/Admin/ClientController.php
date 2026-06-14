<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function destroy(Client $client): RedirectResponse
    {
        DB::transaction(function () use ($client) {
            $client->personas()->delete();
            $client->delete();
        });

        return back()->with('success', 'Client profile deleted successfully.');
    }
}