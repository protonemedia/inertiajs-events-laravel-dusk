<?php declare(strict_types=1);

namespace ProtoneMedia\InertiaJsEventsLaravelDusk\Tests;

use Facebook\WebDriver\Exception\TimeoutException;
use Laravel\Dusk\Browser;
use Mockery;
use Orchestra\Testbench\TestCase;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \ProtoneMedia\InertiaJsEventsLaravelDusk\ServiceProvider::class,
        ];
    }

    /** @test */
    public function it_adds_three_macros()
    {
        $this->assertTrue(Browser::hasMacro('waitForInertiaError'));
        $this->assertTrue(Browser::hasMacro('waitForInertiaNavigate'));
        $this->assertTrue(Browser::hasMacro('waitForInertiaSuccess'));
    }

    /** @test */
    public function it_throws_an_exception_if_the_count_doesnt_increase()
    {
        $driver = Mockery::mock(Driver::class);

        $driver->shouldReceive('executeScript')
            ->with('return window.inertiaEventsCount.errorCount;')
            ->once()
            ->andReturn(0);

        $driver->shouldReceive('executeScript')
            ->with('return window.inertiaEventsCount.errorCount > 0;')
            ->andReturn(0);

        $browser = new Browser($driver);

        try {
            $browser->waitForInertiaError(0.1); // wait for 0.1 seconds
        } catch (TimeoutException $e) {
            return $this->assertTrue(true);
        }

        $this->fail('Should have thrown TimeoutException');
    }

    /** @test */
    public function it_passes_when_the_count_increases()
    {
        $driver = Mockery::mock(Driver::class);

        $driver->shouldReceive('executeScript')
            ->with('return window.inertiaEventsCount.errorCount;')
            ->once()
            ->andReturn(0);

        $driver->shouldReceive('executeScript')
            ->with('return window.inertiaEventsCount.errorCount > 0;')
            ->once()
            ->andReturn(1);

        $browser = new Browser($driver);
        $browser->waitForInertiaError(1);

        $this->assertTrue(true);
    }
}
