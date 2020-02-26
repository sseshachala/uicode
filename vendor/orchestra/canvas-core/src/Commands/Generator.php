<?php

namespace Orchestra\Canvas\Core\Commands;

use Orchestra\Canvas\Core\CodeGenerator;
use Orchestra\Canvas\Core\Contracts\GeneratesCodeListener;
use Orchestra\Canvas\Core\GeneratesCode;
use Orchestra\Canvas\Core\Presets\Preset;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Generator extends Command implements GeneratesCodeListener
{
    use CodeGenerator;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type;

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $fileType = 'class';

    /**
     * Generator processor.
     *
     * @var string
     */
    protected $processor = GeneratesCode::class;

    /**
     * Construct a new generator command.
     */
    public function __construct(Preset $preset)
    {
        $this->files = $preset->filesystem();

        parent::__construct($preset);
    }

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this->setName($this->name)
                ->setDescription($this->description)
                ->addArgument('name', InputArgument::REQUIRED, "The name of the {$this->fileType}");

        $this->specifyParameters();
    }

    /**
     * Execute the command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $this->hasOption('force') && $this->option('force') === true;

        return $this->generateCode($force);
    }

    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className): int
    {
        $this->error($this->type.' already exists!');

        return 1;
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className): int
    {
        $this->info($this->type.' created successfully.');

        return 0;
    }

    /**
     * Get the desired class name from the input.
     */
    public function generatorName(): string
    {
        return \trim($this->argument('name'));
    }

    /**
     * Get the desired class name from the input.
     *
     * @deprecated v4.2.0
     */
    protected function getNameInput(): string
    {
        return $this->generatorName();
    }
}
