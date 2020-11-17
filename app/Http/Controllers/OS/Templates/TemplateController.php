<?php
namespace App\Http\Controllers\OS\Templates;

use App\Http\Controllers\Controller;
use App\Models\OS\Templates\TemplateContent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateGroup;

class TemplateController extends Controller
{
    #get /Templates/List
    public function showTemplateList()
    {
        $templates = Template::all();

        foreach($templates as $template){
            $template->load('user');
        }

        return View::make('OS.Templates.list')
            ->with('templates', $templates);

    }

    public function EditTemplate($subdomain, $id)
    {
        $template = Template::find($id);
        $DocData = str_replace("@extends('OS.Templates.templatemaster') @section('template') " , "", $template->content);
        $DocData = str_replace(" @stop" , "", $DocData);
        $templategroup = TemplateGroup::all();
        return View::make('OS.Templates.new')
            ->with('DocData',$DocData)
            ->with('template',$template)
            ->with('templategroup',$templategroup);
    }

    public function UploadTemplate()
    {
        if (Input::hasFile('fileToUpload'))
        {

            $file = Input::file('fileToUpload');

            switch ($file->getMimeType())
            {

                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    return $this->ProcessMSWORDupload($file);
                    break;
                case '':

                    break;
                case 'application/msword':
                    return $this->WordDocNotDocx($file->getClientOriginalName(), $file->getMimeType());
                default:
                    return $this->InvalidDocType($file->getClientOriginalName(), $file->getMimeType());
                    break;
            }
        }else{
            #error do something
            return Redirect::to('/Templates/List')
                ->withErrors("No File Given");
            #return $this->Error('No File Given', 'none');

        }
    }

    #called from ProcessUpload
    public function WordDocNotDocx($orgfilename, $filemime)
    {
        #Generate an error message or view to state that the doc type is not supported for upload.
        $error = 'This filetype a old doc file and not a DocX file, please use word to save as a DocX before uploading';
        $debug = 'Mime Type: '.$filemime;
        return View::make('error')
            ->with('error', $error)
            ->with('debug', $debug);
    }

    #called from ProcessUpload
    public function InvalidDocType($orgfilename, $filemime)
    {
        #Generate an error message or view to state that the doc type is not supported for upload.
        $error = 'This filetype is not supported by Office Sweeet at this current time';
        $debug = 'Mime Type: '.$filemime;

        return Redirect::to('/Templates/List')
            ->withErrors($error);

    }

    #called from ProcessUpload
    public function ProcessMSWORDupload($file)
    {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file, 'Word2007');

        $html = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');


        $templategroup = TemplateGroup::all();
        $DocData = strstr ( strip_tags($html->getContent() , '<p>'),  '<p>');

        return View::make('OS.Templates.new')
            ->with('templategroup',$templategroup)
            ->with('DocData', $DocData)
            ->with('filename', $file->getClientOriginalName());
    }


    public function showNewTemplate()
    {

        $templategroup = TemplateGroup::all();
        return View::make('OS.Templates.new')
            ->with('templategroup',$templategroup);

    }

    #Save new template to DB
    #post /Templates/New
    public function SaveTemplate()
    {
        $template = Template::where('id', Input::get('id'))->first();
        if(count($template) != 1) {
            $template = new Template;
        }

        $templateheader = "@extends('OS.Templates.templatemaster') @section('template') ";
        $templatedata = str_replace("&gt;" , ">" , Input::get('content'));
        $templatedata = str_replace("&#39;" , "'" , $templatedata);
        $templatefooter = " @stop";

        $template->name = Input::get('name');
        $template->content = "";
        $template->type = Input::get('type');
        $template->subgroup = Input::get('subgroup');
        $template->user_id = Auth::user()->id;
        $template->save();

        $backup = new TemplateContent;
        $backup->content = $templateheader.$templatedata.$templatefooter;
        $backup->template_id = $template->id;
        $backup->save();

        $this->CheckSubgroup($template->type, $template->subgroup);

        return ['status' => 'OK', 'id' => $template->id];

    }

    private function CheckSubgroup($type, $subgroup){

        $group = TemplateGroup::where("group", $type)->where("subgroup",  $subgroup)->get();

        if(count($group) === 0){
            $data = new TemplateGroup;
            $data->group = $type;
            $data->subgroup = $subgroup;
            $data->save();
        }

    }

    public function deleteTemplate(){

        $template = Template::where('id', Input::get('id'))->first();

        if(count($template) === 1){

            $template->delete();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'NotFound'];
        }

    }

}
