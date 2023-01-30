<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Schema;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $posts = [];
            $page = $request->input('page') ? $request->input('page') : 1;
            $limit = $request->input('limit') ? $request->input('limit') : 10;

            $post = new Post();
            $tableName = $post->getTable();
    
            $columns = Schema::getColumnListing($tableName);

            // handle sorting
            if($request->exists('sort')){
                if(in_array($request->input('sort'), $columns)){
                    if($request->exists('direction') && $request->input('direction') === 'desc'){
                        $posts = HelperController::getSortResults($columns, $tableName, $request->input('sort'), $request->input('direction'));
                    }else{
                        $posts = HelperController::getSortResults($columns, $tableName, $request->input('sort'));
                    }
                }else{
                    throw new \Exception('Invalid sort value');
                }
            }

            // handle get posts with comments
            if($request->exists('with') && $request->input('with') === 'comments'){
                $posts = $this->getPostsWithComments();
            }
                        
            // handle get posts by comment content
            if($request->exists('comment')){
                $posts = $this->getPostsByCommentContent($request->input('comment'));
            }
            
            // handle filtering
            if($posts === []){
                $query = Post::select();

                $posts = HelperController::buildQueryWithFilters($columns, $request->input(), $query);
            }

            $results = HelperController::structureResponse($posts, $page, $limit, $tableName);

            return response()->json($results, 200);
        } catch (\Exception $e) {
              Log::error($e->getMessage());
              return response()->json(['status' => 'error'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, int $id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post does not exists'
            ], 400);
        }

        $post->delete();

        return response()->json([ 'status' => 'success' ], 200);
    }

    private function getPostsByCommentContent(string $commentSearch){
        $posts = Post::with(['comments' => fn($query) => $query->where('content', 'LIKE', "%{$commentSearch}%")])
                ->whereHas('comments', fn ($query) => 
                    $query->where('content', 'LIKE', "%{$commentSearch}%")
                );

        return $posts;
    }

    private function getPostsWithComments(){
        $posts = Post::with('comments')->whereHas('comments');

        return $posts;
    }
}
