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

class DailyEntryController extends Controller
{
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

    public function show(DailyEntry $entry): DailyEntryResource
    {
        Gate::authorize('view', $entry);

        return new DailyEntryResource($entry);
    }

    public function update(UpdateDailyEntryRequest $request, DailyEntry $entry): DailyEntryResource
    {
        Gate::authorize('update', $entry);

        $entry->update($request->validated());

        return new DailyEntryResource($entry);
    }

    public function destroy(DailyEntry $entry): JsonResponse
    {
        Gate::authorize('delete', $entry);

        $entry->delete();

        return response()->json(null, 204);
    }
}
