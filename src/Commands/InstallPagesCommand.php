<?php

namespace Marshmallow\Pages\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallPagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pages:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Marshmallow Pages packages';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->artisanCall(
            'vendor:publish --provider="Marshmallow\Pages\PagesServiceProvider"',
            'Pages is published.'
        );

        $this->artisanCall(
            'vendor:publish --provider="Marshmallow\Nova\Flexible\FieldServiceProvider"',
            'Flexible is published.'
        );

        $this->artisanCall(
            'migrate',
            'Database has been migrated.'
        );

        $this->artisanCall(
            'marshmallow:resource Page Pages',
            'Page Nova resource has been created.'
        );

        $this->addRoutesToWeb();
    }

    protected function addRoutesToWeb()
    {
        $route_file_path = base_path('routes/web.php');
        $routes = file_get_contents($route_file_path);
        if (false !== strpos($routes, 'Page::loadRoutes();') || false !== strpos($routes, 'Page::routes();')) {
            $this->info('Routes file is already updated. No changes there :)');

            return;
        }

        try {
            $routes_file = fopen($route_file_path, 'w');
            $new_content = $routes . "\n\Marshmallow\Pages\Facades\Page::routes();\n";
            fwrite($routes_file, $new_content);
            fclose($routes_file);

            $this->info('"routes/web.php" has been updated.');
        } catch (Exception $e) {
            $this->error(__('There was an error while updating your routes file'));
            $this->info('Please add the method "\Marshmallow\Pages\Facades\Page::routes();"" to "routes/web.php"');
        }
    }

    protected function artisanCall($command, $info = null)
    {
        Artisan::call($command);

        if ($info) {
            $this->info($info);
        }
    }
}
