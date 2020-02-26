<?php

namespace Orchestra\Publisher\Console;

use Orchestra\Publisher\Publishing\View;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewPublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'publish:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a package's views to the application";

    /**
     * The view publisher instance.
     *
     * @var \Orchestra\Publisher\Publishing\View
     */
    protected $view;

    /**
     * Create a new view publish command instance.
     *
     * @param  \Orchestra\Publisher\Publishing\View  $view
     */
    public function __construct(View $view)
    {
        parent::__construct();

        $this->view = $view;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $package = $this->input->getArgument('package');

        if (! \is_null($path = $this->getPath())) {
            $this->view->publish($package, $path);
        } else {
            $this->view->publishPackage($package);
        }

        $this->output->writeln('<info>Views published for package:</info> '.$package);

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::REQUIRED, 'The name of the package being published.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['path', null, InputOption::VALUE_OPTIONAL, 'The path to the source view files.', null],
        ];
    }
}
