<?php
namespace App\Http\Controllers\OS\Templates;

use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;


#models

use App\Models\Client;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Template;
use App\Models\OS\Report;



use App\Helpers\OS\TemplateHelper;

class DocXController extends Controller {
    #return $this->Error('', '');
    public function Error($error, $debug)
    {
        if (is_array($error)){
            return View::make('error')
                ->with('errors', $error)
                ->with('debug', $debug);
        }else{
            return View::make('error')
                ->with('error', $error)
                ->with('debug', $debug);
        }
    }

    #show list of templates of a certain type and link to generation of docuemnts for passed dataID
    #get /Templates/List/{type}/{dataID}
    public function showTemplateList($subdomain, $type, $dataID)
    {
        $templates = Template::where('type', '=', $type)->get();
        
        foreach($templates as $template){
            $template->load('user');
        }
        
        return View::make('OS.Documents.list')
            ->with('type', $type)
            ->with('templates', $templates)
            ->with('dataID', $dataID);

    }
    
    #Generate a document from a template stored in DB
    #get /Templates/Generate/{tempID}/{dataID}
    public function generateDoc($subdomain, $tempID, $dataID)
    {
        $selected_template = intval($tempID);
        if ($selected_template !== 0) {
            
            $template = Template::find($selected_template);
            
            switch ($template->type) 
            {
                case 'client':
                    #return $this->generateClientDoc($template, $dataID);
                    $client = Client::where('id', $dataID)
                        ->with('address')
                        ->with('contacts')
                        ->first();
                    app()->instance('Template-Client', $client);

                    $dataid = $client->id;

                    $backtext = "Back to Client";
                    $backlink = "/Clients/View/" . $client->id;
                    $savetext = "Save to Client's File";
                    break;
                case 'vendor':
                    $vendor = Vendor::where('id', $dataID)
                        ->with('address')
                        ->with('contacts')
                        ->first();
                    app()->instance('Template-Vendor', $vendor);

                    $dataid = $vendor->id;

                    $backtext = "Back to Vendor";
                    $backlink = "/Vendors/View/" . $vendor->id;
                    $savetext = "Save to Vendor's File";
                    break;
                case 'employee':
                    $employee = UserHelper::GetOneUserByID($dataID);

                    app()->instance('Template-Employee', $employee);

                    $dataid = $employee->id;

                    $backtext = "Back to Employee";
                    $backlink = "/Employees/View/" . $employee->id;
                    $savetext = "Save to Employee's File";
                    break;
                case 'general':
                    
                    break;
                default:
                    return $this->Error('Invalid Type Given', $template->type);
                    #break;
            }

            //$template->content = str_replace( "figure", "div", $template->content );
            //$template->content = preg_replace("/<figure\s(.+?)>(.+?)<\/figure>/is", "<div>$2</div>", $template->content);

            $view = \DbView::make($template)
                ->with('backtext', $backtext)
                ->with('backlink', $backlink)
                ->with('savetext', $savetext)
                ->with('template', $template)
                ->with('dataID', $dataid);
            return $view;


        } else {
            return $this->Error('Invalid $tempID Given', $tempID);
        }
    }


    #save generated document
    #post /Templates/Save
    public function SaveDoc()
    {
        $data = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'data' => Input::get('ReportData'),
            'type' => Input::get('Type'),
            'DataID' => Input::get('dataID'),
            );

        $doc = new Report;

        switch ($data['type'])
        {
            case 'client':
                $doc->client_id = $data['DataID'];
                $doc->vendor_id = null;
                $doc->user_id = null;
                break;
            case 'vendor':
                $doc->client_id = null;
                $doc->vendor_id = $data['DataID'];
                $doc->user_id = null;
                break;
            case 'employee':
                $doc->client_id = null;
                $doc->vendor_id = null;
                $doc->user_id = $data['DataID'];
                break;
        }

        $doc->name = $data['name'];
        $doc->created_by = Auth::user()->id;
        $doc->reportdata = $data['data'];


        if($data['id'] == 0){

            $doc->originalreport_id = null;
            $doc->save();

            return $doc->id;

        }else{

            $doc->originalreport_id = $data['id'];
            $doc->save();

            $doc2 = Report::find($doc->originalreport_id);
            $doc2->touch();

            return $doc2->id;

        }
    } 

    #view/edit a saved document
    #get /Templates/Edit/{$type}/{$docID}
    public function EditDocument($subdomain, $docID)
    {
        $doc = Report::where('id', $docID)->first();

        if(count($doc) > 0){

            return View::make('OS.Documents.docedit')
                ->with('OriginalID', $doc->id)
                ->with('doc', $doc->GetLast())
                ->with('dataID', $doc->dataID());

        }else{

            return $this->Error('Invalid ID Given', $docID);
        }
    }

    #/Templates/PDF/{docID}
    public function DownloadPDF($subdomain, $docID)
    {
        $docprime = Report::where('id', $docID)->first();

        if(count($docprime) > 0){

            $doc = $docprime->GetLast();

            return $doc->PDFObject()->stream();

        }else{
            return $this->Error('Invalid ID Given', $docID);
        }


    }
}
