<?php

namespace Orchestra\Canvas\Commands\Database;

use Illuminate\Support\Composer;
use Orchestra\Canvas\Core\Commands\Generator;
use Orchestra\Canvas\Processors\GeneratesSeederCode;

class Seeder extends Generator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Seeder';

    /**
     * Generator processor.
     *
     * @var string
     */
    protected $processor = GeneratesSeederCode::class;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->composer = new Composer($this->preset->filesystem(), $this->preset->basePath());
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className): int
    {
        $exitCode = parent::codeHasBeenGenerated($className);

        $this->composer->dumpAutoloads();

        return $exitCode;
    }

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        return __DIR__.'/../../../storage/database/seeds/seeder.stub';
    }
}
