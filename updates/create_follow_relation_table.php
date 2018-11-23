<?php

namespace RLuders\Socialize\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFollowRelationTable extends Migration
{
    public function up()
    {
        Schema::create(
            'rluders_socialize_follow_relations',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('follower_id');
                $table->string('follower_type');
                $table->string('followee_id');
                $table->string('followee_type');                             
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('rluders_socialize_follow_relations');
    }
}
