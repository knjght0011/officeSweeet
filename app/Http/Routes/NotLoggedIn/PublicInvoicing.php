<?php

Route::group(['prefix' => 'Public'], function () {

    Route::group(['prefix' => 'Email'], function () {
        Route::get('{token}', array('uses' => 'OS\Email\EmailController@viewAttachmentPublic'));
        Route::get('PDF/{token}', array('uses' => 'OS\Email\EmailController@viewAttachmentPublicPDF'));
    });


    Route::group(['prefix' => 'Document'], function () {
        Route::group(['prefix' => 'Signing'], function () {
            Route::get('{documenttoken}/{contacttoken}', array('uses' => 'OS\Templates\SigningController@viewSignDocumentPublic'));
            Route::post('SubmitSignatures', array('uses' => 'OS\Templates\SigningController@SubmitSignatures'));
        });

        Route::get('{token}', array('uses' => 'OS\Templates\SigningController@viewDocumentPublic'));
        Route::get('PDF/{token}', array('uses' => 'OS\Templates\SigningController@viewDocumentPublicPDF'));
    });
});

Route::group(['prefix' => 'Invoice'], function () {
    Route::get('{token}', array('uses' => 'InvoiceController@viewInvoicePublic'));
    Route::get('PDF/{token}', array('uses' => 'InvoiceController@viewInvoicePublicPDF'));
});

Route::post('Payment', array('uses' => 'OS\Financial\PublicPaymentsController@MakePayment'));



Route::get('CompanyLogo', array('uses' => 'EmailController@CompanyLogo'));