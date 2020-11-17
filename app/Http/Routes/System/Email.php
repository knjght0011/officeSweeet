<?php
#Email
Route::group(['prefix' => 'Email'], function () {
    Route::get('Overview', array('uses' => 'OS\Email\EmailController@Overview'));
    Route::post('Send', array('uses' => 'OS\Email\EmailController@SendEmail'));
    Route::post('SendPO', array('uses' => 'OS\Email\EmailController@SendPOEmail'));
    Route::post('SendNotification', array('uses' => 'OS\Email\EmailController@SendNotification'));

    Route::post('Resend', array('uses' => 'OS\Email\EmailController@ResendEmail'));
    Route::get('Preview/{id}', array('uses' => 'OS\Email\EmailController@PreviewEmail'));
    Route::get('Attachment/{id}', array('uses' => 'OS\Email\EmailController@ViewAttachment'));

    Route::get('SuperSecretHiddenBulkEmailEnabler', array('uses' => 'OS\Email\EmailTemplateController@SuperSecretHiddenBulkEmailEnabler'));

    Route::group(['middleware' => 'CheckPermission:bulk_email_permission'], function ($router) {

        //Route::get('Group', array('uses' => 'OS\Email\EmailController@GroupSend'));
        Route::post('BulkSend', array('uses' => 'OS\Email\EmailController@BulkSend'));

        Route::get('TemplateTest\{templateid}\{emailaddress}', array('uses' => 'OS\Email\EmailController@TemplateTest'));

        Route::group(['prefix' => 'Template'], function () {

            //Route::get('List', array('uses' => 'OS\Email\EmailTemplateController@List'));
            Route::get('Preview/{id}', array('uses' => 'OS\Email\EmailTemplateController@Preview'));
            Route::post('Upload', array('uses' => 'OS\Email\EmailTemplateController@UploadTemplate'));
            Route::post('Save', array('uses' => 'OS\Email\EmailTemplateController@SaveTemplate'));
            Route::post('Delete', array('uses' => 'OS\Email\EmailTemplateController@Delete'));


            Route::get('New', array('uses' => 'OS\Email\EmailTemplateController@New'));
            Route::post('ImageUpload', array('uses' => 'OS\Email\EmailTemplateController@ImageUpload'));

        });
    });


    //Route::post('Quote', array('uses' => 'OS\Email\EmailController@EmailQuote'));
    //Route::post('InvoiceReminders', array('uses' => 'OS\Email\EmailController@EmailInvoices'));
    //Route::post('Support', array('uses' => 'OS\Email\EmailController@EmailSupport'));
});