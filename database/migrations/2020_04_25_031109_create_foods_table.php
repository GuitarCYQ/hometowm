<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attr')->default('')->comment('图片地址');
            $table->string('name')->default('')->comment('美食名称');
            $table->text('introduce')->nullable()->comment('介绍');
            $table->string('storage')->default('')->comment('储存方法');
            $table->string('cradle')->default('')->comment('发源地');
            $table->string('invention_time')->default('')->comment('发明时间');
            $table->string('taste')->default('')->comment('味道');
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
        Schema::dropIfExists('foods');
    }
}
