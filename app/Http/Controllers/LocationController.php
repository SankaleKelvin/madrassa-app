<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{

    // Display the locations view
    public function index()
    {
        $locations = Location::all(); // Pass locations data to the view if needed
        return view('locations/location', compact('locations'));
    }

    //Create a Location
    public function createLocation(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'areaCode' => 'required'
        ]);

        $location = new Location;
        $location->name = $request->name;
        $location->areaCode = $request->areaCode;
        // $location->user_id = auth()->user()->id;
        $location->save();

        $locationCheck = Location::find($location->id);

        return response()->json($locationCheck);
    }

    //Get all locations 
    public function getLocations()
    {
        $location = Location::all();
        if ($location) {
            return response()->json($location);
        } else {
            return response("No Location was found.");
        }
    }

    //Get a location
    public function getLocation($id)
    {
        try {
            $location = Location::findOrFail($id);
            return response()->json($location);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Location Not found with id: ",
                $id
            ], 404);
        }
    }

    //Update a Location
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'areaCode' => 'required'
        ]);
        try {
            $existingLocation = Location::findOrFail($id);
            if ($existingLocation) {
                $existingLocation->name = $request->name;
                $existingLocation->areaCode = $request->areaCode;
                $existingLocation->save();

                return response()->json($existingLocation);
            } else {
                response()->json("No Record Found!");
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Location could not be updated!"
            ], 404);
        }
    }

    //delete a Location
    public function deleteLocation($id)
    {
        try {
            $existingLocation = Location::findOrFail($id);
            if ($existingLocation) {
                // Location::destroy($id);
                $existingLocation->delete();
                return response()->json([
                    "deleted" => $existingLocation
                ]);
            } else {
                return response("Location does not exist");
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Location could not be deleted!"
            ], 403);
        }
    }
}
