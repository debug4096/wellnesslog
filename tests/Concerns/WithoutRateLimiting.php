<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait WithoutRateLimiting
{
    protected function setUpWithoutRateLimiting(): void
    {
        $this->withoutMiddleware([
            ThrottleRequests::class,
            ThrottleRequestsWithRedis::class,
        ]);
    }
}
