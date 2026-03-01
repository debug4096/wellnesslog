<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\UpdateMedicationRequest;
use App\Http\Resources\MedicationResource;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MedicationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $medications = $request->user()
            ->medications()
            ->paginate(15);

        return MedicationResource::collection($medications);
    }

    public function store(StoreMedicationRequest $request): JsonResponse
    {
        $medication = $request->user()
            ->medications()
            ->create($request->validated());

        return (new MedicationResource($medication))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Medication $medication): MedicationResource
    {
        $this->authorize('view', $medication);

        return new MedicationResource($medication);
    }

    public function update(UpdateMedicationRequest $request, Medication $medication): MedicationResource
    {
        $medication->update($request->validated());

        return new MedicationResource($medication);
    }

    public function destroy(Request $request, Medication $medication): JsonResponse
    {
        $this->authorize('delete', $medication);

        $medication->delete();

        return response()->json(null, 204);
    }
}
