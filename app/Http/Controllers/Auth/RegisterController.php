<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Insurer;
use App\Models\Lender;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Auth/RegisterPage');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        try {
            $request->validate([
                'email' => ['required', 'email', 'unique:users'],
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'string', 'in:farmer,lender,insurer'],
            ]);

            // Additional validation based on role
            if ($request->role === 'farmer') {
                $request->validate([
                    'farm_name' => ['required', 'string', 'max:255'],
                    'farm_address' => ['required', 'string', 'max:500'],
                    'farm_contact' => ['required', 'string', 'max:255'],
                    'farm_size' => ['required', 'numeric', 'min:0'],
                    'farm_type' => ['required', 'string', 'in:crops,livestock,mixed'],
                ]);
            } elseif ($request->role === 'lender') {
                $request->validate([
                    'lender_name' => ['required', 'string', 'max:255'],
                    'lender_address' => ['required', 'string', 'max:500'],
                    'lender_contact' => ['required', 'string', 'max:255'],
                ]);
            } elseif ($request->role === 'insurer') {
                $request->validate([
                    'insurer_name' => ['required', 'string', 'max:255'],
                    'insurer_address' => ['required', 'string', 'max:500'],
                    'insurer_contact' => ['required', 'string', 'max:255'],
                ]);
            }

            //dd('here');

            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role using Laratrust
            $user->addRole($request->role);

            // Create role-specific data
            if ($request->role === 'farmer') {
                Farm::create([
                    'user_id' => $user->id,
                    'name' => $request->farm_name,
                    'address' => $request->farm_address,
                    'contact' => $request->farm_contact,
                    'size' => $request->farm_size,
                    'type' => $request->farm_type,
                ]);
            } elseif ($request->role === 'lender') {
                Lender::create([
                    'user_id' => $user->id,
                    'name' => $request->lender_name,
                    'address' => $request->lender_address,
                    'contact' => $request->lender_contact,
                ]);
            } elseif ($request->role === 'insurer') {
                Insurer::create([
                    'user_id' => $user->id,
                    'name' => $request->insurer_name,
                    'address' => $request->insurer_address,
                    'contact' => $request->insurer_contact,
                ]);
            }

            DB::commit();

            // Log in the user
            //Auth::login($user);


            return back()->with('success', 'Account created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
