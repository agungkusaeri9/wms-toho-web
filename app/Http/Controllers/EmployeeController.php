<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $items = Employee::with('user')->orderBy('name', 'ASC')->get();
        return view('pages.employee.index', [
            'title' => 'Data Employee',
            'items' => $items
        ]);
    }
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('pages.employee.create', [
            'title' => 'Create Employee',
            'roles' => $roles
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'min:3'],
            'nik' => ['required', 'min:3'],
            'gender' => ['required', 'in:L,P'],
            'nik' => ['required', 'unique:employees,nik'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['min:5'],
            'roles' => ['required'],
            'gender' => ['required'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('name', request('roles'))->firstOrFail();
            $data_employee = request()->only(['name', 'nik', 'gender']);
            $data_user = request()->only(['name', 'email']);
            $data_user['password'] = bcrypt(request('password'));
            request()->file('avatar') ? $data_user['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;

            $user = User::create($data_user);
            $user->assignRole($role);
            $data_employee['user_id'] = $user->id;
            Employee::create($data_employee);
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee has been created successfully.');
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Employee::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('pages.employee.edit', [
            'title' => 'Edit Employee',
            'roles' => $roles,
            'item' => $item
        ]);
    }

    public function update($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        request()->validate([
            'name' => ['required', 'min:3'],
            'nik' => ['required', 'min:3', 'unique:employees,nik,' . $employee->id],
            'gender' => ['required', 'in:L,P'],
            'email' => ['required', 'unique:users,email,' . $employee->user->id],
            'password' => ['nullable', 'min:5'],
            'roles' => ['required'],
            'gender' => ['required'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);

        DB::beginTransaction();
        try {
            $data_employee = request()->only(['name', 'nik', 'gender']);
            $data_user = request()->only(['name', 'email']);
            $data_user['password'] = bcrypt(request('password'));
            $roles = request('roles');
            if (request()->file('avatar')) {
                if ($employee->avatar) {
                    Storage::disk('public')->delete($employee->user->avatar);
                }
                $data_user['avatar'] = request()->file('avatar')->store('users', 'public');
            }
            $employee->user->update($data_user);
            $employee->user->syncRoles($roles);
            $employee->update($data_employee);
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee has been updated Successfully.');
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Employee::with('user')->findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('success', 'Employee has been deleted Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
