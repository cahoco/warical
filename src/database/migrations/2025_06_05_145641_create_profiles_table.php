<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('a_name')->nullable();
            $table->date('a_birthday')->nullable();
            $table->text('a_disliked_foods')->nullable();     // ← 追加
            $table->string('b_name')->nullable();
            $table->date('b_birthday')->nullable();
            $table->text('b_disliked_foods')->nullable();     // ← 追加
            $table->date('anniversary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
