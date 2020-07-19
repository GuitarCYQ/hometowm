<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attr')->default('')->comment('图片地址');
            $table->string('title')->default('')->comment('游记标题');
            $table->string('Release')->default('')->comment('发布人');
            $table->string('set_time')->default('')->comment('出发时间');
            $table->string('day')->default('')->comment('出行天数');
            $table->string('partner')->default('')->comment('伙伴');
            $table->text('introduce')->nullable()->comment('介绍');
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
        Schema::dropIfExists('travels');
    }
}
