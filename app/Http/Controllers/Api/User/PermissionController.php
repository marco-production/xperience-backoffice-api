<?php

namespace App\Http\Controllers\Api\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class PermissionController extends Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->middleware('permission:roles.index')->only('index');
        $this->middleware('permission:roles.create')->only('store');
        $this->middleware('permission:roles.show')->only('show');
        $this->middleware('permission:roles.update')->only('update');
        $this->middleware('permission:roles.delete')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::withCasts([
            'created_at' => 'datetime:d-m-Y H:00',
            'updated_at' => 'datetime:d-m-Y H:00'
        ])->get();

        return response()->json($roles, 200);
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
            'name' => 'required|string|max:50|unique:roles'
        ]);

        if($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 422);

        $role = Role::create(['name' => $request->input('name')]);
        return response()->json($role, 200);
    }

    /**
     * Show role for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permissions = [];
        $role = Role::find($id);

        if($role->exists()) {
            //Obtener los nombre de los grupos de permisos
            $permissionGroup = Permission::select('group')->groupBy('group')->get();
            $countPermissionGroup = count($permissionGroup);

            for ($i = 0; $i < $countPermissionGroup; $i++) {

                //Obtener los permisos de cada grupo por separado
                $permissionsOfGroup = Permission::where('group', $permissionGroup[$i]->group)->get();
                $countPermissions = count($permissionsOfGroup);

                for ($x = 0; $x < $countPermissions; $x++) { 

                    $active = false;
                    if( $role->hasPermissionTo($permissionsOfGroup[$x]['name']) ) { $active = true; }
                    $permissionsOfGroup[$x]["active"] = $active;
                }
                array_push($permissions, ['group' => $permissionGroup[$i]->group, 'permission' => $permissionsOfGroup]);
            }
            return response()->json(['role' => $role, 'permissions' => $permissions], 200);
        } 
        return response()->json(['errors' => 'This role does not exist.'], 400);
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
            'name' => ['required', 'string', 'max:50', Rule::unique('roles')->where(function ($query) use($id) {
                return $query->where('id', '!=', $id);
            })],
            'permissions' => 'nullable|array'
        ]);

        if($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 422);

        $role = Role::findOrFail($id);

        if($role){
            $role->update(['name' => $request->name]);
            
            // Add permission
            if($request->filled('permissions')) $role->syncPermissions($request->permissions);

            // Cast date
            $role->withCasts([
                'created_at' => 'datetime:d-m-Y H:00',
                'updated_at' => 'datetime:d-m-Y H:00'
            ]);
            
            return response($role, 200);
        }

        return response()->json(['errors' => 'This role does not exist.'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if($role){
            if($role->name == 'Super Admin') return response()->json(['errors' => "You can't delete Super Admin Role"], 400); 

            $role->delete();
            return response(['message' => 'Role deleted successfully!'], 204);
        }
        return response()->json(['errors' => 'This role does not exist.'], 400);
    }
}
