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

class CreateGeonamesHierarchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames_hierarchies', function (Blueprint $table) {
            $table->integer('parent_id')->index()->unsigned();
            $table->foreign('parent_id')->references('geoname_id')->on('geonames_geonames')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('child_id')->index()->unsigned();
            $table->foreign('child_id')->references('geoname_id')->on('geonames_geonames')->onUpdate('cascade')->onDelete('cascade');
            $table->string('type',40)->index();
            $table->unique(['parent_id','child_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geonames_hierarchies');
    }
}
