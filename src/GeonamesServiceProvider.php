<?php
/**
 *     This is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * User: Evren Yurtesen
 * Date: 04-Jul-16
 * Time: 2:16 PM
 */

namespace Yurtesen\Geonames;

use Illuminate\Support\ServiceProvider;

class GeonamesServiceProvider extends ServiceProvider
{
    /**
     * Artisan commands
     *
     * @var array
     */
    protected $serviceCommands = [
        'Yurtesen\Geonames\Console\Download',
        'Yurtesen\Geonames\Console\Install',
        'Yurtesen\Geonames\Console\Seed',
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/config/geonames.php' => config_path('geonames.php')
        ], 'config');

        $this->mergeConfigFrom(realpath(__DIR__ . '/config/geonames.php'), 'geonames');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->serviceCommands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->serviceCommands;
    }

}