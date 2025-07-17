<?php

namespace App\GraphQL\Mutations;

class AnswerNote
{
    public function __invoke($_, array $args)
    {
        $correct = session('correct_note', null);

        if (!$correct || !isset($correct['note'])) {
            return false;
        }

        return strtoupper($args['note']) === strtoupper($correct['note']);
    }
}
