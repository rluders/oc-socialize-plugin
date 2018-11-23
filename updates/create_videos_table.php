<?php

namespace RLuders\Socialize\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create(
            'rluders_socialize_videos',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('title');
                $table->string('description');
                $table->enum('status', ['draft', 'published']);
                $table->enum('privacy', ['private', 'everyone', 'friends', 'subscribers'])->default('private');
                $table->unsignedInteger('user_id');                
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('rluders_socialize_videos');
    }
}
