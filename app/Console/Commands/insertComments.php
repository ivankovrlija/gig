<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class insertComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insertComments';

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
        $comments = [];
        $randomWords = "Cool,Strange,Funny,Laughing,Nice,Awesome,Great,Horrible,Beautiful,PHP,Vegeta,Italy,Joost";
        $randomWords = explode(",", $randomWords);
        $number_of_elements = count($randomWords);
        $number_of_combinations = 0;
        $combinations = $this->getCombinations($randomWords);
        $number_of_combinations = count($combinations);

        for ($i=1; $i <= $number_of_combinations; $i++) {
            $post_id = mt_rand(1,5);
            $content = implode(' ', $combinations[$i]);
            $abbreviation = '';

            foreach($combinations[$i] as $value){
                $abbreviation .= substr($value, 0, 1);
                
            }

            // sort abbreviation alphabetically
            $abbreviation = str_split($abbreviation);
                
            sort($abbreviation);
                
            $abbreviation = implode('', $abbreviation);
                 
            $comments[] = [
                'post_id' => $post_id,
                'content' => $content,
                'abbreviation' => $abbreviation,
                'created_at' => now(),
                'updated_at' => now()
            ];     
        }

        $insert_data = collect($comments); // Make a collection to use the chunk method

        // it will chunk the dataset in smaller collections containing 1000 values each. 
        $chunks = $insert_data->chunk(1000);

        foreach ($chunks as $chunk)
        {
            \DB::table('comments')->insert($chunk->toArray());
        }
        
        echo "Finished Inserting Comments \n";
    }

    /**
     * Create combinations.
     *
     * @return array
     */
    private function getCombinations(array $array): array{
        // initialize by adding the empty set
        $combinations = array(array( ));

        foreach ($array as $element){
            foreach ($combinations as $combination){
                $lowerCaseWords = strtolower($element);
                $mergeArrays = array_merge(array($lowerCaseWords), $combination);
                array_push($combinations, $mergeArrays);
            }
            
        }

        unset($combinations[0]);
        return $combinations;
    }
}
