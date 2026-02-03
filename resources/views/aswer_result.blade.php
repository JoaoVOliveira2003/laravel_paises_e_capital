<x-main-layout pageTitle="paises e capitais">

    <div class="container">

        <div class="border border-primary rounded-5 p-3 text-center fs-3 mb-3">
            Pergunta: <span class="text-info fw-bolder"><span>{{$currentQuestion}} / {{$total_question}}</span>
            </span>
        </div>

        <div class="text-center fs-3 mb-3">
            Qual capital de {{ $country }}
        </div>


        <div class="text-center fs-3 mb-3">
            Resposta correta: <span class="text-info">{{$correct_aswer}}</span>
        </div>

        <div class="text-center fs-3 mb-3">
            A sua resposta: <span class="[conditional]">{{ $aswer }}</span>
        </div>


        <!-- cancel game -->
        <div class="text-center mt-5">
            <a href="{{route('next_question')}}" class="btn btn-primary mt-3 px-5">AVANÇAR</a>
        </div>

    </div>

</x-main-layout>
