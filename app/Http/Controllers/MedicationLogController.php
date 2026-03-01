<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationLogRequest;
use App\Http\Resources\MedicationLogResource;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MedicationLogController extends Controller
{
    public function index(Medication $medication): AnonymousResourceCollection
    {
        $this->authorize('viewLogs', $medication);

        $medicationLogs = $medication->medicationLogs()
            ->paginate(15);

        return MedicationLogResource::collection($medicationLogs);
    }

    public function store(StoreMedicationLogRequest $request, Medication $medication): JsonResponse
    {
        $medicationLog = $medication->medicationLogs()
            ->create($request->validated());

        return (new MedicationLogResource($medicationLog))
            ->response()
            ->setStatusCode(201);
    }
}
