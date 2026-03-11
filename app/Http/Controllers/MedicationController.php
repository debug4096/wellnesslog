<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\UpdateMedicationRequest;
use App\Http\Resources\MedicationResource;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class MedicationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Medication::class);

        $medications = $request->user()
            ->medications()
            ->orderByDesc('created_at')
            ->paginate(15);

        return MedicationResource::collection($medications);
    }

    public function store(StoreMedicationRequest $request): JsonResponse
    {
        Gate::authorize('create', Medication::class);

        $medication = $request->user()
            ->medications()
            ->create($request->validated());

        return (new MedicationResource($medication))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Medication $medication): MedicationResource
    {
        Gate::authorize('view', $medication);

        return new MedicationResource($medication);
    }

    public function update(UpdateMedicationRequest $request, Medication $medication): MedicationResource
    {
        Gate::authorize('update', $medication);

        $medication->update($request->validated());

        return new MedicationResource($medication);
    }

    public function destroy(Medication $medication): JsonResponse
    {
        Gate::authorize('delete', $medication);

        $medication->delete();

        return response()->json(null, 204);
    }
}
