<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 5:44 PM
 */

namespace Foris\LaExtension\Console\Command;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class for make repository commands.
 */
class MakeService extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('repository')) {
            return __DIR__ . '/Stubs/CrudService.stub';
        }

        return __DIR__ . '/Stubs/Service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . config('app-ext.file_path.services');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        return $this->replaceRepository(parent::buildClass($name));
    }

    /**
     * Replace the model name for the given stub.
     *
     * @param      string  $stub   The stub
     *
     * @return     string
     */
    protected function replaceRepository($stub)
    {
        if ($repository = $this->option('repository')) {
            $fullClassName = str_replace('/', '\\', $repository);
            $classNameArr  = explode('\\', $fullClassName);

            $stub = str_replace('NamespacedDummyRepository', $fullClassName, $stub);
            return str_replace('DummyRepository', end($classNameArr), $stub);
        }
        return $stub;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['repository', 'r', InputOption::VALUE_REQUIRED, 'Generate a resource service for the given resource repository'],
        ];
    }
}