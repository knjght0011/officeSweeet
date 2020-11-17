<?php
namespace App\Helpers\OS;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskListHelper
{

    public static function GetAllTasks()
    {
        if(!app()->bound('tasks')){

            app()->instance('tasks', Task::all());

            return app()->make('tasks');
        }else{
            return app()->make('tasks');
        }
    }

    public static function IncompleteTasks(){

        $count = 0;

        foreach(self::GetAllTasks() as $task){
            if($task->user_id === Auth::user()->id){
                if($task->status != "Complete"){
                    $count = $count + 1;
                }
            }
        }

        return $count;

    }

    public static function GetStatusList(){

        $array = array();
        foreach(self::GetAllTasks() as $task){
            if(!in_array($task->status, $array)){
                $array[] = $task->status;
            }
        }

        if (($key = array_search("Important", $array)) !== false) {
            unset($array[$key]);
        }
        if (($key = array_search("Urgent", $array)) !== false) {
            unset($array[$key]);
        }
        if (($key = array_search("Critical", $array)) !== false) {
            unset($array[$key]);
        }
        if (($key = array_search("Complete", $array)) !== false) {
            unset($array[$key]);
        }
        if (($key = array_search("", $array)) !== false) {
            unset($array[$key]);
        }

        return $array;

    }

}