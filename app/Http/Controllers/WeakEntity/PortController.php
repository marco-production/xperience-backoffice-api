<?php

namespace App\Http\Controllers\WeakEntity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\WeakEntities\Port;

class PortController extends Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->middleware('permission:port.index')->only('index');
        $this->middleware('permission:port.create')->only('store');
        $this->middleware('permission:port.show')->only('show');
        $this->middleware('permission:port.update')->only('update');
        $this->middleware('permission:port.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ports = Port::All();
        return response()->json($ports, 200);
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
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:190|unique:ports',
            'transportation_id' => 'required|integer|between:1,3',
            'place' => 'required|string|max:190',
            'dominican_port' => 'required|boolean',
        ]);

        if($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 422);

        $port = Port::create([
            "code" => $request->code,
            "name" => $request->name,
            "transportation_id" => $request->transportation_id,
            "place" => $request->place,
            "dominican_port" => $request->dominican_port,
        ]);

        $port['id'] = $port->id;

        return response()->json($port, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $port = Port::findOrFail($id);
        return response()->json($port, 200);
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
            'code' => 'nullable|string|max:10',
            'name' => ['nullable', 'string', 'max:180', Rule::unique('ports')->where(function ($query) use ($id) {
                $query->where('id', '!=', $id);
             })],
            'transportation_id' => 'nullable|integer|between:1,3',
            'place' => 'nullable|string|max:190',
            'dominican_port' => 'nullable|boolean',
            'enabled' => 'nullable|boolean'
        ]);

        if($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 422);

        $port = Port::findOrFail($id);

        $port->update([
            "code" => $request->filled('code') ? $request->code : $port->code,
            "name" => $request->filled('name') ? $request->name : $port->name,
            "transportation_id" => $request->filled('transportation_id') ? $request->transportation_id : $port->transportation_id,
            "place" => $request->filled('place') ? $request->place : $port->place,
            "dominican_port" => $request->filled('dominican_port') ? $request->dominican_port : $port->dominican_port,
            "enabled" => $request->filled('enabled') ? $request->enabled : $port->enabled,
        ]);

        $port['id'] = $port->id;

        return response()->json($port, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $port = Port::findOrFail($id);
        $port->delete();

        return response()->json('Port deleted successfully.', 200);
    }
}
