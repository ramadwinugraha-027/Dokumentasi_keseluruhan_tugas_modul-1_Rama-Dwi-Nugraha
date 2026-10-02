<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Activity;
use App\Services\RegistrationService;
use DomainException;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    protected RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function store(StoreRegistrationRequest $request, Activity $activity): RedirectResponse
    {
        try {
            $this->registrationService->register($activity, $request->validated());

            return back()->with('success', 'Pendaftaran berhasil dikonfirmasi secara atomis.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
