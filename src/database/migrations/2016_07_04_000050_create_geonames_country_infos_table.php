<?php
/**
 * Created by PhpStorm.
 * User: Evren Yurtesen
 * Date: 04-Jul-16
 * Time: 5:14 PM
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesCountryInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames_country_infos', function (Blueprint $table) {
            $table->char('iso',2)->unique();
            $table->char('iso3',3)->unique();
            $table->char('iso_numeric',3)->unique();
            //$table->smallInteger('iso_numeric')->unsigned();
            $table->char('fips',2)->nullable();
            $table->string('country',60);
            $table->string('capital',40);
            $table->integer('area')->unsigned();
            $table->integer('population')->unsigned()->nullable();
            $table->char('continent_code',2);
            $table->integer('continent_id')->unsigned();
            $table->foreign('continent_id')->references('geoname_id')->on('geonames_geonames')->onUpdate('cascade')->onDelete('cascade');
            $table->char('tld',3)->nullable();
            $table->char('currency_code',3)->nullable();
            $table->string('currency_name',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('postal_code_format',100)->nullable();
            $table->string('postal_code_regex',200)->nullable();
            $table->string('languages',100)->nullable();
            $table->integer('geoname_id')->primary()->unsigned();
            $table->foreign('geoname_id')->references('geoname_id')->on('geonames_geonames')->onUpdate('cascade')->onDelete('cascade');
            $table->string('neighbors',60)->nullable();
            $table->char('equivalent_fips_code',2)->nullable();
        });
        //DB::statement('ALTER TABLE geonames_country_infos CHANGE iso_numeric iso_numeric SMALLINT(3) UNSIGNED ZEROFILL NOT NULL');
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
