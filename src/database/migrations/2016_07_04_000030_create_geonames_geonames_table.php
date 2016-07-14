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
 * Time: 5:14 PM
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesGeonamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames_geonames', function (Blueprint $table) {
            $table->integer('geoname_id')->primary()->unsigned();
            $table->string('name', 200);
            $table->string('ascii_name', 200)->nullable();
            $table->string('alternate_names', 10000)->nullable();
            $table->decimal('latitude', 7, 5)->index();
            $table->decimal('longitude', 8, 5)->index();
            $table->char('feature_class', 1)->nullable();
            $table->string('feature_code', 10)->nullable();
            $table->char('country_code', 2);
            $table->string('cc2', 60)->nullable();
            $table->string('admin1_code', 20)->nullable();
            $table->string('admin2_code', 80)->nullable();
            $table->string('admin3_code', 20)->nullable();
            $table->string('admin4_code', 20)->nullable();
            $table->integer('population')->unsigned()->nullable();
            $table->integer('elevation')->nullable();
            $table->integer('dem')->nullable();
            $table->string('timezone_id', 40)->index()->nullable();
            $table->foreign('timezone_id')->references('timezone_id')->on('geonames_timezones')->onUpdate('cascade')->onDelete('cascade');
            $table->date('modified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geonames_geonames');
    }
}
