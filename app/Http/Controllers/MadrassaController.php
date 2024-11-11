<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Madrassa;
use Illuminate\Http\Request;

class MadrassaController extends Controller
{
    public function index(){
        // $madrassa = Madrassa::all();
        $madrassa = Madrassa::join('locations', 'madrassas.location_id', '=', 'locations.id')
        ->select('madrassas.*', 'locations.name as location_name')
        ->get();
        return response()->json($madrassa);
    }

    public function createMadrassa(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'location_id'=>'required|integer',
        ]);

        $madrassa = new Madrassa;
        $madrassa->name = $request->name;
        $madrassa->location_id = $request->location_id;
        $madrassa->save();

        $madrassaItem = Madrassa::findOrFail($madrassa->id);
        if($madrassaItem){
            return response()->json([
                "Created:"=>$madrassaItem
            ]);
        }
        else{
            return response("Not Created");
        }

    }

    public function getMadrassa($id){
        $madrassa = Madrassa::findOrFail($id);
        return response()->json($madrassa);
    }

    public function updateMadrassa(Request $request, $id){
        $request->validate([
            'name'=>'required|string|max:255',
            'location_id'=>'required|integer|exists:locations,id'
        ]);
        try{
            $madrassa = Madrassa::findOrFail($id);
            if($madrassa){
                $madrassa->name = $request->name;
                $madrassa->location_id = $request->location_id;
                $madrassa->save();
                return response()->json([
                    "Updated"=>$madrassa
                ]);
            }
            else{
                return response("Could Not Update Madrassa");
            }
        }
        catch(\Exception $e){
            return response()->json([
                "Error"=>"Could not create Madrassa",$e
            ], 404);
        }
    }

    public function deleteMadrassa($id){
        try{
            $madrassa = Madrassa::findOrFail($id);
            if($madrassa){
                $madrassa->delete();
                return response()->json([
                    "Deleted"=>$madrassa
                ]);
            }
        }
        catch(\Exception $e){

        }
    }
}
