<?php

namespace RLuders\Socialize\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFriendshipsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'rluders_socialize_friendships',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->morphs('sender');
                $table->morphs('recipient');
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('rluders_socialize_friendships');
    }
}
