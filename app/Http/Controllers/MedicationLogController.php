<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationLogRequest;
use App\Http\Resources\MedicationLogResource;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

class MedicationLogController extends Controller
{
    #[OA\Get(
        path: '/api/medications/{medication}/logs',
        summary: 'List intake logs for a medication',
        security: [['sanctum' => []]],
        tags: ['Medication Logs'],
        parameters: [
            new OA\Parameter(
                name: 'medication',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
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
                description: 'Paginated list of medication intake logs',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/MedicationLog'),
                        ),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ],
    )]
    public function index(Medication $medication): AnonymousResourceCollection
    {
        Gate::authorize('viewLogs', $medication);

        $medicationLogs = $medication->medicationLogs()
            ->orderByDesc('taken_at')
            ->paginate(15);

        return MedicationLogResource::collection($medicationLogs);
    }

    #[OA\Post(
        path: '/api/medications/{medication}/logs',
        summary: 'Record a medication intake',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['taken_at'],
                properties: [
                    new OA\Property(
                        property: 'taken_at',
                        description: 'Intake time; must not be in the future',
                        type: 'string',
                        format: 'date-time',
                        example: '2026-05-28T09:00:00Z',
                    ),
                    new OA\Property(
                        property: 'dosage',
                        description: 'Dosage amount; up to 3 decimal places',
                        type: 'number',
                        example: 0.125,
                        maximum: 999.999,
                        minimum: 0.001,
                    ),
                    new OA\Property(
                        property: 'notes', type: 'string', example: 'Taken with breakfast.', maxLength: 5000,
                    ),
                ],
            ),
        ),
        tags: ['Medication Logs'],
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
                response: 201,
                description: 'Medication intake recorded',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/MedicationLog'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
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
