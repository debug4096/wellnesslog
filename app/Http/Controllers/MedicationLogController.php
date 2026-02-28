<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationLogRequest;
use App\Http\Resources\MedicationLogResource;
use App\Models\Medication;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MedicationLogController extends Controller
{
    public function index(Medication $medication): AnonymousResourceCollection
    {
        $this->authorize('view', $medication);

        $medicationLogs = $medication->medicationLogs()
            ->paginate(15);

        return MedicationLogResource::collection($medicationLogs);
    }

    public function store(StoreMedicationLogRequest $request, Medication $medication): MedicationLogResource
    {
        $medicationLog = $medication->medicationLogs()
            ->create($request->validated());

        return new MedicationLogResource($medicationLog);
    }
}
