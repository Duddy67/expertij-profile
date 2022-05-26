<?php namespace Codalia\Profile\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('codalia_profile_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
	    $table->integer('checked_out')->unsigned()->nullable()->index();
	    $table->timestamp('checked_out_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('codalia_profile_profiles');
    }
}
