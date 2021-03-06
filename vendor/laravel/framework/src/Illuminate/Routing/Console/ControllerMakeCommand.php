<?php

namespace Illuminate\Routing\Console;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:controller';

    /**
     * The form request names to be created when
     * form request option is used.
     *
     * @var string
     */
    protected $formRequests = ["Store", "Update", "Delete"];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($this->option('parent')) {
            $stub = '/stubs/controller.nested.stub';
        } elseif ($this->option('model')) {
            if ($this->option('form-request')) {
                $stub = '/stubs/controller.model.request.stub';
            } else {
                $stub = '/stubs/controller.model.stub';
            }
        } elseif ($this->option('invokable')) {
            $stub = '/stubs/controller.invokable.stub';
        } elseif ($this->option('resource')) {
            $stub = '/stubs/controller.stub';
        } elseif ($this->option('form-request')) {
            if ($this->option('model')) {
                $stub = '/stubs/controller.model.request.stub';
            } else {
                $stub = '/stubs/controller.request.stub';
            }

        }

        if ($this->option('api') && is_null($stub)) {
            $stub = '/stubs/controller.api.stub';
        } elseif ($this->option('api') && ! is_null($stub) && ! $this->option('invokable')) {
            $stub = str_replace('.stub', '.api.stub', $stub);
        }

        $stub = $stub ?? '/stubs/controller.plain.stub';

        return __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    /**
     * Get the default namespace for the form request classes.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultFormRequestNamespace()
    {
        $rootNamespace = $this->laravel->getNamespace();
        return $rootNamespace.'Http\Requests';
    }

    /**
     * Get the default namespace for the model classes.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultModelNamespace()
    {
        $rootNamespace = $this->laravel->getNamespace();
        return $rootNamespace.'Http\Models';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('form-request')) {
            $replace = $this->buildFormRequestReplacements($replace, $name);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    protected function buildParentReplacements()
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if (! class_exists($parentModelClass)) {
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $parentModelClass]);
            }
        }

        return [
            'ParentDummyFullModelClass' => $parentModelClass,
            'ParentDummyModelClass' => class_basename($parentModelClass),
            'ParentDummyModelVariable' => lcfirst(class_basename($parentModelClass)),
        ];
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (! class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $modelClass]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
        ]);
    }

    /**
     * Build the form request replacement values.
     *
     * @param  $replace
     * @param  $name
     * @return void
     */
    protected function buildFormRequestReplacements($replace, $name)
    {
        $storeRequest = $updateRequest = $deleteRequest = '';
        foreach ($this->formRequests as $formRequest) {
            //get the name of the object to use for the Request classes.
            $requestName = $this->getObjectName($this->input->getArgument('name'));
            $formRequestClassName = $formRequest.$requestName;
            //get the full path of the objects so we can reference them correctly in the generated files.
            $fullPathFormRequestClassName = $this->getDefaultFormRequestNamespace().'\\'.$formRequestClassName;
            if ($formRequest == 'Store') {
                $storeRequest = $fullPathFormRequestClassName;
            } elseif ($formRequest == 'Update') {
                $updateRequest = $fullPathFormRequestClassName;
            } elseif ($formRequest == 'Delete') {
                $deleteRequest = $fullPathFormRequestClassName;
            }
            $this->call('make:request', ['name' =>$formRequestClassName]);
        }

        return array_merge($replace, [
            'DummyFullStoreRequest' => $storeRequest,
            'DummyFullUpdateRequest' => $updateRequest,
            'DummyFullDeleteRequest' => $deleteRequest,
            'DummyUpdateRequest' => class_basename($updateRequest),
            'DummyStoreRequest' => class_basename($storeRequest),
            'DummyDeleteRequest' => class_basename($deleteRequest),
        ]);

    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (! Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $this->getDefaultModelNamespace().'\\'.$model;
        }
        return $model;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],
            ['form-request', 'f', InputOption::VALUE_NONE, 'Generate basic Form Request classes for the controller.'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'],
            ['invokable', 'i', InputOption::VALUE_NONE, 'Generate a single method, invokable controller class.'],
            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
        ];
    }
}
