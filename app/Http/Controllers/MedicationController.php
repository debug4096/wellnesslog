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
use OpenApi\Attributes as OA;

class MedicationController extends Controller
{
    #[OA\Get(
        path: '/api/medications',
        description: 'Returns only non-archived medications. Archived (soft-deleted) entries are excluded.',
        summary: 'List authenticated user active medications',
        security: [['sanctum' => []]],
        tags: ['Medications'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1, minimum: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of active medications',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Medication'),
                        ),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ],
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Medication::class);

        $medications = $request->user()
            ->medications()
            ->orderByDesc('created_at')
            ->paginate(15);

        return MedicationResource::collection($medications);
    }

    #[OA\Post(
        path: '/api/medications',
        summary: 'Create a new medication',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'dosage', 'unit', 'frequency'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Aspirin', maxLength: 100, minLength: 3),
                    new OA\Property(
                        property: 'dosage',
                        description: 'Dosage amount; up to 3 decimal places',
                        type: 'number',
                        example: 500,
                        maximum: 999.999,
                        minimum: 0.001,
                    ),
                    new OA\Property(property: 'unit', type: 'string', example: 'mg', enum: ['tablet', 'drop', 'capsule', 'ml', 'mg', 'injection', 'spray', 'patch'],
                    ),
                    new OA\Property(property: 'frequency', type: 'string', example: 'twice_daily', enum: ['once_daily', 'twice_daily', 'three_times_daily', 'every_other_day', 'weekly', 'as_needed'],
                    ),
                    new OA\Property(
                        property: 'reminder_time',
                        type: 'string',
                        pattern: '^([01][0-9]|2[0-3]):[0-5][0-9]$',
                        example: '09:30',
                        nullable: true,
                    ),
                ],
            ),
        ),
        tags: ['Medications'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Medication created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Medication'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
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

    #[OA\Get(
        path: '/api/medications/{medication}',
        summary: 'Get a single medication',
        security: [['sanctum' => []]],
        tags: ['Medications'],
        parameters: [
            new OA\Parameter(
                name: 'medication',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Medication data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Medication'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ],
    )]
    public function show(Medication $medication): MedicationResource
    {
        Gate::authorize('view', $medication);

        return new MedicationResource($medication);
    }

    #[OA\Put(
        path: '/api/medications/{medication}',
        summary: 'Update a medication',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Aspirin', maxLength: 100, minLength: 3),
                    new OA\Property(property: 'dosage', type: 'number', example: 250, maximum: 999.999, minimum: 0.001),
                    new OA\Property(property: 'unit', type: 'string', example: 'mg', enum: ['tablet', 'drop', 'capsule', 'ml', 'mg', 'injection', 'spray', 'patch'],
                    ),
                    new OA\Property(property: 'frequency', type: 'string', example: 'once_daily', enum: ['once_daily', 'twice_daily', 'three_times_daily', 'every_other_day', 'weekly', 'as_needed'],
                    ),
                    new OA\Property(
                        property: 'reminder_time',
                        type: 'string',
                        pattern: '^([01][0-9]|2[0-3]):[0-5][0-9]$',
                        example: '20:00',
                        nullable: true,
                    ),
                ],
            ),
        ),
        tags: ['Medications'],
        parameters: [
            new OA\Parameter(
                name: 'medication',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Medication updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Medication'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
    public function update(UpdateMedicationRequest $request, Medication $medication): MedicationResource
    {
        Gate::authorize('update', $medication);

        $medication->update($request->validated());

        return new MedicationResource($medication);
    }

    #[OA\Delete(
        path: '/api/medications/{medication}',
        description: 'Soft-deletes the medication. The record is preserved for historical statistics but excluded from all subsequent CRUD operations.',
        summary: 'Archive a medication (soft delete)',
        security: [['sanctum' => []]],
        tags: ['Medications'],
        parameters: [
            new OA\Parameter(
                name: 'medication',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Medication archived'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ],
    )]
    public function destroy(Medication $medication): JsonResponse
    {
        Gate::authorize('delete', $medication);

        $medication->delete();

        return response()->json(null, 204);
    }
}
