<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('parent_id')->default('')->comment('上级id');
            $table->enum('action',['0','1'])->default('0')->comment('主方法');
            $table->string('models')->default('')->comment('模块');
            $table->string('controller')->default('')->comment('控制器');
            $table->string('methods')->default('')->comment('方法');
            $table->enum('type',['0','1'])->default('0')->comment('是否要权限');
            $table->enum('status',['0','1'])->default('1')->comment('是否启用');
            $table->enum('accord',['0','1'])->default('1')->comment('是否显示，默认显示');
            $table->string('name')->default('')->comment('菜单名');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序');

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
        Schema::dropIfExists('admin_menus');
    }
}
