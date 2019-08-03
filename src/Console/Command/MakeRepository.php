<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 5:45 PM
 */

namespace Foris\LaExtension\Console\Command;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class for make repository commands.
 */
class MakeRepository extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('model')) {
            return __DIR__ . '/Stubs/CrudRepository.stub';
        }

        return __DIR__ . '/Stubs/Repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . config('app-ext.file_path.repositories');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        return $this->replaceModel(parent::buildClass($name));
    }

    /**
     * Replace the model name for the given stub.
     *
     * @param      string  $stub   The stub
     *
     * @return     string
     */
    protected function replaceModel($stub)
    {
        if ($model = $this->option('model')) {
            $fullClassName = str_replace('/', '\\', $model);
            $classNameArr  = explode('\\', $fullClassName);

            $stub = str_replace('NamespacedDummyModel', $fullClassName, $stub);
            return str_replace('DummyModel', end($classNameArr), $stub);
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
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Generate a resource repository for the given model'],
        ];
    }
}
