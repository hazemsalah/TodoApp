<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
<h1>You Have Been Invited To Task {{$user->name}}</h1>
<form method="POST" action="http://127.0.0.1:8000/api/followTask">
    {{ csrf_field() }}
    <input type ="hidden" name="user_id" value={{$user->id}} >
    <input type ="hidden" name="task_id" value={{$task->id}}>

    <button type="submit">Accept Invitation</button>
</form>
</body>
</html>