<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationLogRequest;
use App\Http\Resources\MedicationLogResource;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class MedicationLogController extends Controller
{
    public function index(Medication $medication): AnonymousResourceCollection
    {
        Gate::authorize('viewLogs', $medication);

        $medicationLogs = $medication->medicationLogs()
            ->orderByDesc('taken_at')
            ->paginate(15);

        return MedicationLogResource::collection($medicationLogs);
    }

    public function store(StoreMedicationLogRequest $request, Medication $medication): JsonResponse
    {
        Gate::authorize('log', $medication);

        $medicationLog = $medication->medicationLogs()
            ->create($request->validated());

        return (new MedicationLogResource($medicationLog))
            ->response()
            ->setStatusCode(201);
    }
}
