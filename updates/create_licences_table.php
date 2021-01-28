<?php namespace Codalia\Profile\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLicencesTable extends Migration
{
    public function up()
    {
        Schema::create('codalia_profile_licences', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('profile_id')->unsigned()->index()->nullable();
            $table->string('type')->nullable();
            $table->integer('appeal_court_id')->unsigned()->nullable();
            $table->integer('court_id')->unsigned()->nullable();
            $table->smallInteger('since')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('codalia_profile_licences');
    }
}
