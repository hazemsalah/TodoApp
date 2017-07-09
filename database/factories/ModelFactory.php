<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Task::class, function ($faker) {
    return [
        'body' => $faker->sentence,
        'private' => false,
        'completed' => false,
        'deadline' => $faker->dateTime(\Carbon\Carbon::now()->addWeek()),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
$factory->define(\App\Comment::class, function ($faker) {
    return [
        'body'  =>  $faker->sentence,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'task_id' => function () {
            return factory(\App\Task::class)->create()->id;
        },
        'votes' => 0
    ];
});
//$factory->define(\App\File::class, function ($faker) {
//    return [
//        'comment_id' => function () {
//            return factory(\App\Comment::class)->create()->id;
//        },
//        'user_id' => function () {
//            return factory(App\User::class)->create()->id;
//        },
//
//    ];
//});


$factory->define(\App\Comment::class, function ($faker) {
    return [
        'body'  =>  $faker->sentence,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'commentable_id' => function () {
            return factory(\App\Task::class)->create()->id;
        },
        'commentable_type' => (new ReflectionClass(\App\Task::class))->getName()
        ,
        'votes' => 0
    ];
});

$factory->define(\App\Vote::class, function($faker){
   return[
       'voteable_id' => function(){
             return factory(App\Comment::class)->create()->id;
       },
       'user_id' => function () {
           return factory(App\User::class)->create()->id;
       },
        'voteable_type' => (new ReflectionClass(\App\Comment::class))->getName()
   ];
});

$factory->state(App\Vote::class, 'voteReply', function ($faker) {
    return [
        'voteable_id' => function(){
            return factory(App\Reply::class)->create()->id;
        },
        'voteable_type' => (new ReflectionClass(\App\Reply::class))->getName(),
    ];
});


$factory->state(App\Comment::class, 'ReplyId', function ($faker) {
    return [
        'commentable_id' => function(){
            return factory(App\Comment::class)->create()->id;
        },
        'commentable_type' => (new ReflectionClass(\App\Comment::class))->getName()
    ];
});