<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    

    public function index(Request $request)
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Role::with(['permissions'])->select(sprintf('%s.*', (new Role())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'role_show';
                $editGate = 'role_edit';
                $deleteGate = 'role_delete';
                $crudRoutePart = 'roles';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('default', function ($row) {
                return $row->default ? ($row->default==1) ?? 'yes': 'no';
            });
            $table->editColumn('permissions', function ($row) {
                $labels = [];
                foreach ($row->permissions as $permission) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $permission->title);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'permissions']);

            return $table->make(true);
        }

        return view('admin.roles.index');
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::pluck('title','id' );
        $permi = Permission::all();
        $grpTitle = trans('cruds');

        return view('admin.roles.create', compact('permissions' , 'permi', 'grpTitle'));
    }

    public function store(StoreRoleRequest $request)
    {
        $default =($request->default == 'on') ? 1 : 0;
        if ($default == 1) {
            Role::where('default', 1)->update(['default' => 0]);
        }
        
        $request->merge([
            'default' => $default,
        ]);
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        session()->flash('message', trans('global.create_success'));
        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::pluck('title', 'id');
        $permi = Permission::all();
        $role->load('permissions');
        $grpTitle = trans('cruds');
        $mnfound = false;
        return view('admin.roles.edit', compact('permissions', 'role', 'grpTitle', 'permi', 'mnfound'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $def =($request->default == 'on') ? 1 : 0;
        if ($def == 1) {
            Role::where('default', 1)->update(['default' => 0]);
        }
        $request->merge([
            'default' => $def,
        ]);
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        session()->flash('message', trans('global.update_success'));
        
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return back();
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
