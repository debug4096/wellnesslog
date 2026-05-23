<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetDailyEntryRequest;
use App\Http\Requests\StoreDailyEntryRequest;
use App\Http\Requests\UpdateDailyEntryRequest;
use App\Http\Resources\DailyEntryResource;
use App\Models\DailyEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

class DailyEntryController extends Controller
{
    #[OA\Get(
        path: '/api/entries',
        summary: 'List authenticated user daily entries',
        security: [['sanctum' => []]],
        tags: ['Daily Entries'],
        parameters: [
            new OA\Parameter(
                name: 'date_from',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-05-01'),
            ),
            new OA\Parameter(
                name: 'date_to',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-05-31'),
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
                description: 'Paginated list of daily entries',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/DailyEntry'),
                        ),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
    public function index(GetDailyEntryRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', DailyEntry::class);

        $validated = $request->validated();

        $entries = $request->user()
            ->dailyEntries()
            ->when($validated['date_from'] ?? null, fn ($q, $date) => $q->dateFrom($date))
            ->when($validated['date_to'] ?? null, fn ($q, $date) => $q->dateTo($date))
            ->orderByDesc('date')
            ->paginate(15);

        return DailyEntryResource::collection($entries);
    }

    #[OA\Post(
        path: '/api/entries',
        summary: 'Create a new daily entry',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['date', 'mood_level', 'energy_level'],
                properties: [
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-05-20'),
                    new OA\Property(property: 'mood_level', type: 'integer', example: 7, enum: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    ),
                    new OA\Property(property: 'energy_level', type: 'integer', example: 6, enum: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    ),
                    new OA\Property(
                        property: 'sleep_minutes',
                        type: 'integer',
                        example: 450,
                        nullable: true,
                        maximum: 1440,
                        minimum: 0,
                    ),
                    new OA\Property(
                        property: 'notes',
                        type: 'string',
                        example: 'Slept well.',
                        nullable: true,
                        maxLength: 5000,
                    ),
                ],
            ),
        ),
        tags: ['Daily Entries'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Daily entry created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/DailyEntry'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
    public function store(StoreDailyEntryRequest $request): JsonResponse
    {
        Gate::authorize('create', DailyEntry::class);

        $entry = $request->user()
            ->dailyEntries()
            ->create($request->validated());

        return (new DailyEntryResource($entry))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/entries/{entry}',
        summary: 'Get a single daily entry',
        security: [['sanctum' => []]],
        tags: ['Daily Entries'],
        parameters: [
            new OA\Parameter(
                name: 'entry',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daily entry data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/DailyEntry'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ],
    )]
    public function show(DailyEntry $entry): DailyEntryResource
    {
        Gate::authorize('view', $entry);

        return new DailyEntryResource($entry);
    }

    #[OA\Put(
        path: '/api/entries/{entry}',
        summary: 'Update a daily entry',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-05-20'),
                    new OA\Property(property: 'mood_level', type: 'integer', example: 8, enum: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    ),
                    new OA\Property(property: 'energy_level', type: 'integer', example: 7, enum: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    ),
                    new OA\Property(
                        property: 'sleep_minutes',
                        type: 'integer',
                        example: 480,
                        nullable: true,
                        maximum: 1440,
                        minimum: 0,
                    ),
                    new OA\Property(
                        property: 'notes',
                        type: 'string',
                        example: 'Updated note.',
                        nullable: true,
                        maxLength: 5000,
                    ),
                ],
            ),
        ),
        tags: ['Daily Entries'],
        parameters: [
            new OA\Parameter(
                name: 'entry',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daily entry updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/DailyEntry'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
    public function update(UpdateDailyEntryRequest $request, DailyEntry $entry): DailyEntryResource
    {
        Gate::authorize('update', $entry);

        $entry->update($request->validated());

        return new DailyEntryResource($entry);
    }

    #[OA\Delete(
        path: '/api/entries/{entry}',
        summary: 'Delete a daily entry',
        security: [['sanctum' => []]],
        tags: ['Daily Entries'],
        parameters: [
            new OA\Parameter(
                name: 'entry',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Daily entry deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ],
    )]
    public function destroy(DailyEntry $entry): JsonResponse
    {
        Gate::authorize('delete', $entry);

        $entry->delete();

        return response()->json(null, 204);
    }
}
