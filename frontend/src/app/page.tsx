"use client";

import { useState, useEffect, useRef } from "react";
import client from "../lib/apolloClient";
import { Renderer, Stave, StaveNote, Formatter } from "vexflow";

const RANDOM_NOTE_QUERY = gql`
  query {
    randomNote {
      clef
      note
      octave
      position
    }
  }
`;

export default function NoteGame() {
  const { data, loading, error, refetch } = useQuery(RANDOM_NOTE_QUERY, { client });
  const [feedback, setFeedback] = useState<string | null>(null);
  const divRef = useRef<HTMLDivElement>(null);

  const handleAnswer = async (selectedNote: string) => {
    const correctNote = localStorage.getItem('correct_note');
    if (correctNote && selectedNote.toUpperCase() === correctNote.toUpperCase()) {
      setFeedback("正解！");

      setTimeout(() => {
        setFeedback(null);

        refetch();
      }, 1500);

    } else {
      setFeedback("不正解..もう一度");
    }
  };

  // VexFlow描画処理
  useEffect(() => {

    if (data?.randomNote) {
      const correctNote = data.randomNote.note;
      localStorage.setItem('correct_note', correctNote);
    }

    if (!data?.randomNote || !divRef.current) return;

    divRef.current.innerHTML = "";

    const renderer = new Renderer(divRef.current, Renderer.Backends.SVG);
    renderer.resize(300, 200);
    const context = renderer.getContext();

    const stave = new Stave(10, 0, 120);
    context.scale(2, 2);
    stave.addClef(data.randomNote.clef === "treble" ? "treble" : "bass");
    stave.setContext(context).draw();

    const note = new StaveNote({
      keys: [convertToVexflowKey(data.randomNote.note, data.randomNote.clef, data.randomNote.position)],
      duration: "q",
      clef: data.randomNote.clef,
    });

    // 👇 Formatter で位置決めを行ってから描画！
    Formatter.FormatAndDraw(context, stave, [note]);

  }, [data]);

  // VexFlowが期待する音名変換関数
  function convertToVexflowKey(note: string, clef: string, position: number): string {
    let baseOctave = clef === 'treble' ? 4 : 3; // ト音記号は4、ヘ音記号は3を基準に
    if (position >= 7) {
      baseOctave += 1; // 2オクターブ目は1オクターブ足す
    }
    return note.toLowerCase() + "/" + baseOctave;
  }

  if (loading) return <p>読み込み中...</p>;
  if (error) return <p>エラーが発生しました: {error.message}</p>;

  const notes = [
    { letter: "C", japanese: "ド" },
    { letter: "D", japanese: "レ" },
    { letter: "E", japanese: "ミ" },
    { letter: "F", japanese: "ファ" },
    { letter: "G", japanese: "ソ" },
    { letter: "A", japanese: "ラ" },
    { letter: "B", japanese: "シ" },
  ];

  return (
    <div className="note-wrap">
      <h1>音符当てゲーム</h1>
      {/* VexFlow描画用のdiv */}
      <div className="note-line" ref={divRef}></div>

      <div className="note-button-wrap">
        {notes.map(({ letter, japanese }) => (
          <button className="note-button" key={letter} onClick={() => handleAnswer(letter)} style={{ margin: 5 }}>
            {japanese} 
            <br />
            {letter}
          </button>
        ))}
      </div>

      <div className="feedback">
        {feedback && <p>{feedback}</p>}
      </div>
    </div>
  );
}
