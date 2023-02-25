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
        Schema::create('category_comic', function (Blueprint $table) {
            $table->foreignId('comic_id')->constrained('comics');
            $table->foreignId('category_id')->constrained('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_comic', function (Blueprint $table) {
            $table->dropForeign('category_comic_comic_id_foreign');
            $table->dropForeign('category_comic_category_id_foreign');
        });
        Schema::dropIfExists('category_comic');
    }
};
