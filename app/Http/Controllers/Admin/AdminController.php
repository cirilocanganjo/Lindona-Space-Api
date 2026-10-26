<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PaginationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, PaginationService $paginationService)
    {
        $perPage = $paginationService->perPage($request);
        return User::query()

        ->when(filled($request->searcher), function ($query) use ($request) {
            $query->whereHas('funcionario', function ($q) use ($request) {
                $q->where('nome_funcionario', 'like', "%{$request->input('searcher')}%");
                $q->orWhere('telefone_funcionario', $request->input('searcher'));
                $q->orWhere('email_funcionario', $request->input('searcher'));
            });           
         })

         ->when(filled($request->status), function ($query) use ($request) {
             $query->whereHas('funcionario', function ($q) use ($request) {
                $q->where('status',$request->input('status'));
            });           
  
         })

        ->whereHas('funcionario')  
        ->with('funcionario')  
        ->paginate($perPage);

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
        //
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

    public function getAllEmployeesCounter (): int
    {
      return User::query()->wherehas('funcionario')->count();
    }
   public function getAllActiveEmployeesCounter(): int
    {
        return User::query()
            ->whereHas('funcionario', fn ($query) => $query->where('status', 1))
            ->count();
    }
    public function getAllInactiveEmployeesCounter (): int
    
    {
        return User::query()
            ->whereHas('funcionario', fn ($query) => $query->where('status', 0))
            ->count();
    }

    public function changeUserStatus($id): JsonResponse
   {
    $user = User::query()->with('funcionario')->find($id);

    if (!$user) {
        return response()->json([
            'message' => 'Utilizador não encontrado.'
        ], 404);
    }

    if (!$user->funcionario) {
        return response()->json([
            'message' => 'Este utilizador não possui um registo de funcionário.'
        ], 404);
    }

    DB::transaction(function () use ($user) {
        $user->funcionario->update([
            'status' => !$user->funcionario->status,
        ]);
    });

    return response()->json([
        'message' => 'O estado do funcionário foi atualizado com sucesso!',
        'status' => (bool) $user->funcionario->fresh()->status,
    ]);
  }


}
