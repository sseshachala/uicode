<?php

namespace Orchestra\Canvas\Commands;

use Orchestra\Canvas\Core\Commands\Generator;
use Orchestra\Canvas\Processors\GeneratesCodeWithMarkdown;
use Symfony\Component\Console\Input\InputOption;

class Notification extends Generator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Notification';

    /**
     * Generator processor.
     *
     * @var string
     */
    protected $processor = GeneratesCodeWithMarkdown::class;

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className): int
    {
        $exitCode = parent::codeHasBeenGenerated($className);

        if ($this->option('markdown')) {
            $this->writeMarkdownTemplate();
        }

        return $exitCode;
    }

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        $directory = __DIR__.'/../../storage/notification';

        return $this->option('markdown')
                ? "{$directory}/markdown.stub"
                : "{$directory}/notification.stub";
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Notifications';
    }

    /**
     * Generator options.
     */
    public function generatorOptions(): array
    {
        return [
            'markdown' => $this->option('markdown') ?? null,
        ];
    }

    /**
     * Write the Markdown template for the mailable.
     */
    protected function writeMarkdownTemplate(): void
    {
        $path = $this->preset->resourcePath().'/views/'.str_replace('.', '/', $this->option('markdown')).'.blade.php';

        if (! $this->files->isDirectory(\dirname($path))) {
            $this->files->makeDirectory(\dirname($path), 0755, true);
        }

        $this->files->put($path, \file_get_contents(__DIR__.'/../../storage/laravel/markdown.stub'));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the notification already exists'],
            ['markdown', 'm', InputOption::VALUE_OPTIONAL, 'Create a new Markdown template for the notification'],
        ];
    }
}
