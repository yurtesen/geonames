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

class CreateGeonamesTimezonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames_timezones', function (Blueprint $table) {
            $table->string('timezone_id', 40)->primary();
            $table->char('country_code', 2)->index();
            // We are adding this constraint from geonames_country_infos migration
            //$table->foreign('country_code')->references('iso')->on('geonames_country_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('gmt_offset', 2, 1);
            $table->decimal('dst_offset', 2, 1);
            $table->decimal('raw_offset', 2, 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geonames_timezones');
    }
}
