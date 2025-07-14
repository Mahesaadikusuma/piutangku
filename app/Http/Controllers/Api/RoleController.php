<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Livewire\Company\Roles\Roles;
use App\Repository\Interface\RoleInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected RoleInterface $roleRepo;
    public function __construct(RoleInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    public function roles(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search', '');
            $perPage = $request->input('perPage', 10);
            $sortBy = $request->input('sortBy', 'newest');

            $roles = $this->roleRepo->paginateFiltered($search, $sortBy, $perPage);
            return response()->json([
                "status" => "success",
                "data" => $roles
            ]);
        } catch (\Exception $e) {

            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3|max:100|unique:roles,name',
            ]);
            $data = [
                'name' => $request->name,
            ];
            $role = $this->roleRepo->createdrole($data);

            return response()->json([
                "status" => "success",
                "data" => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $id = $role->id;
            $request->validate([
                'name' => 'required|string|min:3|max:100|unique:roles,name,' . $id,
            ]);
            $data = ['name' => $request->name];
            $this->roleRepo->updatedrole($data, $role);

            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil diperbarui',
                'data' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request, Role $role)
    {
        try {
            $this->roleRepo->deletedrole($role);

            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
