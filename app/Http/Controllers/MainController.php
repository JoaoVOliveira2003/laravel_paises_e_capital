<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
            'quiz' => $quiz,
            'total_question' => $nPerguntas,
            'current_question' => 1,
            'correct_aswers' => 0,
            'wrong_answers' => 0
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

            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);
            $question['correct'] = null;

            $questions[] = $question;
        }
        return $questions;
    }

    public function game()
    {
        $quiz = session('quiz');
        $total_question = session('total_question');
        $current_question = session('current_question');

        $index = $current_question - 1;

        $answers = $quiz[$index]['wrong_answers'];
        $answers[] = $quiz[$index]['correct_answer'];

        shuffle($answers);

        return view('game')->with([
            'country' => $quiz[$index]['country'],
            'totalQuestion' => $total_question,
            'currentQuestion' => $current_question,
            'answers' => $answers
        ]);
    }


    public function telaIncial()
    {
        return view('home');
    }

    public function showResults()
    {
        // dd(session());

        $total = session('total_question');
        $correct_aswer = session('correct_aswer');
        return view('final_results')->with([
            'correct_aswer' => session('correct_aswer'),
            'wrong_aswer' => session('wrong_answers'),
            'total_question' => session('total_question'),
            'porcentagem' => round($correct_aswer / $total * 100, 2),
        ]);
    }
    public function aswer($aswer)
    {

        try {
            $aswer = Crypt::decryptString($aswer);
        } catch (\Exception $e) {
            return redirect()->route('game');
        }

        $quiz = session('quiz');
        $currrent_question = session('current_question') - 1;
        $correct_aswer = $quiz[$currrent_question]['correct_answer'];
        $wrong_aswer = session('wrong_answers');

        if ($aswer == $correct_aswer) {
            $correct_aswer++;
            $quiz[$currrent_question]['correct'] = true;
        } else {
            $wrong_aswer++;
            $quiz[$currrent_question]['correct'] = false;
        }

        session()->put([
            'quiz' => $quiz,
            'wrong_answers' => $wrong_aswer,
            'correct_answer' => $correct_aswer,
        ]);

        //preparar a datra para mostrar a resposta
        $data = [
            'country' => $quiz[$currrent_question]['country'],
            'correct_aswer' => $correct_aswer,
            'aswer' => $aswer,
            'currentQuestion' => $currrent_question,
            'total_question' => session('total_question'),
        ];

        return view('aswer_result')->with($data);
    }

    public function next_question()
    {
        $current_question = session('current_question');
        $total_question = session('total_question');

        if ($current_question < $total_question) {
            $current_question++;
            session()->put('current_question', $current_question);
            return redirect()->route('game');
        } else {
            return redirect()->route('showResults');
        }
    }

}
