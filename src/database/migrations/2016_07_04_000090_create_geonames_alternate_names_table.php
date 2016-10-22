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
 *     along with this.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * User: Evren Yurtesen
 * Date: 04-Jul-16
 * Time: 5:14 PM
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesAlternateNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames_alternate_names', function (Blueprint $table) {
            $table->integer('alternate_name_id')->primary()->unsigned();
            $table->integer('geoname_id')->index()->unsigned();
            $table->foreign('geoname_id')->references('geoname_id')->on('geonames_geonames')->onUpdate('cascade')->onDelete('cascade');
            $table->string('iso_language', 7)->nullable();
            $table->string('alternate_name', 400)->nullable();
            $table->boolean('isPreferredName');
            $table->boolean('isShortName');
            $table->boolean('isColloquial');
            $table->boolean('isHistoric');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geonames_alternate_names');
    }
}
