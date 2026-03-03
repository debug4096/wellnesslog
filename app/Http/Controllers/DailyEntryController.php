<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyEntryRequest;
use App\Http\Requests\UpdateDailyEntryRequest;
use App\Http\Resources\DailyEntryResource;
use App\Models\DailyEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DailyEntryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $entries = $request->user()
            ->dailyEntries()
            ->when($request->query('date_from'), fn ($q, $date) => $q->dateFrom($date))
            ->when($request->query('date_to'), fn ($q, $date) => $q->dateTo($date))
            ->orderByDesc('date')
            ->paginate(15);

        return DailyEntryResource::collection($entries);
    }

    public function store(StoreDailyEntryRequest $request): JsonResponse
    {
        $entry = $request->user()
            ->dailyEntries()
            ->create($request->validated());

        return (new DailyEntryResource($entry))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, DailyEntry $entry): DailyEntryResource
    {
        $this->authorize('view', $entry);

        return new DailyEntryResource($entry);
    }

    public function update(UpdateDailyEntryRequest $request, DailyEntry $entry): DailyEntryResource
    {
        $entry->update($request->validated());

        return new DailyEntryResource($entry);
    }

    public function destroy(Request $request, DailyEntry $entry): JsonResponse
    {
        $this->authorize('delete', $entry);

        $entry->delete();

        return response()->json(null, 204);
    }
}
