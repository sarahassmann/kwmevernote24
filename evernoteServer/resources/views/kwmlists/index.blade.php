<!DOCTYPE html>
<html>
    <head>
        <title>KWM Evernote - Laravel</title>
    </head>
    <body>
        <ul>
            @foreach($kwmlists as $list)
                <li><a href="kwmlists/{{$list->id}}">{{$list->listName}} {{$list->created_at}}</a></li>
            @endforeach
        </ul>
    </body>
</html>
