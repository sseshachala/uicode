<?php

namespace Orchestra\Foundation\Processors\Extension;

use Orchestra\Contracts\Extension\Command\Viewer as Command;
use Orchestra\Contracts\Extension\Factory;
use Orchestra\Contracts\Extension\Listener\Viewer as Listener;
use Orchestra\Foundation\Processors\Processor;

class Viewer extends Processor implements Command
{
    /**
     * The extension implementation.
     *
     * @var \Orchestra\Contracts\Extension\Factory
     */
    protected $extension;

    /**
     * Construct a new processor.
     *
     * @param \Orchestra\Contracts\Extension\Factory  $extension
     */
    public function __construct(Factory $extension)
    {
        $this->extension = $extension;
    }

    /**
     * View all extension page.
     *
     * @param  \Orchestra\Contracts\Extension\Listener\Viewer $listener
     *
     * @return mixed
     */
    public function view(Listener $listener)
    {
        $data['extensions'] = $this->extension->detect();

        return $listener->showExtensions($data);
    }

    /**
     * Invoke the processor.
     *
     * @param  \Orchestra\Contracts\Extension\Listener\Viewer $listener
     *
     * @return mixed
     */
    public function __invoke(Listener $listener)
    {
        return $this->view($listener);
    }
}
