<?php

namespace Norotaro\EnumataRecorder\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Norotaro\Enumata\Events\TransitionedState;
use Norotaro\EnumataRecorder\Listeners\RecordStateHistory;

final class PackageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
