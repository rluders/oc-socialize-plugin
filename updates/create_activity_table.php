<?php

namespace RLuders\Socialize\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateActivityTable extends Migration
{
    public function up()
    {
        Schema::create(
            'rluders_socialize_activities',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('subject_id');
                $table->string('subject_type');
                $table->string('name');
                $table->unsignedInteger('user_id');                
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('rluders_socialize_activities');
    }
}
