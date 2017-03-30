@extends('layouts.template')
@section('page_content')
    @if(isset($responseNFE))
        <div class="page-title">
            <div class="title_left">
                <h3>Retorno da Nota Fiscal <i>(Código: {{$responseNFE['code']}})</i></h3>
            </div>
        </div>
        <section class="row">
            <div class="x_panel">
            </div>
            <div class="x_panel">
                <section class="x_content">
                    @if($responseNFE['error'])
                        <div class="alert fade in alert-danger" role="alert">
                            <ul>
                                @foreach($responseNFE['message'] as $message)
                                    <li><b>{{$message->codigo}}:</b> <i>{{$message->mensagem}}</i></li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert fade in alert-success" role="alert">
                            <?php print_r($responseNFE['message']); ?>
                        </div>
                    @endif
                </section>
            </div>
        </section>
    @endif
@endsection