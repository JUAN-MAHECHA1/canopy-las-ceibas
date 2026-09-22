<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferLeadershipRequest;
use App\Models\User;
use App\Services\LeadershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function __construct(private readonly LeadershipService $leadershipService)
    {
    }

    public function index(): View
    {
        return view('admin.guides.index', [
            'guias' => User::guias()->orderBy('name')->get(),
        ]);
    }

    public function setLider(TransferLeadershipRequest $request): RedirectResponse
    {
        $nuevoLider = User::guias()->findOrFail($request->validated('guia_id'));

        $this->leadershipService->transferirA($nuevoLider);

        return back()->with('success', "{$nuevoLider->name} quedó como guía líder.");
    }
}
