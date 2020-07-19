<?php
//use Illuminate\Routing\Route;


Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => ['checklogin:admin.login','web']], function (){
    Route::get('login','LoginController@index')->name('admin.login');
    Route::post('login','LoginController@login')->name('admin.login');
    Route::get('logout','IndexController@logout')->name('admin.logout');

    Route::resource('index','IndexController',['as'=>'admin']);
    Route::get('welcome','IndexController@welcome')->name('admin.index.welcome');
    Route::get('/getParent','IndexController@getParent')->name('admin.index.getParent');

    Route::resource('admin','AdminController',['as'=>'admin']);
    Route::post('admin/status','AdminController@status')->name('admin.admin.status');
    Route::post('admin/restore','AdminController@restore')->name('admin.admin.restore');
    Route::post('admin/search','AdminController@search')->name('admin.admin.search');
    Route::DELETE('admin/delAll/{id}','AdminController@delAll')->name('admin.admin.delAll');

    Route::resource('menu','Admin_menuController',['as' => 'admin']);
    Route::post('sort','Admin_menuController@sort')->name('admin.menu.sort');
    Route::post('menu/status','Admin_menuController@status')->name('admin.menu.status');
    Route::DELETE('menu/delAll/{id}','Admin_menuController@delAll')->name('admin.menu.delAll');

    Route::resource('group','Admin_groupController',['as' => 'admin']);
    Route::post('group/status','Admin_groupController@status')->name('admin.group.status');
    Route::post('group/search','Admin_groupController@search')->name('admin.group.search');
    Route::DELETE('group/delAll/{id}','Admin_groupController@delAll')->name('admin.group.delAll');
});