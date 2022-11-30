<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;

class WordController extends Controller
{
    public static function getWords() {
        return Word::all();
    }

    public static function getWordByParsed($parsedWord) {
        return Word::where('parsed_word', $parsedWord)->get();
    }

    public static function postWordsFromUrl($url) {
        // read the content of txt file to a string
        $fileContentsString = file_get_contents($url);
        $fileContentsString = strtolower($fileContentsString);

        // encode the string from txt file
        $fileContentsString = mb_convert_encoding($fileContentsString, 'HTML-ENTITIES', 'UTF-8');

        $fileContentsArray = explode("\n", $fileContentsString);
        $unique_array = array_unique($fileContentsArray);
       
        $data_chunks = array_chunk($unique_array, 10000);
        $test = 0;
        forEach($data_chunks as $chunk) {
            $data = array();
            
            forEach($chunk as $value) {
                error_log($test);
                $test++;
                $parsedArray = str_split($value);
                sort($parsedArray);
                $parsed = implode($parsedArray);

                $word = ['original_word'=>$value, 'parsed_word'=>$parsed];

                array_push($data, $word);
            }

            Word::insert($data);
        }

        return (array_slice($fileContentsArray, 0, 20));
    }
}
