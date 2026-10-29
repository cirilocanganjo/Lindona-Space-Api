<?php

namespace App\Http\Controllers\Admin;
use Hash;
use \Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\User;
use App\Services\PaginationService;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FuncionarioController extends Controller
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
        $user = User::query()->with('funcionario')
        ->whereHas('funcionario')
        ->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Nenhum resultado encontrado.'
            ]);
        }

        
        return response()->json([
            'data' => $user,
        ]);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, string $id)
    {
        $user = User::query()->where('id', $id)->with(['funcionario'])->first();   
        $employee = $user->funcionario;  
        $validated = $request->validated();

        $oldPhoto = $user->profile_photo;
        $newPhoto = null;

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photo = $request->file('photo');
            $newPhoto = Str::uuid() . '.' . $photo->extension();
            $photo->storeAs('img', $newPhoto, 'public');
        }

    try {
        DB::transaction(function () use ($user, $employee,$validated, $newPhoto) {
            $employee->update([
                'nome_funcionario' => $validated['name'],
                'salario' => $validated['salary'],
                'cargo' => $validated['role'],
                'status' => $validated['status'],
                'email_funcionario' => $validated['email'],
                'telefone_funcionario' => $validated['phone'],
                ...(!empty($validated['password']) ? [
                    'senha_funcionario' => Hash::make($validated['password']),
                ] : []),
            ]);

            if ($newPhoto !== null) {
                $user->update([
                    'profile_photo' => $newPhoto,
                ]);
            }
        });
    } catch (\Throwable $exception) {
        if ($newPhoto !== null) {
            Storage::disk('public')->delete('img/' . $newPhoto);
        }

        throw $exception;
    }

    if ($newPhoto !== null && $oldPhoto) {
        Storage::disk('public')->delete('img/' . $oldPhoto);
    }

    $employee->refresh();

    return response()->json([
        'message' => 'Operação realizada com sucesso.',
        'data' => [
            'name' => $employee->nome_funcionario,
            'email' => $employee->email_funcionario,
            'phone' => $employee->telefone_funcionario,
            'profile_photo' => $user->fresh()->profile_photo,
        ],
    ]);
    
    }
  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
   {
    $user = User::query()->where('id', $id)->with(['funcionario'])->first();

    if (!$user) {
        return response()->json([
            'message' => 'Funcionário não encontrado.'
        ], 404);
    }

    $employee = $user->funcionario;
    $photoToDelete = $user->profile_photo;

    try {
        DB::transaction(function () use ($user, $employee) {            
            $user->delete();
        });

    } catch (\Throwable $exception) {
        return response()->json([
            'message' => 'Ocorreu um erro ao realizar a operação.',
            'error' => $exception->getMessage()
        ], 500);
    }    

    return response()->json([
        'message' => 'Funcionário removido com sucesso.'
    ]);
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
