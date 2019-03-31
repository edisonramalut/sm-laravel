<?php

namespace Illuminate\Auth\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class CrudMakeCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
                    {--model=[MODEL] : the model to generate the crud for}
                    {--views : Only scaffold the authentication views}
                    {--force : Overwrite existing views by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the basic crud forms for the existing model.';


    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'layouts/crud.stub' => 'layouts/crud.blade.php'
    ];

    /**
     * The css that need to be exported.
     *
     * @var array
     */
    protected $css = [
        'crud/css.stub' => 'crud.css',
    ];

    /**
     * The js that need to be exported.
     *
     * @var array
     */
    protected $js = [
        'crud/js.stub' => 'crud.js',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('model') == '[MODEL]' || $this->option('model') == '') {
            $this->info('Model name is required to generate the CRUD views.');
            die();
        }

        $modelClass = $this->parseModel($this->option('model'));

        $modelViews = [
            'crud/index.stub' => class_basename($modelClass).'/index.blade.php',
            'crud/update.stub' => class_basename($modelClass).'/update.blade.php',
        ];
        $this->views = array_merge($this->views, $modelViews);

        if (! class_exists($modelClass)) {
            $this->info('A '.$modelClass.' model does not exist.');
            die();
        }

        $this->createDirectories(lcfirst(class_basename($modelClass)));

        $this->exportCss();
        $this->exportJs();
        $this->exportViews($modelClass);


        if (! $this->option('views')) {
            $replace = [
                '{{namespace}}' => $this->getAppNamespace(),
                'Dummy' => class_basename($modelClass),
                'dummy' => lcfirst(class_basename($modelClass))
            ];
            file_put_contents(
                app_path('Http/Controllers/'.class_basename($modelClass).'Controller.php'),
                $this->compileControllerStub($replace)
            );

            $replace = [
                'FullDummy' => class_basename($modelClass),
                'dummy' => lcfirst(class_basename($modelClass))
            ];

            file_put_contents(
                base_path('routes/web.php'),
                $this->compileRoutesStub($replace),
                FILE_APPEND
            );
        }

        $this->call('config:cache');
        $this->call('optimize');

        $this->info('Crud scaffolding generated successfully.');
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
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories($model)
    {
        if (! is_dir($directory = resource_path('views/layouts'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = resource_path('views/'.$model))) {
            mkdir($directory, 0755, true);
        }

    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews($modelClass)
    {
        foreach ($this->views as $key => $value) {
            if (file_exists($view = resource_path('views/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            $replace = [
                'Dummy' => class_basename($modelClass),
                'dummy' => lcfirst(class_basename($modelClass)),
            ];
            file_put_contents(
                $view,
                str_replace(
                    array_keys($replace), array_values($replace),file_get_contents(__DIR__.'/stubs/make/views/'.$key)
                )
            );

        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportCss()
    {
        foreach ($this->css as $key => $value) {
            if (file_exists($view = public_path('css/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] file already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__.'/stubs/make/views/'.$key,
                $view
            );
        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportJs()
    {
        foreach ($this->js as $key => $value) {
            if (file_exists($view = public_path('js/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] file already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            $replace = [
                'Dummy' => class_basename($modelClass),
                'dummy' => lcfirst(class_basename($modelClass)),
            ];
            file_put_contents(
                $view,
                str_replace(
                    array_keys($replace), array_values($replace),file_get_contents(__DIR__.'/stubs/make/views/'.$key)
                )
            );
        }
    }

    /**
     * Compiles the Controller stub.
     *
     * @return string
     */
    protected function compileControllerStub($replace)
    {
        return str_replace(
            array_keys($replace), array_values($replace),file_get_contents(__DIR__.'/stubs/make/controllers/DummyController.stub')
        );
    }

    /**
     * Compiles the routes stub.
     *
     * @return string
     */
    protected function compileRoutesStub($replace)
    {
        return str_replace(
            array_keys($replace), array_values($replace), file_get_contents(__DIR__.'/stubs/make/crud_routes.stub')
        );
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The model of the crud'],
        ];
    }
}
