<?php 
require_once 'vendor/autoload.php';


class tumblrFeed{
    public function fetchfeeds($blogName,$limit){
        $consumerKey=Configure::read('tumblrConsumerKey');
        $consumerSecret=Configure::read('tumblrConsumerSecret');
        $token=Configure::read('tumblrToken');
        $tokenSecret=Configure::read('tumblrTokenSecret');
        // $consumerKey='2bU4wxtcVJ0VvXtu3QHSxDBxGj6K5KCVTCb867gnpiUeu7Yyh3';
        // $consumerSecret='8ZR65dPs7hD1GBH9TUtVl4dY3J9rU7yZdl47fCryX0MUpgLe00';
        // $token='6iEZfTFdG5yZNfM3yjOe4uM09JR79j72otmj40mDyOM0Bg6s1U';
        // $tokenSecret='wsnHXzcWD9gad80NtBxIVRfCC1KuGasnYCh5xqvAj3IBksBRdg';
        $client = new Tumblr\API\Client($consumerKey, $consumerSecret);
        $client->setToken($token, $tokenSecret); 
        $blogPosts=$client->getBlogPosts($blogName,['limit'=>$limit]);
        $getBlogAvatar=$client->getBlogAvatar($blogName, $size = null);

        $response['blog']['title']=$blogPosts->blog->title;
        $response['blog']['name']=$blogPosts->blog->name;
        $response['blog']['description']=$blogPosts->blog->description;
        $response['blog']['avtar']=$getBlogAvatar;
        $response['blog']['url']=$blogPosts->blog->url;
        foreach ($blogPosts->posts as $index => $post) {
            if($post->type=='text' || $post->type=='link' || $post->type=='photo'|| $post->type=='video'){
            $response['posts'][$index]['type']=$post->type;
            $response['posts'][$index]['post_url']=$post->post_url;
            $response['posts'][$index]['excerpt']=$response['posts'][$index]['summary']="";
                if($post->type!='photo'){
                    $response['posts'][$index]['title']=(isset($post->title)&&!empty($post->title))?$post->title:"";
                    $response['posts'][$index]['summary']=(isset($post->summary)&&!empty($post->summary))?$post->summary:"";
                    if(isset($post->excerpt)){
                        $response['posts'][$index]['excerpt']=$post->excerpt;
                    }
                } 
                if($post->type=='photo'&&!empty($post->photos)){
                $response['posts'][$index]['title']=(isset($post->photos[0]->caption)&&!empty($post->photos[0]->caption))?$post->photos[0]->caption:"";
                $response['posts'][$index]['image_url']=(isset($post->photos[0]->original_size->url)&&!empty($post->photos[0]->original_size->url))?$post->photos[0]->original_size->url:"";
                    if(empty($response['posts'][$index]['image_url'])){
                     $response['posts'][$index]['image_url']=(isset($post->photos[0]->alt_sizes[0]->url)&&!empty($post->photos[0]->alt_sizes[0]->url))?$post->photos[0]->alt_sizes[0]->url:"";
                    }
                }

                if($post->type=='video'){
                   $response['posts'][$index]['embed_code']=(isset($post->trail->thumbnail_url)&&!empty($post->trail->thumbnail_url))?$post->trail->thumbnail_url:"";
                   $response['posts'][$index]['thumbnail_url']=(isset($post->trail->player[0]->embed_code)&&!empty($post->trail->player[0]->embed_code))?$post->trail->player[0]->embed_code:"";
                }

            }
            
        } 


        return $response;
    }
}

    