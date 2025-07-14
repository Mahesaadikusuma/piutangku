<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\Interface\PermissionInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    protected PermissionInterface $permissionRepo;
    public function __construct(PermissionInterface $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    public function permissions(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search', '');
            $perPage = $request->input('perPage', 10);
            $sortBy = $request->input('sortBy', 'newest');

            $permissions = $this->permissionRepo->paginateFiltered($search, $sortBy, $perPage);
            return response()->json([
                "status" => "success",
                "data" => $permissions
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
                'name' => 'required|string|min:3|max:100|unique:permissions,name',
            ]);
            $data = [
                'name' => $request->name,
            ];
            $permission = $this->permissionRepo->createdPermission($data);

            return response()->json([
                "status" => "success",
                "data" => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3|max:100|unique:permissions,name,' . $id,
            ]);
            $permission = Permission::findOrFail($id);
            $data = ['name' => $request->name];

            $this->permissionRepo->updatedPermission($data, $permission);
            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil diperbarui',
                'data' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function delete(Request $request, Permission $permission)
    {
        try {
            $this->permissionRepo->deletedPermission($permission);
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
