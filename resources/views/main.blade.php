@include('layouts.header')

    <div class="container">
        <h1 class="text-center">{{ isset($pagetitl) ? $pagetitle : '' }}</h1>
    </div>

@if (isset($user->id))

    <form method="POST" id="id-form-messages">
        <div class="form-group">
            <lablel form="name">Сообщение: </lablel>
            <textarea class="form-control" rows="5" placeholder="Текст сообщения" name="messages" cols="50" id="messages"></textarea>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Добавить">
        </div>
    </form><hr>

@else
    Авторизоваться: <br>

    <a href="./?auth=fb" class="btn btn-social-icon btn-lg btn-facebook">
        <span class="fa fa-facebook"></span>
    </a>

    <a href="./?auth=vk" class="btn btn-social-icon btn-lg btn-vk">
        <span class="fa fa-vk"></span>
    </a>
@endif

<div class="text-right"><b>Всего сообщений</b> <i class="badge">{{ isset($count) ? $count : 0 }}</i></div><br/>


@if (!$messages->isEmpty())
    @foreach($messages as $message)
<div class="messages">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span>{!! $message->nickname !!}</span>
                <span class="pull-right label label-info">{!! $message->created_at !!}</span>
            </h3>
        </div>

        <div class="panel-body">{!! $message->msg !!}</div>
    </div>
</div>
@endforeach
    <div class="text-center">
        {!! $messages->render() !!}
    </div>
@else
    <div class="alert alert-warning">
        нет сообщений
    </div>
@endif
@include('layouts.footer')