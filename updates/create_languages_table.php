<?php namespace Codalia\Profile\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLanguagesTable extends Migration
{
    public function up()
    {
        Schema::create('codalia_profile_languages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('licence_id')->unsigned()->index()->nullable();
            $table->char('alpha_2', 2)->nullable();
            $table->boolean('interpreter')->nullable();
            $table->boolean('translator')->nullable();
            $table->tinyInteger('ordering')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('codalia_profile_languages');
    }
}
