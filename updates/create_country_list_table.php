<?php namespace Codalia\Profile\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCountryListTable extends Migration
{
    public function up()
    {
        Schema::create('codalia_profile_country_list', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('alpha_2', 2)->nullable();
            $table->char('alpha_3', 3)->nullable();
            $table->smallInteger('numerical')->unsigned()->nullable();
            $table->char('continent_code', 2)->nullable();
            $table->boolean('citizenship')->nullable();
            $table->boolean('published')->nullable();
            $table->char('fr', 3)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('codalia_profile_country_list');
    }
}
