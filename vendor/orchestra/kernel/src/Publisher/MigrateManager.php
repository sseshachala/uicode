<?php

namespace Orchestra\Publisher;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Migrations\Migrator;
use Orchestra\Contracts\Publisher\Publisher;

class MigrateManager implements Publisher
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * Migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Construct a new instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @param  \Illuminate\Database\Migrations\Migrator  $migrator
     */
    public function __construct(Container $app, Migrator $migrator)
    {
        $this->app = $app;
        $this->migrator = $migrator;
    }

    /**
     * Create migration repository if it's not available.
     *
     * @return void
     */
    protected function createMigrationRepository(): void
    {
        $repository = $this->migrator->getRepository();

        if (! $repository->repositoryExists()) {
            $repository->createRepository();
        }
    }

    /**
     * Run migration for an extension or application.
     *
     * @param  string  $path
     *
     * @return void
     */
    public function run(string $path): void
    {
        // We need to make sure migration table is available.
        $this->createMigrationRepository();

        $this->migrator->run($path);
    }

    /**
     * Migrate package.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function package(string $name): void
    {
        $files = $this->app->make('files');

        if (\method_exists($this->app, 'vendorPath')) {
            $vendorPath = \rtrim($this->app->vendorPath(), '/');
        } else {
            $basePath = \rtrim($this->app->basePath(), '/');
            $vendorPath = "{$basePath}/vendor";
        }

        $paths = [
            "{$vendorPath}/{$name}/resources/database/migrations/",
            "{$vendorPath}/{$name}/database/migrations/",
            "{$vendorPath}/{$name}/migrations/",
        ];

        foreach ($paths as $path) {
            if ($files->isDirectory($path)) {
                $this->run($path);
            }
        }
    }

    /**
     * Migrate extension.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function extension(string $name): bool
    {
        $files = $this->app->make('files');

        list($basePath, $sourcePath) = $this->getPathFromExtensionName($name);

        $paths = [
            "{$basePath}/resources/database/migrations/",
            "{$basePath}/resources/migrations/",
            "{$basePath}/database/migrations/",
        ];

        // We don't execute the same migration twice, this little code
        // compare both folder before appending the paths.
        if ($basePath !== $sourcePath && ! empty($sourcePath)) {
            $paths = \array_merge($paths, [
                "{$sourcePath}/resources/database/migrations/",
                "{$sourcePath}/resources/migrations/",
                "{$sourcePath}/database/migrations/",
            ]);
        }

        foreach ($paths as $path) {
            if ($files->isDirectory($path)) {
                $this->run($path);
            }
        }

        return true;
    }

    /**
     * Migrate Orchestra Platform.
     *
     * @return bool
     */
    public function foundation(): bool
    {
        $this->package('orchestra/memory');
        $this->package('orchestra/auth');

        return true;
    }

    /**
     * Get path from extension name.
     *
     * @param  string  $name
     *
     * @return array
     */
    protected function getPathFromExtensionName(string $name): array
    {
        $extension = $this->app->make('orchestra.extension');
        $finder = $this->app->make('orchestra.extension.finder');

        if ($name === 'app') {
            $basePath = $sourcePath = $this->app->basePath();
        } else {
            $basePath = $finder->resolveExtensionPath(\rtrim($extension->option($name, 'path'), '/'));
            $sourcePath = $finder->resolveExtensionPath(\rtrim($extension->option($name, 'source-path'), '/'));
        }

        return [$basePath, $sourcePath];
    }
}
