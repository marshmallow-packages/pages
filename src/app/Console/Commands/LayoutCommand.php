<?php

namespace Marshmallow\Pages\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LayoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marshmallow:layout
                            {--empty : Create a layout without fields}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new layout and connecting resource';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->prepareFileStructure();

        $this->title = $this->ask('Please provide a name for your layout');

        $slug = str_slug($this->title);
        $upper_camel = ucfirst(Str::camel(str_slug($this->title)));

        $name_examle = $slug;
        $class_examle = (strpos($upper_camel, 'Layout') === false)
                                ? $upper_camel . 'Layout'
                                : $upper_camel;

        $component_example = (strpos($upper_camel, 'Component') === false)
                                ? $upper_camel . 'Component'
                                : $upper_camel;


        $this->name = $this->ask('Please provide a slug for your layout', $name_examle);
        $this->layout_class = $this->ask('Please provide a class name for your layout', $class_examle);
        $this->component_class_path = $this->ask('Please enter a name for you component', '\App\View\Components\\' . $component_example . '::class');

        $this->component_class_name = $this->getComponentClassFromPath($this->component_class_path);


        $this->fields = $this->getStubContent('LayoutFields');
        $this->use = '';

        if ($this->option('empty')) {
            $this->fields = '';
            $this->use = 'use \Marshmallow\Pages\Flexible\Layouts\Traits\EmptyLayout;';
        }

        file_put_contents(
            app_path('Flexible/Layouts/'. $this->layout_class .'.php'),
            $this->parseStubContent('Layout')
        );

        file_put_contents(
            app_path('View/Components/'. $this->component_class_name .'.php'),
            $this->parseStubContent('Component')
        );

        file_put_contents(
            resource_path('views/components/'. $slug .'.blade.php'),
            $this->parseStubContent('View')
        );
    }

    protected function getComponentClassFromPath($path)
    {
        $path = explode('::class', $path);
        $path = $path[0];
        $path = explode('\\', $path);
        return end($path);
    }

    protected function getParams()
    {
        return [
            '{{component_class}}' => $this->component_class_name,
            '{{name}}' => $this->name,
            '{{title}}' => $this->title,
            '{{class}}' => $this->layout_class,
            '{{component_class_path}}' => $this->component_class_path,
            '{{fields}}' => $this->fields,
            '{{use}}' => $this->use,
        ];
    }

    protected function getStubContent($stub_name)
    {
        return file_get_contents(__dir__ . '/../../Stubs/'. $stub_name .'.stub');
    }

    protected function parseStubContent($stub_name)
    {
        $content = $this->getStubContent($stub_name);
        return strtr($content, $this->getParams());
    }

    /**
     * Make sure all folders we need are available
     *
     * @return void
     */
    protected function prepareFileStructure()
    {
        $structure = [
            resource_path('views'),
            resource_path('views/components'),
            app_path('View'),
            app_path('View/Components'),
            app_path('Flexible'),
            app_path('Flexible/Layouts'),
        ];
        foreach ($structure as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder);
            }
        }
    }
}
