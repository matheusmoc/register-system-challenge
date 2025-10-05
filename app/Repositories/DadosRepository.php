<?php
namespace App\Repositories;

use App\Contracts\DadosInterface;
use Illuminate\Support\Facades\DB;

class DadosRepository implements DadosInterface
{
    public function all()
    {
        return DB::select('SELECT * FROM dados ORDER BY id DESC');
    }

    public function find(int $id)
    {
        $result = DB::select('SELECT * FROM dados WHERE id = ?', [$id]);
        return $result[0] ?? null;
    }

    public function create(array $data)
    {
        DB::insert('INSERT INTO dados (nome, idade, cep, cidade, estado, rua, bairro, ensino_medio, sexo, salario, anexo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['nome'],
            $data['idade'],
            $data['cep'],
            $data['cidade'],
            $data['estado'],
            $data['rua'],
            $data['bairro'],
            $data['ensino_medio'] ? 1 : 0,
            $data['sexo'],
            $data['salario'],
            $data['anexo']
        ]);

        return DB::getPdo()->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        $afetadas = DB::update('UPDATE dados SET nome=?, idade=?, cep=?, cidade=?, estado=?, rua=?, bairro=?, ensino_medio=?, sexo=?, salario=?, anexo=? WHERE id=?', [
            $data['nome'],
            $data['idade'],
            $data['cep'],
            $data['cidade'],
            $data['estado'],
            $data['rua'],
            $data['bairro'],
            $data['ensino_medio'] ? 1 : 0,
            $data['sexo'],
            $data['salario'],
            $data['anexo'],
            $id
        ]);

        return $afetadas > 0;
    }

    public function delete(int $id)
    {
        $afetadas = DB::delete('DELETE FROM dados WHERE id = ?', [$id]);
        return $afetadas > 0;
    }

    public function getStatistics()
    {
        $total = DB::select('SELECT COUNT(*) as total FROM dados')[0]->total;
        $comEnsinoMedio = DB::select('SELECT COUNT(*) as total FROM dados WHERE ensino_medio = 1')[0]->total;
        $salarioMedio = DB::select('SELECT AVG(salario) as media FROM dados')[0]->media;
        $idadeMedia = DB::select('SELECT AVG(idade) as media FROM dados')[0]->media;
        $distribuicaoSexo = DB::select('SELECT sexo, COUNT(*) as total FROM dados GROUP BY sexo');

        return [
            'total' => $total,
            'com_ensino_medio' => $comEnsinoMedio,
            'salario_medio' => $salarioMedio,
            'idade_media' => $idadeMedia,
            'distribuicao_sexo' => $distribuicaoSexo
        ];
    }
}