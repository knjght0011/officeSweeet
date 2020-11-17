<?php
#ACP
$router->group(['middleware' => 'CheckPermission:acp_permission'], function ($router) {
    Route::group(['prefix' => 'ACP'], function ($router) {
        Route::get('/', array('uses' => 'ACPController@showAdmin'));

        Route::group(['prefix' => 'General'], function () {
            Route::get('/{subtab?}', array('uses' => 'ACPController@showAdminGeneral'));
        });

        $router->group(['middleware' => 'CheckPermission:acp_import_export_permission'], function ($router) {
            Route::group(['prefix' => 'ImportExport'], function () {
                Route::get('/{subtab?}', array('uses' => 'ACPController@showAdminImportExport'));
            });
        });

        $router->group(['middleware' => 'CheckPermission:acp_company_info_permission'], function ($router) {
            Route::group(['prefix' => 'CompanyInfo'], function () {
                Route::get('/{subtab?}', array('uses' => 'ACPController@showAdminCompanyInfo'));
            });
        });

        $router->group(['middleware' => 'CheckPermission:acp_manage_custom_tables_permission'], function ($router) {
            Route::group(['prefix' => 'CustomTabs'], function () {
                Route::get('/{subtab?}', array('uses' => 'ACPController@showAdminCustomTabs'));
            });
        });

        Route::group(['prefix' => 'Messages'], function () {
            Route::get('/{subtab?}', array('uses' => 'ACPController@showMessages'));
        });

        Route::group(['prefix' => 'Integration'], function () {
            Route::get('/{subtab?}', array('uses' => 'ACPController@showIntegration'));
            Route::post('/TNApplication', array('uses' => 'ACPController@TransnationalApplication'));
        });

        $router->group(['middleware' => 'CheckPermission:acp_subscription_permission'], function ($router) {
            Route::group(['prefix' => 'Subscription'], function () {
                Route::get('/{subtab?}', array('uses' => 'ACPController@showAdminSubscription'));
            });
        });

        Route::post('Branches/Save', array('uses' => 'ACPController@saveBranch'));
        Route::post('Branches/Disable', array('uses' => 'ACPController@disableBranch'));
        Route::post('General/Save', array('uses' => 'ACPController@saveGeneralSettings'));
        Route::post('Templates/Save', array('uses' => 'ACPController@saveTemplateSubGroup'));
        Route::post('Templates/Delete', array('uses' => 'ACPController@deleteTemplateSubGroup'));
        Route::post('Envelope/Save', array('uses' => 'ACPController@saveEnvelope'));
        Route::post('CheckSettings/Save', array('uses' => 'ACPController@saveCheckSettings'));
        Route::post('Scheduler/Save', array('uses' => 'ACPController@saveSchedulerEvent'));
        Route::post('Scheduler/Delete', array('uses' => 'ACPController@deleteSchedulerEvent'));

        Route::post('Training/Save', array('uses' => 'ACPController@saveTraining'));
        Route::post('Training/Delete', array('uses' => 'ACPController@deleteTraining'));

        Route::post('ImportExport/Import', array('uses' => 'ACPController@ImportExcelClients'));


        Route::group(['prefix' => 'Expense'], function () {
            Route::post('Categories/Delete', array('uses' => 'ACPController@DeleteCatagory'));
            Route::post('Categories/Type', array('uses' => 'ACPController@ChangeCatagoryType'));
            Route::post('Categories/Save', array('uses' => 'ACPController@SaveCatagory'));
            Route::post('Subcategories/Delete', array('uses' => 'ACPController@DeleteSubCatagory'));
            Route::post('Subcategories/Save', array('uses' => 'ACPController@SaveSubCatagorys'));
            Route::post('Budget/Update', array('uses' => 'ACPController@UpdateMonthlyBudget'));

        });

        #Route::get('Tab/{tab1}/{tab2}', array('uses' => 'ACPController@showAdminTab'));

    });
    Route::get('Branches/All', array('uses' => 'ACPController@BranchData'));
});