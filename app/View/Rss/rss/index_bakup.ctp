<?php 
if($content=='full'){
    $this->set('documentData', [
        'xmlns:link'=>"http://www.w3.org/2005/Atom",
    ]);
}

if($content=='full'){
    $this->set('channelData', array(
        'title' => __(SITEURL." Press Releases"),
        'link' =>$this->Html->url('/latest.rss', true),
        'description' => __("Newswire site",true),
        'copyright'=>"Copyright ".date('Y'),
        'atom:link' => array(
            'attrib' => array(
                'href' =>$this->Html->url('/latest.rss', true),
                'rel' => 'self',
                'type' => 'application/rss+xml'
            )
        ),
        'generator'=>SITEURL,
        'language' => 'en-us'
    ));
}else{
    $this->set('channelData', array(
        'title' => __("Email Wire Press Releases"),
        'link' =>$this->Html->url('/latest.rss', true),
        'description' => __("Newswire site",true),
        'copyright'=>"Copyright ".date('Y'),
        'atom:link' => array(
            'attrib' => array(
                'href' =>$this->Html->url('/latest.rss', true),
                'rel' => 'self',
                'type' => 'application/rss+xml'
            )
        ),
        'generator'=>"emailwire.com",
        'language' => 'en-us'
    ));
}

foreach ($data_arr as $data) {
    if(date('Y-m-d', strtotime($data['PressRelease']['release_date'])) <= date('Y-m-d')){
       // $this->Custom->UpdateClippingReport($data['PressRelease']['id'],$hostname,'rss_feed');

        $dataTime = strtotime($data['PressRelease']['release_date']);
        $siteurl=SITEURL.'release/'.$data['PressRelease']['slug'];
        $singleposturl=SITEURL.'rss/release.rss?s='.$data['PressRelease']['slug'].'&prid='.$data['PressRelease']['id'];
        
         // $image =$this->Post->getRssNewsSingleImage($data['PressImage']);    


        // Remove & escape any HTML to make sure the feed content will validate.
        $videos = '';
        $podcasts = '';
        $wordLimit="500";
        if($content=='full'){
            $image =$this->Post->getRssNewsImages($data['PressImage']);
            $videos =$this->Post->getRssNewsYoutubeVideos($data['PressYoutube']);
            $podcasts =$this->Post->getRssNewsPodcasts($data['PressPoadcast']);
            $wordLimit="500000";
            $bodyText = h(strip_tags($data['PressRelease']['body']));
        }else{
            $image =$this->Post->getRssNewsSingleImage($data['PressImage']);    
            $bodyText = h(strip_tags($data['PressRelease']['summary']));
        }

        $bodyText = $this->Text->truncate($bodyText, $wordLimit, array(
            'exact'  => true,
            'html'   => true,
        ));
        $bodyText =$image.$videos.$podcasts." \n".$bodyText;

        $category=[];
        if(!empty($data['Category'] )){
            foreach ($data['Category'] as $index => $cat) {
                $category[$index]=$cat['name'];
            }
        }
        $arr=array(
            'title' => $data['PressRelease']['title'],
            'link' =>$siteurl,
            'guid' => array('url' => $singleposturl, 'isPermaLink' => 'true'),
            'description' => '<img align="left" hspace="5" src="'.SITEURL.'rss/gif?v='.$data['PressRelease']['id'].'"/> '.$bodyText, 
            'pubDate' => date('Y-m-d', strtotime($data['PressRelease']['release_date']))
        );

        // if(!empty($image))
        //     $arr['image']=$image;

        if(!empty($category))
        $arr['category']=$category;

        echo $this->Rss->item(array(),$arr);
    }
}
