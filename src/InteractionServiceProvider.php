<?php

declare(strict_types=1);

namespace LaravelInteraction\Support;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class InteractionServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $interaction;

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    $this->getConfigPath() => config_path($this->interaction . '.php'),
                ],
                $this->interaction . '-config'
            );
            $this->publishes(
                [
                    $this->getMigrationsPath() => database_path('migrations'),
                ],
                $this->interaction . '-migrations'
            );
            if ($this->shouldLoadMigrations()) {
                $this->loadMigrationsFrom($this->getMigrationsPath());
            }
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), $this->interaction);
    }

    protected function getConfigPath(): string
    {
        return $this->path() . '/../config/' . $this->interaction . '.php';
    }

    protected function getMigrationsPath(): string
    {
        return $this->path() . '/../migrations';
    }

    private function shouldLoadMigrations(): bool
    {
        return (bool) config($this->interaction . '.load_migrations');
    }

    private function path(): ?string
    {
        $reflectionClass = new ReflectionClass(static::class);

        $fileName = $reflectionClass->getFileName();
        if ($fileName === false) {
            return null;
        }

        return \dirname($fileName);
    }
}
