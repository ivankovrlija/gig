<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;
use Schema;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $comments = [];
            $page = $request->input('page') ? $request->input('page') : 1;
            $limit = $request->input('limit') ? $request->input('limit') : 10;

            $comment = new Comment();
            $tableName = $comment->getTable();
    
            $columns = Schema::getColumnListing($tableName);
            
            // handle sorting
            if($request->exists('sort')){
                if(in_array($request->input('sort'), $columns)){
                    if($request->exists('direction') && $request->input('direction') === 'desc'){
                        $comments = HelperController::getSortResults($columns, $tableName, $request->input('sort'), $request->input('direction'));
                    }else{
                        $comments = HelperController::getSortResults($columns, $tableName, $request->input('sort'));
                    }
                }else{
                    throw new \Exception('Invalid sort value');
                }
            }

            // handle get comments with post data
            if($request->exists('with') && $request->input('with') === 'post'){
                $comments = $this->getCommentsWithPost();
            }

            // handle filtering
            if($comments === []){
                $query = Comment::select();

                $comments = HelperController::buildQueryWithFilters($columns, $request->input(), $query);
            }

            $results = HelperController::structureResponse($comments, $page, $limit, $tableName);

            return response()->json($results, 200);
        } catch (\Exception $e) {
              Log::error($e->getMessage());
              return response()->json(['status' => 'error'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'post_id' => 'required|int|exists:posts,id',
                'content'      => 'required|string',
                'abbreviation' => 'required|string',
            ]);

            if ( Comment::create($request->all()) ) {
                $response = [ 'status' => 'success' ];
            } else {
                $response = [ 'status' => 'error' ];
            }

            return response()->json($response, 200);

        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }

    //
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, int $id)
    {
        $comment = Comment::find($id);

        if(!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment does not exists'
            ], 400);
        }

        $comment->delete();

        return response()->json([ 'status' => 'success' ], 200);
    }

    private function getCommentsWithPost(){
        $comments = Comment::with('post')->whereHas('post');

        return $comments;
    }
}
