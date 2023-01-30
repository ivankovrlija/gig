<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class insertPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insertPosts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = array();
        $date_now = new \DateTime();
        for ($i=1; $i < 6; $i++) { 
            $post = [];
            $id = $i;
            $topic = substr(sha1(mt_rand()), 0, 7);
            $post = array(
                "id" => $id,
                "topic" => $topic,
                "created_at" => $date_now,
                "updated_at" => $date_now
            );
            array_push($posts, $post);
        }
        
        $requests = DB::table('posts')  
                ->insert($posts);

        echo "Finished Inserting Posts \n";
    }
}
