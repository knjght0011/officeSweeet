<?php
$router->group(['middleware' => 'CheckPermission:multi_assets_permission,1'], function ($router) {
    Route::get('AssetLiability', array('uses' => 'OS\Financial\AssetController@showOverview'));

    Route::group(['prefix' => 'Products'], function () {
        Route::post('IncrementStock', array('uses' => 'ProductsController@IncrementStock'));
        Route::post('DecrementStock', array('uses' => 'ProductsController@DecrementStock'));
    });
});

$router->group(['middleware' => 'CheckPermission:multi_assets_permission,2'], function ($router) {
    Route::group(['prefix' => 'Products'], function () {
        Route::post('Save', array('uses' => 'ProductsController@SaveProduct'));
        Route::post('SaveTax', array('uses' => 'ProductsController@SaveProductTax'));
        Route::post('SaveCityTax', array('uses' => 'ProductsController@SaveProductCityTax'));
        Route::post('SaveInventoryManager', array('uses' => 'ProductsController@SaveInventoryManager'));
    });

    Route::group(['prefix' => 'Services'], function () {
        Route::post('Save', array('uses' => 'OS\Inventory\ServiceLibraryController@SaveProduct'));
    });
});

$router->group(['middleware' => 'CheckPermission:multi_assets_permission,3'], function ($router) {
    Route::group(['prefix' => 'AssetLiability'], function () {
        Route::post('/Save', array('uses' => 'OS\Financial\AssetController@saveAsset'));
        Route::post('/Delete', array('uses' => 'OS\Financial\AssetController@deleteAsset'));
        Route::post('/SaveDepreciation', array('uses' => 'OS\Financial\AssetController@saveDepreciation'));
        Route::post('/DeleteDepreciation', array('uses' => 'OS\Financial\AssetController@DeleteDepreciation'));
        Route::post('/Journal', array('uses' => 'OS\Financial\AssetController@ToggleJournal'));
    });
});