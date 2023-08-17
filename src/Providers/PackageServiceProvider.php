<?php

namespace Norotaro\EnumataHistory\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Norotaro\Enumata\Events\TransitionedState;
use Norotaro\EnumataHistory\Listeners\RecordStateHistory;

final class PackageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Event::listen(TransitionedState::class, [RecordStateHistory::class, 'handle']);
    }
}
