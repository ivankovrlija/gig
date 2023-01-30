<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic feature test to check response status of CommentController index.
     *
     * @return void
     */
    public function test_get_comments_response_status()
    {
        $response = $this->get('/api/comments');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test to check response json structure of CommentController index.
     *
     * @return void
     */
    public function test_get_comments_response_json_structure()
    {
        $array = ['result' => [], 'count' => 8191];
        $response = $this->get('/api/comments');

        $response->assertJson($array);
    }

    /**
     * A basic feature test to check response json structure for sort of CommentController index.
     *
     * @return void
     */
    public function test_get_comments_by_sort_response_structure_and_sorting_fields()
    {
        $array = ['result' => [], 'count' => 8191];
        $fields = ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'];

        foreach($fields as $field){
            $url = '/api/comments?sort=' . $field;
            $response = $this->get($url);

            $response->assertJson($array);
        }
        
    }

    /**
     * A basic feature test to check response json structure for sort direction of CommentController index.
     *
     * @return void
     */
    public function test_get_comments_by_sort_response_structure_and_sorting_direction()
    {
        $array = ['result' => [], 'count' => 8191];
        $fields = ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'];
        $directions = ['asc', 'desc'];

        foreach($fields as $field){
            for ($i=0; $i < count($directions); $i++) { 
                $url = '/api/comments?sort=' . $field . '&direction=' . $directions[$i];
                $response = $this->get($url);

                $response->assertJson($array);
            }
        }
        
    }

    /**
     * A basic feature test to check request default page and limit of CommentController index.
     *
     * @return void
     */
    public function test_get_comments_default_page_and_limit()
    {
        $response = $this->get('/api/comments');

        $response->assertJsonFragment(['current_page' => 1]);
        $response->assertJsonFragment(['per_page' => 10]);
    }

    /**
     * A basic feature test to check request parameters page and limit of CommentController index.
     *
     * @return void
     */
    public function test_get_comments_parameters_page_and_limit()
    {
        $current_page = 2;
        $per_page = 3;
        $url = '/api/comments?page=' . $current_page . '&limit=' . $per_page; 
        $response = $this->get($url);

        $response->assertJsonFragment(['current_page' => $current_page]);
        $response->assertJsonFragment(['per_page' => $per_page]);
    }

    /**
     * A basic feature test to check delete request of CommentController delete.
     *
     * @return void
     */
    public function test_delete_comment()
    {
        $id = 400899;
        $url = '/api/comments/' . $id; 
        $response = $this->delete($url);

        $response->assertJson([ 'status' => 'success' ]);
    }
}
