<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PaginationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
