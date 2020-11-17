<?php

namespace App\Http\Controllers\OS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

#use \App\Providers\EventLog;

use App\Helpers\OS\FileStoreHelper;

use App\Models\OS\FileStore;

class FileStoreController extends Controller {

    public function View(){

        $files = FileStore::all();

        return View::make('OS.FileStore.view')
            ->with('files', $files);

    }

    public function UploadCKEditor(){

        $upload = Input::file('upload');

        $data = array(
            'file' => 'data:' . $upload->getMimeType() . ';base64,' . base64_encode(file_get_contents($upload)),
            'client_id' => null,
            'vendor_id' => null,
            'description' => "CK Editor Upload",
            'upload_user' => Auth::user()->id,
            'updatetype' => "",
        );

        //$id =  FileStoreHelper::StoreFile($data);

        //return ['uploaded' => "1", 'fileName' => $upload->getClientOriginalName(), 'url' => url("/FileStore/ShowFile/" . $id)];
        return ['uploaded' => "1", 'fileName' => $upload->getClientOriginalName(), 'url' => $data['file']];
    }

    public function Upload(){

        $data = array(
            'file' => Input::get('file'),
            'client_id' => Input::get('client_id'),
            'vendor_id' => Input::get('vendor_id'),
            'user_id' => Input::get('user_id'),
            'description' => Input::get('description'),
            'upload_user' => Auth::user()->id,
            'updatetype' => Input::get('updatetype'),
            'updateid' => Input::get('updateid'),
        );

        switch (FileStoreHelper::getMimeType($data['file']))
        {
            case 'application':
                switch (FileStoreHelper::getMimeSubType($data['file'])) {
                    case 'pdf':
                        $valid = FileStoreHelper::ValidatefileInput($data);
                        break;
                    default:
                        return ['status' => 'unsuportedfiletype'];
                }
                break;
            case 'image':
                $valid = FileStoreHelper::ValidatefileInput($data);
                break;
            default:
                return ['status' => 'unsuportedfiletype'];
        }

        if ($valid->fails()){
            return ['status' => 'validation', 'errors' => $valid->errors()->toArray()];
        }else{
            return ['status' => 'OK', 'data' => FileStoreHelper::StoreFile($data)];
        }

    }

    public function Description(){
        $data = array(
            'id' => Input::get('id'),
            'description' => Input::get('description'),
        );

        $valid = FileStoreHelper::ValidatefileInput($data);

        if ($valid->fails()){
            return $valid->errors()->toArray();
        }else{
            return FileStoreHelper::FileDescription($data['id'], $data['description']);
        }

    }

    public function Delete(){
        $data = array(
            'id' => Input::get('id'),
        );

        if(FileStoreHelper::FileDelete($data['id'])){
            return "done";
        }else{
            return "failtofind";
        }

    }

    public function ShowFile($subdomain, $id)
    {

        $file = FileStore::find($id);

        if(count($file) === 1 ){
            switch ($file->getMimeType())
            {
                case 'application':
                    switch ($file->getMimeSubType())
                    {
                        case 'pdf':
                            return response($file->Base64Decode()) ->header('content-type','application/pdf') ->header('Content-Disposition', 'inline; filename="file.pdf"');
                        case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                            return response($file->Base64Decode()) ->header('content-type','application/‌vnd.openxmlformats-officedocument.wordprocessingml.document') ->header('Content-Disposition', 'inline; filename="file.odp"');
                        default:
                            $error["error"] = "Unknown File Type" . $file->getMimeType() . " " . $file->getMimeSubType();
                            return $error;
                    }
                    break;
                case 'image':
                    return View::make('OS.FileStore.image')
                        ->with('image', $file->file);
                default:
                    $error["error"] = "Unknown File Type" . $file->getMimeType() . " " . $file->getMimeSubType();
                    return $error;
            }
        }else{
            return "error";
        }
    }


    public function ViewerTest(){

        return View::make('OS.FileStore.viewertest');

    }
}
