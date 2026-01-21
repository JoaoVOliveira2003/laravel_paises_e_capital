<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    private $appData;
    public function __construct()
    {
        $this->appData = require(app_path('app_data.php'));
    }

    public function mostrarJson()
    {
        return response()->json($this->appData);
    }

    public function prepareGame(Request $request)
    {
        $request->validate([
            'total_questions' => 'required|integer|min:3|max:30'
        ], [
            'total_questions.required' => 'O campo total de perguntas é obrigatório.',
            'total_questions.integer'  => 'O total de perguntas deve ser um número inteiro.',
            'total_questions.min'      => 'O total de perguntas deve ser no mínimo :min.',
            'total_questions.max'      => 'O total de perguntas deve ser no máximo :max.',
        ]);

        // getPost
        $nPerguntas = intval($request->input('total_questions'));

        $quiz = $this->criandoPerguntas($nPerguntas);

        session()->put([
            'quiz'=>$quiz,
            'total_question'=> $nPerguntas,
            'current_question'=> 1,
            'correct_aswers' => 0,
            'wrong_answers'=>0
        ]);

        return redirect()->route('game');

     }

    private function criandoPerguntas($nPerguntas)
    {
        $questions = [];
        $totalPaises = count($this->appData);

        $index = range(0, $totalPaises - 1);
        shuffle($index);

        //criando o array de questões
        $questionNumber = 1;

        foreach (array_slice($index, 0, $nPerguntas) as $i) {
            $question['question_number'] = $questionNumber++;
            $question['country'] = $this->appData[$i]['country'];
            $question['correct_answer'] = $this->appData[$i]['capital'];

            //resposta errada
            $other_capitals = array_column($this->appData, 'capital');
            //removendo a correta
            $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);

            shuffle($other_capitals);

            $question['wrong_aswer'] = array_slice($other_capitals, 0, 3);
            $question['correct'] = null;

            $questions[] = $question;
        }
        return $questions;
    }

    public function telaIncial()
    {
        return view('home');
    }
}
