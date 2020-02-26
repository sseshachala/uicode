<?php

namespace Orchestra\Canvas\Commands\Database;

use Orchestra\Canvas\Core\Commands\Generator;
use Orchestra\Canvas\Processors\GeneratesObserverCode;
use Symfony\Component\Console\Input\InputOption;

class Observer extends Generator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Observer';

    /**
     * Generator processor.
     *
     * @var string
     */
    protected $processor = GeneratesObserverCode::class;

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        $directory = __DIR__.'/../../../storage/database/eloquent';

        return $this->option('model')
            ? "{$directory}/observer.stub"
            : "{$directory}/observer.plain.stub";
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Observers';
    }

    /**
     * Generator options.
     */
    public function generatorOptions(): array
    {
        return [
            'model' => $this->option('model'),
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the observer applies to.'],
        ];
    }
}
