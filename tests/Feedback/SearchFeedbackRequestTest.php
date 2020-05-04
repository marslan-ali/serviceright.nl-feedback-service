<?php

namespace Tests\Feedback;

use App\Domain\Models\Feedback;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use MicroServiceWorld\Domain\Models\Department;
use Tests\TestCase;

/**
 * Class SearchFeedbackRequestTest
 * @package Tests
 */
class SearchFeedbackRequestTest extends TestCase
{
    use DatabaseTransactions;

    public function test_search_feedback_success()
    {
        $department = $this->faker->name;
        factory(\App\Domain\Models\Feedback::class, 5)->state('accepted')->create([
            'department' => $department,
            'tags' => [
                'brand' => 'volkswagen',
                'model' => 'polo',
                'construction_year' => 2012,
                'city' => 'Almere'
            ]
        ]);

        $response = $this->json('GET', '/feedbacks/search?tags={"brand":"volkswagen","model":"polo", "construction_year": 2012}&minimum=3', [], [
            Department::departmentHeaderName() => $department
        ]);

        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'department', 'content', 'rating', 'created_at', 'updated_at']
            ]
        ]);
    }

    public function test_search_feedback_without_tags()
    {
        $department = $this->faker->name;
        factory(\App\Domain\Models\Feedback::class, 5)->state('accepted')->create([
            'department' => $department,
            'tags' => [
                'brand' => 'volkswagen',
                'model' => 'polo',
                'construction_year' => 2012,
                'city' => 'Almere'
            ]
        ]);

        $response = $this->json('GET', '/feedbacks/search?minimum=3', [], [
            Department::departmentHeaderName() => $department
        ]);

        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'department', 'content', 'rating', 'created_at', 'updated_at']
            ]
        ]);
    }

    public function test_search_with_minimum_count()
    {
        $department = $this->faker->name;

        // regular item
        factory(\App\Domain\Models\Feedback::class)->state('accepted')->create([
            'department' => $department
        ]);

        // volkswagen polo
        factory(\App\Domain\Models\Feedback::class)->state('accepted')->create([
            'department' => $department,
            'tags' => [
                'brand' => 'volkswagen',
                'model' => 'polo',
                'construction_year' => 2012,
                'city' => 'Almere'
            ]
        ]);

        // volkswagen golf
        factory(\App\Domain\Models\Feedback::class)->state('accepted')->create([
            'department' => $department,
            'tags' => [
                'brand' => 'volkswagen',
                'model' => 'golf',
                'construction_year' => 2012,
                'city' => 'Almere'
            ]
        ]);

        $response = $this->json('GET', '/feedbacks/search?tags={"brand":"volkswagen","model":"polo","construction_year":2012}&minimum=3',  [], [
            Department::departmentHeaderName() => $department
        ]);

        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'department', 'content', 'rating', 'created_at', 'updated_at']
            ]
        ]);
    }

    public function test_search_feedback_items_non_matching()
    {
        $department = $this->faker->name;

        // regular item
        factory(\App\Domain\Models\Feedback::class, 10)->state('accepted')->create([
            'department' => $department
        ]);

        $response = $this->json('GET', '/feedbacks/search?tags={"brand":"volkswagen","model":"polo", "construction_year": 2012}&minimum=3',  [], [
            Department::departmentHeaderName() => $department
        ]);

        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'department', 'content', 'rating', 'created_at', 'updated_at']
            ]
        ]);
    }

    public function test_search_with_empty_response()
    {
        $department = $this->faker->name;

        $feedback = factory(\App\Domain\Models\Feedback::class)->state('accepted')->create([
            'department' => $department,
            'tags' =>
                [
                    'brand'             => 'volkswagen',
                    'model'             => 'polo',
                    'construction_year' => 2012,
                    'city'              => 'Almere']
            ]
        );

        $response = $this->json('GET',
            '/feedbacks/search?tags={"brand":"volkswagen","model":"polo", "construction_year": 2012}&minimum=1');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data'
        ]);

        dump($feedback);
        dd($response->content());

        $response->assertJson([
            'data' => [[
                'id' => $feedback->getKey()
            ]]
        ]);

    }



}
