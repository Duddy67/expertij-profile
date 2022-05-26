<?php namespace Codalia\Profile\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('codalia_profile_profiles', function (Blueprint $table) {
            $table->char('civility', 3)->nullable()->after('user_id');
            $table->string('birth_name')->nullable()->after('last_name');
            $table->date('birth_date')->nullable()->after('birth_name');
            $table->string('birth_location', 30)->nullable()->after('birth_date');
            $table->char('citizenship', 2)->nullable()->after('birth_location');
            $table->string('phone', 15)->nullable()->after('country');
            $table->boolean('honorary_member')->default(0)->after('phone');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('codalia_profile_profiles', 'civility')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('civility');
            });
        }

        if (Schema::hasColumn('codalia_profile_profiles', 'birth_name')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('birth_name');
            });
        }

        if (Schema::hasColumn('codalia_profile_profiles', 'birth_date')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('birth_date');
            });
        }

        if (Schema::hasColumn('codalia_profile_profiles', 'birth_location')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('birth_location');
            });
        }

        if (Schema::hasColumn('codalia_profile_profiles', 'citizenship')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('citizenship');
            });
        }

        if (Schema::hasColumn('codalia_profile_profiles', 'phone')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('phone');
            });
        }

        if (Schema::hasColumn('codalia_profile_profiles', 'honorary_member')) {
            Schema::table('codalia_profile_profiles', function($table)
            {
                $table->dropColumn('honorary_member');
            });
        }
    }
}
