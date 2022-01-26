<?php

namespace App\Http\Controllers;

use App\Models\ObjectModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;

class ObjectController extends BaseController
{
    public function index()
    {
        $allObjects = ObjectModel::all();
        $remainingUserObjects = [];
        $remainingComputerObjects = [];

        if (Session::get("remaining-person-objects")) {
            $remainingUserObjects = Arr::pluck(Session::get("remaining-person-objects"), 'name');
        }

        if (Session::get("remaining-computer-objects")) {
            $remainingComputerObjects = Arr::pluck(Session::get("remaining-computer-objects"), 'name');
        }
        
        $objects = [];

        $i = 0;
        foreach($allObjects as $object) {
            $objects[$i] = $object;
            $objects[$i]['active'] = true;

            if(!empty($remainingUserObjects)) {
                $isActive = in_array($object['name'], $remainingUserObjects);
                $objects[$i]['active'] = $isActive;
            }
            $i++;
        }

        return [
            'objects' => $objects,
            'remaining_count_user' => count($remainingUserObjects),
            'remaining_count_computer' => count($remainingComputerObjects)
        ];
    }
}
