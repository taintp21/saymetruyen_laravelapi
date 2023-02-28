<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('another_names', 300)->nullable();
            $table->string('slug', 200);
            $table->string('author', 50);
            $table->text('desc')->nullable();
            $table->string('background_preview', 50);
            $table->string('image_preview', 50);
            $table->tinyInteger('status');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('no action');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropForeign('comics_user_id_foreign');
        });
        Schema::dropIfExists('comics');
    }
};
