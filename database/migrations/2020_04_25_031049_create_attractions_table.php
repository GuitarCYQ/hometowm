<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attractions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attr')->default('')->comment('图片地址');
            $table->string('name')->default('')->comment('景点名称');
            $table->text('introduce')->nullable()->comment('介绍');
            $table->string('location')->default('')->comment('景点地址');
            $table->string('climate')->default('')->comment('气候');
            $table->string('level')->default('')->comment('级别');
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
        Schema::dropIfExists('attractions');
    }
}
