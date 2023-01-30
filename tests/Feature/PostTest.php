<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test to check response status of PostController index.
     *
     * @return void
     */
    public function test_get_posts_response_status()
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test to check response json structure of PostController index.
     *
     * @return void
     */
    public function test_get_posts_response_json_structure()
    {
        $array = ['result' => [], 'count' => 5];
        $response = $this->get('/api/posts');

        $response->assertJson($array);
    }

    /**
     * A basic feature test to check response json structure for sort of PostController index.
     *
     * @return void
     */
    public function test_get_posts_by_sort_response_structure_and_sorting_fields()
    {
        $array = ['result' => [], 'count' => 5];
        $fields = ['id', 'topic', 'created_at', 'updated_at'];

        foreach($fields as $field){
            $url = '/api/posts?sort=' . $field;
            $response = $this->get($url);

            $response->assertJson($array);
        }
        
    }

    /**
     * A basic feature test to check response json structure for sort direction of PostController index.
     *
     * @return void
     */
    public function test_get_posts_by_sort_response_structure_and_sorting_direction()
    {
        $array = ['result' => [], 'count' => 5];
        $fields = ['id', 'topic', 'created_at', 'updated_at'];
        $directions = ['asc', 'desc'];

        foreach($fields as $field){
            for ($i=0; $i < count($directions); $i++) { 
                $url = '/api/posts?sort=' . $field . '&direction=' . $directions[$i];
                $response = $this->get($url);

                $response->assertJson($array);
            }
        }
        
    }

    /**
     * A basic feature test to check request default page and limit of PostController index.
     *
     * @return void
     */
    public function test_get_posts_default_page_and_limit()
    {
        $response = $this->get('/api/posts');

        $response->assertJsonFragment(['current_page' => 1]);
        $response->assertJsonFragment(['per_page' => 10]);
    }

    /**
     * A basic feature test to check request parameters page and limit of PostController index.
     *
     * @return void
     */
    public function test_get_posts_parameters_page_and_limit()
    {
        $current_page = 2;
        $per_page = 3;
        $url = '/api/posts?page=' . $current_page . '&limit=' . $per_page; 
        $response = $this->get($url);

        $response->assertJsonFragment(['current_page' => $current_page]);
        $response->assertJsonFragment(['per_page' => $per_page]);
    }

    /**
     * A basic feature test to check response for comment of PostController index.
     *
     * @return void
     */
    public function test_get_posts_by_comment_content()
    {
        $word = 'joost laughing';
        $url = '/api/posts?comment=' . $word; 
        $response = $this->get($url);

        $response->assertJsonFragment(['content' => sprintf("%s", $word, $word)]);
    }

    /**
     * A basic feature test to check delete request of PostController delete.
     *
     * @return void
     */
    public function test_delete_post()
    {
        $id = 1;
        $url = '/api/posts/' . $id; 
        $response = $this->delete($url);

        $response->assertJson([ 'status' => 'success' ]);
    }
}
