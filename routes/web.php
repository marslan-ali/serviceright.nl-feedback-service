<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'hello';
//    return $router->app->version();
});

$router->get('/feedback/statistics/aggregate', 'FeedBackAggregate');

// the feedback request
$router->get('/feedback-request/{request}', 'FeedbackRequest\ShowFeedbackRequest');

// this is only accessible by internal systems
$router->post('/feedback-request', 'FeedbackRequest\CreateRequest');
$router->post('/feedback', 'Feedback\CreateFeedback');

$router->get('/feedback', 'Feedback\GetFeedbackByStatus');

$router->get('/feedbacks/search', 'Feedback\SearchFeedback');
