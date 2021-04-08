<?php declare(strict_types=1);

namespace ProtoneMedia\InertiaJsEventsLaravelDusk;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;

class ServiceProvider extends SupportServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (class_exists(Browser::class)) {
            $this->addMacrosForInertiaEvents();
        }
    }

    /**
     * Add macros for all events.
     *
     * @return void
     */
    public function addMacrosForInertiaEvents()
    {
        $events = ['error', 'navigate', 'success'];

        foreach ($events as $event) {
            $this->addWaitForInertiaCountMacro($event);
        }
    }

    /**
      * Helper method to get type-hints.
      *
      * @param mixed $browser
      * @return \Laravel\Dusk\Browser
      */
    public static function browser($browser): Browser
    {
        return $browser;
    }

    /**
     * Add a macro for the given event key.
     *
     * @param string $key
     * @return void
     */
    private function addWaitForInertiaCountMacro(string $key)
    {
        Browser::macro('waitForInertia' . Str::studly($key), function ($seconds = null) use ($key): Browser {
            $jsKey = "{$key}Count";

            $browser = ServiceProvider::browser($this);
            $driver = $browser->driver;

            $currentCount = $driver->executeScript("return window.inertiaEventsCount.{$jsKey};");

            return $browser->waitUsing($seconds, 100, function () use ($currentCount, $jsKey, $driver) {
                return $driver->executeScript("return window.inertiaEventsCount.{$jsKey} > {$currentCount};");
            }, "Waited %s seconds for Inertia.js to increase the {$jsKey}.");
        });
    }
}
