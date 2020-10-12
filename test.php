<?php

    require_once 'C:\xampp\server\htdocs\apitest\xplore-php-sdk.php'; //SDK directory

    $query = new XPLORE('dxjgm98sxnh2c5cgwzmcmy8f');
    if (!empty($_GET['query'])) {

        $query->queryText($_GET['query']);
        $query->dataType('json');
        $query->dataFormat('string');
        

    }
    /* Failed code
    if (!empty($_GET['query'])) { //if query is not empty when query is submitted

        $ieee_url = 'https://ieeexploreapi.ieee.org/api/v1/search/articles?apikey=dxjgm98sxnh2c5cgwzmcmy8f&article_title='. urlencode($_GET['query']);//url with appended query

        $ieee_json = file_get_contents($ieee_url);
        $ieee_arr = json_decode($ieee_json, true);

        $total_records = $ieee_arr['total_records'];
        $total_searched = $ieee_arr['total_searched']; 
        $article_title = $ieee_arr['articles']; 
    }
    */

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>IEEE Xplore API</title>
        <link rel="stylesheet" type="text/css" href="main.css" />
    </head>

    <body>
        <form action="" method="get" style="text-align:center;background-color:#7EC0EE;padding-top:1em;padding-bottom:1em;">
            <label style="font-weight:bold;"> Query: </label>
            <input type="text" name="query"/>
            <button style="border:none;" type="submit">Submit</button>
        </form>
        <div id="display_results" >
            <?php

                $results = $query->callAPI();   //                                      THE RETURNED ARRAY
                                                //                     catogory: ['total_searched','total_records','articles']                        => 
                                                //  general_data/article_number: ['data']/['article_number']                                          => 
                                                //           article_parameters: ['doi','title','publisher','isbn','rank','authors','access_type'...] => 
                                                //    article_data/sub_category: ['article_data']/['authors','author_terms','author']                 =>
                                                //       sub_category_numbering: ['numbering']                                                        =>
                                                //                      subdata: ['sub_data','sub_data_numbering']
                                                //                  sub_subdata: ['sub_subdata']
                if (!empty($results)) {
                    echo '<h1 style>IEEE</h1>';
                    foreach($results as $category=>$general_data){
                        if( $category === "articles" ){ // nested loop to display all articles and article_data  //
                            foreach($general_data as $article_num => $article_parameters){
                                echo '<div style="margin-top:5em;background-color:#d3d3d3;text-align:center;">'.$article_num.'</div>'; // display article numbering

                                foreach($article_parameters as $article_parameters => $article_data){ // display article parameters
                                    if( $article_parameters === "authors" || 
                                        $article_parameters === "index_terms" || 
                                        $article_parameters === "isbn_formats" ){ // display parameters with subparameters
                                        echo '<br>' .$article_parameters . ': <br>';

                                        foreach( $article_data as $sub_category => $sub_category_numbering){
                                            echo '<div class="tab1">' . $sub_category. ': </div><br>';

                                            foreach($sub_category_numbering as $sub_category_numbering => $subdata){
                                                echo '<div class="tab2">'. $sub_category_numbering. ': </div><br>';

                                                foreach( $subdata as $subdata => $sub_subdata){
                                                    echo '<div class="tab3">'. $subdata.': ' .$sub_subdata .'</div><br>';
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        echo '<br>' .$article_parameters . ': ' .$article_data. '<br>';
                                    }
                                }
                            }
                        }
                        else{                   //  category is total_records and total_searched   //
                            echo '<p>'.$category.': '.$general_data.'</p>';
                        }
                    }
                }else{
                    echo '<p>No results.</p>';
                }


            ?>
        </div>

    </body>

<html>