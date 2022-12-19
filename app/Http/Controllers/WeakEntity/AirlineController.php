<?php

namespace App\Http\Controllers\WeakEntity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\WeakEntities\Airline;

class AirlineController extends Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->middleware('permission:airline.index')->only('index');
        $this->middleware('permission:airline.create')->only('store');
        $this->middleware('permission:airline.show')->only('show');
        $this->middleware('permission:airline.update')->only('update');
        $this->middleware('permission:airline.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airlines = Airline::All();
        return response()->json($airlines, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:airlines',
            'name' => 'required|string|max:180|unique:airlines',
            'origin_code' => 'required|string|max:10',
            'observation' => 'nullable|string|max:150',
        ]);

        if($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 422);

        $id = Airline::orderBy('id', 'desc')->first()->id + 1;

        $airline = Airline::create([
            "id" => $id,
            "code" => $request->code,
            "name" => $request->name,
            "origin_code" => $request->origin_code,
            "observation" => $request->has('observation') ? $request->observation : null,
        ]);

        $airline['id'] = $id;

        return response()->json($airline, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $airline = Airline::findOrFail($id);
        return response()->json($airline, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['nullable', 'string', 'max:10', Rule::unique('airlines')->where(function ($query) use ($id) {
                $query->where('id', '!=', $id);
            })],
            'name' => ['nullable', 'string', 'max:180', Rule::unique('airlines')->where(function ($query) use ($id) {
               $query->where('id', '!=', $id);
            })],
            'origin_code' => 'nullable|string|max:10',
            'observation' => 'nullable|string|max:150',
            'enabled' => 'nullable|boolean',
        ]);

        if($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 422);

        $airline = Airline::findOrFail($id);

        $airline->update([
            "code" => $request->filled('code') ? $request->code : $airline->code,
            "name" => $request->filled('name') ? $request->name : $airline->name,
            "origin_code" => $request->filled('origin_code') ? $request->origin_code : $airline->origin_code,
            "observation" => $request->has('observation') ? $request->observation : $airline->observation,
            "enabled" => $request->filled('enabled') ? $request->enabled : $airline->enabled,
        ]);

        return response()->json($airline, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $airline = Airline::findOrFail($id);
        $airline->delete();

        return response()->json('Airline deleted successfully.', 200);
    }
}
