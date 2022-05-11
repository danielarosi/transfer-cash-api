<?php
namespace App\Services;

use Illuminate\Http\Request;
/**
 * Interface de Transações, assinaturas de métodos
 */
interface TransactionInterface
{
    public function all();
    public function show(string $column, $id);
    public function store(Request $request);
}
