<?php
/**
 * Created by PhpStorm.
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
