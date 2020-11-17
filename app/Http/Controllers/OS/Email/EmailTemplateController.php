<?php

namespace App\Http\Controllers\OS\Email;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

use App\Helpers\OS\FileStoreHelper;

use App\Models\OS\Email\EmailTemplate;

class EmailTemplateController extends Controller {

    public function SuperSecretHiddenBulkEmailEnabler(){

        $permissions  = Auth::user()->permissions;
        $permissions['bulk_email_permission'] = 1;
        Auth::user()->permissions = $permissions;
        Auth::user()->save();
        return "Done";

    }

    /*
    public function List(){

        $templates = EmailTemplate::all();
        return View::make('OS.EmailTemplate.list')
                        ->with('templates', $templates);
    }
    */

    public function Preview($subdomain, $id){

        $template = EmailTemplate::where('id', $id)->first();

        if(count($template) === 1){
            return View::make('Emails.Customer.emailtemplate')
                ->with('content', $template->content);
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function UploadTemplate()
    {
        if (Input::hasFile('fileToUpload'))
        {
            $file = Input::file('fileToUpload');

            $mimesplit = explode("/" , $file->getMimeType());

            switch ($mimesplit[0])
            {
                case 'image':
                    $content = $this->ProcessImageupload($file);
                    break;
                case 'text':
                    switch ($file->getMimeType())
                    {
                        case 'text/html':
                            $content = $this->ProcessHTMLupload($file);
                            break;
                        default:
                            return Redirect::to('/Email/Template/List')
                                ->withErrors("This filetype is not supported by Office Sweeet at this current time");
                            break;
                    }
                    break;
                case 'application':
                    switch ($file->getMimeType())
                    {
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                            $content = $this->ProcessMSWORDupload($file);
                            break;
                        case 'application/msword':
                            return Redirect::to('/Email/Template/List')
                                ->withErrors("This filetype a old doc file and not a DocX file, please use word to save as a DocX before uploading");
                        default:
                            return Redirect::to('/Email/Template/List')
                                ->withErrors("This filetype is not supported by Office Sweeet at this current time");
                            break;
                    }
                    break;
                default:
                    return Redirect::to('/Email/Template/List')
                        ->withErrors("This filetype is not supported by Office Sweeet at this current time");
                    break;
            }

            $template = new EmailTemplate;
            $template->name = $file->getClientOriginalName();
            $template->content = $content;
            $template->save();

            return Redirect::to('/Email/Template/List')
                        ->with("newid", $template->id);

        }else{
            #error do something
            return Redirect::to('/Email/Template/List')
                ->withErrors("No File Given");
            #return $this->Error('No File Given', 'none');

        }
    }

    #called from ProcessUpload
    public function ProcessMSWORDupload($file)
    {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file, 'Word2007');
        $html = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        //return strstr ( strip_tags($html->getContent() , '<p>'),  '<p>');
        return $html->getContent();

    }

    public function ProcessImageupload($file){

        $base64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file));

        return "<img style='width: 100%;' src='". $base64 ."'/>";
    }

    public function ProcessHTMLupload($file){
        return file_get_contents($file);
    }

    public function Delete(){

        $template = EmailTemplate::where('id', Input::get('id'))->first();

        if(count($template) === 1){

            $template->delete();

            return ['status' => 'OK'];

        }else{
            return ['status' => 'notfound'];
        }

    }

    //WIP below
    public function New(){

        return View::make('OS.EmailTemplate.editor');

    }

    public function SaveTemplate(){

        $data = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'content' => Input::get('content')
        );

        $template = EmailTemplate::where('id', $data['id'])->first();

        if(count($template) != 1){
            $template = new EmailTemplate;
        }


        $template->name =  $data['name'];
        $template->content =  $data['content'];
        $template->save();

        return ['status' => 'OK', 'id' => $template->id];
    }


    public function ImageUpload(){

        //$data = Input::all();
        $file_array = Input::file();

        $resultArray = array();

        foreach ($file_array as $key => $value) {

            $imagedetails = getimagesize($value);

            $data = array(
                'file' => 'data:' . $value->getMimeType() . ';base64,' . base64_encode(file_get_contents($value)),
                'description' => "Email Template Editor Upload.",
                'upload_user' => Auth::user()->id,
            );


            $file = FileStoreHelper::StoreFileAndReturn($data);

            $result = array(
                'name' => $value->getClientOriginalName(),
                'src' => $file->file,
                'type' => 'image',
                'height' => $imagedetails[1],
                'width' => $imagedetails[0],
            );

            array_push($resultArray,$result);
        }

        $response = array( 'data' => $resultArray );
        return json_encode($response);

    }

    /*{
      data: [
        'https://.../image.png',
        // ...
        {
          src: 'https://.../image2.png',
          type: 'image',
          height: 100,
          width: 200,
        },
        // ...
      ]
    }*/
}

