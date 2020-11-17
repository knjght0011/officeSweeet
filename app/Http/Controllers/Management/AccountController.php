<?php

namespace App\Http\Controllers\Management;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
#use Illuminate\Support\Facades\Input;
#use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\AccountHelper;
use App\Helpers\Management\TaskHelpers\MigrationHelper;

use App\Models\Management\Account;

use App\Mail\WelcomeEmail;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #AccountHelper::CreateDB("demo", "demo", "f7qj6KCkX9TP5WwP33Hautc2gE4xJTvK");
        
        $accounts = Account::all();
        return View::make('Management.Accounts.index')
            ->with('accounts', $accounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('Management.Accounts.edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($subdomain, $id)
    {
        $osaccount = Account::where('subdomain', '=', $id)->first();

        return View::make('Management.Accounts.show')
            ->with('osaccount', $osaccount);
        
    }

    public function SetActiveDate(){

        $account = Account::where('id', Input::get('accountid'))->first();
        if(count($account) === 1){

            $date = Carbon::parse(Input::get('active'))->endOfDay();
            $account->active = $date;
            $account->save();

            return ['status' => 'OK'];

        }else{
            return ['status' => 'notfound'];
        }

    }

    public function SetUsers(){

        $account = Account::where('id', Input::get('accountid'))->first();
        if(count($account) === 1){

            //$date = Carbon::parse(Input::get('active'))->endOfDay();
            $account->licensedusers = Input::get('users');
            $account->save();

            return ['status' => 'OK'];

        }else{
            return ['status' => 'notfound'];
        }

    }

    public function migrate($subdomain, $id)
    {
        $account = Account::where('subdomain', '=', $id)->first();
        
        if(count($account) === 1){
            $output = MigrationHelper::MigrateDB($account);
            
            return View::make('Management.Accounts.artisanoutput')
                ->with('output', $output);
        }else{
            return "error no account found";
        }
    }
    
    public function rollback($id)
    {
        $account = Account::where('subdomain', '=', $id)->first();
        
        if(count($account) === 1){
            $output = MigrationHelper::RollbackDB($account);
            
            return View::make('Management.Accounts.artisanoutput')
                ->with('output', $output);
        }else{
            return "error no account found";
        }
    }



}
