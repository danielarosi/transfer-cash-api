<?php
namespace App\Services;

use Illuminate\Http\Request;
/**
 * Interface do Usuário, assinaturas de métodos
 */
interface UserInterface
{
    public function all();
    public function show(int $id);
    public function store(Request $request);
}
