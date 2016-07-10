<?php
/**
 * Created by PhpStorm.
 * User: Evren Yurtesen
 * Date: 04-Jul-16
 * Time: 5:14 PM
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesIsoLanguageCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames_iso_language_codes', function (Blueprint $table) {
            $table->char('iso_639_3',3)->primary();
            $table->char('iso_639_2',3)->unique()->nullable();
            $table->char('iso_639_1',2)->unique()->nullable();
            $table->string('language_name', 200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geonames_iso_language_codes');
    }
}
