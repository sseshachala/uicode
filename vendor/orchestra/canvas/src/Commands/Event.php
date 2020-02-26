<?php

namespace Orchestra\Canvas\Commands;

use Orchestra\Canvas\Core\Commands\Generator;
use Orchestra\Canvas\Processors\GeneratesEventCode;

class Event extends Generator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Event';

    /**
     * Generator processor.
     *
     * @var string
     */
    protected $processor = GeneratesEventCode::class;

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        return __DIR__.'/../../storage/event/event.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Events';
    }
}
