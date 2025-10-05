<?php

namespace App\Services;

use App\Contracts\DadosInterface;
use App\Contracts\FilesInterface;
use Illuminate\Support\Facades\Validator;

class DadosService
{
    public function __construct(
        private  DadosInterface $dadosRepository,
        private  FilesInterface $fileService
    ) {}

    public function listarTodos()
    {
        return $this->dadosRepository->all();
    }

    public function buscarPorId($id)
    {
        return $this->dadosRepository->find($id);
    }

    public function criar(array $dados)
    {
        $validacao = $this->validarDados($dados);
        if (!$validacao['success']) {
            throw new \InvalidArgumentException($validacao['message']);
        }

        $dados['anexo'] = $this->fileService->upload($dados['anexo']);
        $dados['salario'] = $this->formatarSalario($dados['salario']);
        $dados['ensino_medio'] = isset($dados['ensino_medio']) ? 1 : 0;

        return $this->dadosRepository->create($dados);
    }

    public function atualizar($id, array $dados)
    {
        $registro = $this->dadosRepository->find($id);
        if (!$registro) {
            return false;
        }

        $validacao = $this->validarDados($dados, $id);
        if (!$validacao['success']) {
            throw new \InvalidArgumentException($validacao['message']);
        }

        if (isset($dados['anexo'])) {
            $this->fileService->delete($registro->anexo);
            $dados['anexo'] = $this->fileService->upload($dados['anexo']);
        } else {
            $dados['anexo'] = $registro->anexo;
        }

        $dados['salario'] = $this->formatarSalario($dados['salario']);
        $dados['ensino_medio'] = isset($dados['ensino_medio']) ? 1 : 0;

        return $this->dadosRepository->update($id, $dados);
    }

    public function excluir($id)
    {
        $registro = $this->dadosRepository->find($id);
        if (!$registro) {
            return false;
        }

        $this->fileService->delete($registro->anexo);
        return $this->dadosRepository->delete($id);
    }

    public function getStatistics()
    {
        return $this->dadosRepository->getStatistics();
    }

    private function validarDados(array $dados, $id = null): array
    {
        $regras = [
            'nome' => 'required|string|max:150',
            'idade' => 'required|integer|min:1',
            'cep' => 'required|regex:/^\d{2}\.\d{3}-\d{3}$/',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|size:2',
            'rua' => 'required|string|max:150',
            'bairro' => 'required|string|max:100',
            'sexo' => 'required|in:Masculino,Feminino,Outro',
            'salario' => 'required|regex:/^\d{1,9}(\.\d{3})*,\d{2}$/',
        ];

        if (is_null($id)) {
            $regras['anexo'] = 'required|file|mimes:pdf,jpg,png|max:10240';
        } else {
            $regras['anexo'] = 'sometimes|file|mimes:pdf,jpg,png|max:10240';
        }

        $validator = Validator::make($dados, $regras, [
            'cep.regex' => 'Formato de CEP inválido. Use: 99.999-999',
            'salario.regex' => 'Formato de salário inválido. Use: 1.234,56',
            'anexo.mimes' => 'Apenas arquivos PDF, JPG e PNG são permitidos',
            'anexo.max' => 'O arquivo não pode ser maior que 10MB'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }

        return ['success' => true];
    }

    private function formatarSalario($salario): float
    {
        return (float) str_replace(['.', ','], ['', '.'], $salario);
    }
}