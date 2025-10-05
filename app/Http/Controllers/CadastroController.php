<?php

namespace App\Http\Controllers;

use App\Services\DadosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CadastroController extends Controller
{
    public function __construct(private DadosService $dadosService) {}

    public function index()
    {
        $registros = $this->dadosService->listarTodos();
        $estatisticas = $this->dadosService->getStatistics();
        
        return view('cadastro.cadastro', compact('registros', 'estatisticas'));
    }

    public function listar()
    {
        try {
            $registros = $this->dadosService->listarTodos();
            
            return response()->json([
                'success' => true,
                'data' => $registros
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar registros: ' . $e->getMessage()
            ], 500);
        }
    }

    public function estatisticas()
    {
        try {
            $estatisticas = $this->dadosService->getStatistics();
            
            return response()->json([
                'success' => true,
                'data' => $estatisticas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Dados recebidos no store:', $request->all());
            Log::info('Arquivo recebido:', ['has_file' => $request->hasFile('anexo')]);
            
            $dados = $request->all();
            $dados['anexo'] = $request->file('anexo');
            
            Log::info('Dados processados:', $dados);
            
            $this->dadosService->criar($dados);
            
            return response()->json([
                'success' => true,
                'message' => 'Cadastro realizado com sucesso!'
            ]);
        } catch (\InvalidArgumentException $e) {
            Log::error('Erro de validação:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro interno:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $registro = $this->dadosService->buscarPorId($id);
            
            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $registro
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar registro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $dados = $request->all();
            if ($request->hasFile('anexo')) {
                $dados['anexo'] = $request->file('anexo');
            }
            
            $sucesso = $this->dadosService->atualizar($id, $dados);
            
            if (!$sucesso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cadastro atualizado com sucesso!'
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sucesso = $this->dadosService->excluir($id);
            
            if (!$sucesso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registro excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir registro: ' . $e->getMessage()
            ], 500);
        }
    }
}