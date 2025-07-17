<?php

namespace App\GraphQL\Resolvers;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class NoteResolver
{
    protected $notes = [
        // ト音記号 (Treble clef)
        ['clef' => 'treble', 'note' => 'C', 'octave' => 4, 'position' => 0],
        ['clef' => 'treble', 'note' => 'D', 'octave' => 4, 'position' => 1],
        ['clef' => 'treble', 'note' => 'E', 'octave' => 4, 'position' => 2],
        ['clef' => 'treble', 'note' => 'F', 'octave' => 4, 'position' => 3],
        ['clef' => 'treble', 'note' => 'G', 'octave' => 4, 'position' => 4],
        ['clef' => 'treble', 'note' => 'A', 'octave' => 4, 'position' => 5],
        ['clef' => 'treble', 'note' => 'B', 'octave' => 4, 'position' => 6],

        ['clef' => 'treble', 'note' => 'C', 'octave' => 5, 'position' => 7],
        ['clef' => 'treble', 'note' => 'D', 'octave' => 5, 'position' => 8],
        ['clef' => 'treble', 'note' => 'E', 'octave' => 5, 'position' => 9],
        ['clef' => 'treble', 'note' => 'F', 'octave' => 5, 'position' => 10],

        // ヘ音記号 (Bass clef)
        ['clef' => 'bass', 'note' => 'C', 'octave' => 2, 'position' => 0],
        ['clef' => 'bass', 'note' => 'D', 'octave' => 2, 'position' => 1],
        ['clef' => 'bass', 'note' => 'E', 'octave' => 2, 'position' => 2],
        ['clef' => 'bass', 'note' => 'F', 'octave' => 2, 'position' => 3],
        ['clef' => 'bass', 'note' => 'G', 'octave' => 2, 'position' => 4],
        ['clef' => 'bass', 'note' => 'A', 'octave' => 2, 'position' => 5],
        ['clef' => 'bass', 'note' => 'B', 'octave' => 2, 'position' => 6],

        ['clef' => 'bass', 'note' => 'C', 'octave' => 3, 'position' => 7],
        ['clef' => 'bass', 'note' => 'D', 'octave' => 3, 'position' => 8],
        ['clef' => 'bass', 'note' => 'E', 'octave' => 3, 'position' => 9],
        ['clef' => 'bass', 'note' => 'F', 'octave' => 3, 'position' => 10],
    ];

    public function randomNote($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $selected = $this->notes[array_rand($this->notes)];

        // 正解情報をセッションに保存
        session(['correct_note' => $selected['note']]);
        session(['correct_clef' => $selected['clef']]); 

        return $selected;
    }

    public function answerNote($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $correctNote = session('correct_note');
        $correctClef = session('correct_clef');

        // 正解ノートがセッションにない場合は不正解
        if (!$correctNote) {
            return false;
        }

        // 例：noteだけ比較（必要ならclefも比較）
        return strtoupper($args['note']) === strtoupper($correctNote);
    }
}
