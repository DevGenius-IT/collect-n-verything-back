<?php

namespace App\Repositories;

use App\Models\Answer;

class AnswerRepository
{
    public function all()
    {
        return Answer::all();
    }

    public function find($id)
    {
        return Answer::findOrFail($id);
    }

    public function create(array $data)
    {
        return Answer::create($data);
    }

    public function update(Answer $answer, array $data)
    {
        $answer->update($data);
        return $answer;
    }

    public function delete(Answer $answer)
    {
        return $answer->delete();
    }
}
