<?php

namespace App\Repositories;

use Illuminate\Foundation\Http\FormRequest;

interface RepositoryInterface
{
    public function all();

    public function find($id);

    public function create(FormRequest $request);

    public function update($id, FormRequest $request);

    public function delete($id);
}