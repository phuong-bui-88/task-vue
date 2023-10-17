<?php

namespace App\Providers;

use Laravel\Scout\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;


class ScoutMacroServiceProvider extends ServiceProvider
{
    /**
     * Registers the application's scout macros.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('count', function () {
            return $this->engine()->getTotalCount(
                $this->engine()->search($this)
            );
        });
    }
}
