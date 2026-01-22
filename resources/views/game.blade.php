<x-main-layout pageTitle="paises e capitais">

<div class="container">

<x-question
    :country="$country"
    :currentQuestion="$currentQuestion"
    :totalQuestion="$totalQuestion"
/>


    <div class="row">
        @foreach ($answers as $aswer)
            <x-aswer :capital="$aswer" />
        @endforeach
    </div>

</div>

<!-- cancel game -->
<div class="text-center mt-5">
    <a href="#" class="btn btn-outline-danger mt-3 px-5">CANCELAR JOGO</a>
</div>

</x-main-layout>
