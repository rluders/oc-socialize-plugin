<?php

namespace RLuders\Socialize\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFriendshipGroupsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'rluders_socialize_friendship_groups',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->integer('friendship_id')->unsigned();
                $table->morphs('friend');
                $table->integer('group_id')->unsigned();
                $table->foreign('friendship_id')
                    ->references('id')
                    ->on('rluders_socialize_friendships')
                    ->onDelete('cascade');
                $table->unique(['friendship_id', 'friend_id', 'friend_type', 'group_id'], 'unique');
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('rluders_socialize_friendship_groups');
    }
}
