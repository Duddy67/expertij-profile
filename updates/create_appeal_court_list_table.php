<?php namespace Codalia\Profile\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateAppealCourtListTable extends Migration
{
    public function up()
    {
        Schema::create('codalia_profile_appeal_court_list', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
	    $table->string('name')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('codalia_profile_appeal_court_list');
    }
}
