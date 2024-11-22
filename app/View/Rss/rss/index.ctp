<?php 
$copyright=str_replace("#YEAR#",date('Y'), 'GroupWeb Media LLC 2006 - ##YEAR## All rights reserved.');
if($content=='full'){
    $this->set('documentData', [
        'xmlns:link'=>"http://www.w3.org/2005/Atom",
    ]);
}

if($content=='full'){
    $this->set('channelData', array(
        'title' => __($siteName." Press Releases"),
        'link' =>$this->Html->url('/latest.rss', true),
        'description' => __("News from EmailWire -- the global newswire with press release distribution services.",true),
        'copyright'=>$copyright,
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
        'title' => __("$siteName Press Releases"),
        'link' =>$this->Html->url('/latest.rss', true),
        'description' => __("News from EmailWire -- the global newswire with press release distribution services.",true),
        'copyright'=>$copyright,
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
}
if(!empty($data_arr)){
    foreach ($data_arr as $data) {
    // $this->Custom->UpdateClippingReport($data['PressRelease']['id'],$hostname,'rss_feed');

        $dataTime = strtotime($data['PressRelease']['release_date']);
        $siteurl=SITEURL.'release/'.$data['PressRelease']['slug'];
        $singleposturl=SITEURL.'rss/release.rss?s='.$data['PressRelease']['slug'];
        
        // $image =$this->Post->getRssNewsSingleImage($data['PressImage']);    
        $image =$this->Post->getRssNewsSingleImage($data['PressImage']);


        // Remove & escape any HTML to make sure the feed content will validate.
        $wordLimit="500";
        if($content=='full'){
            $wordLimit="500000";
            $bodyText = h(strip_tags($data['PressRelease']['body']));
        }else{
            $bodyText = h(strip_tags($data['PressRelease']['summary']));
        }

        $bodyText = $this->Text->truncate($bodyText, $wordLimit, array(
            // 'ending' => '...',
            'exact'  => true,
            'html'   => true,
        ));
        $bodyText =$image." \n".$bodyText;

        $category=[];
        if(!empty($data['Category'] )){
            foreach ($data['Category'] as $index => $cat) {
                $category[$index]=$cat['name'];
            }
        }
        $arr=array(
            'title' => $data['PressRelease']['title']." \n\r".'<img src="'.SITEURL.'rss/gif?v='.$data['PressRelease']['id'].'">',
            'link' =>$siteurl,
            'guid' => array('url' => $singleposturl, 'isPermaLink' => 'true'),
            'description' => $bodyText, 
            'pubDate' => $data['PressRelease']['release_date']
        );

        // if(!empty($image))
        //     $arr['image']=$image;

        if(!empty($category)){
           $arr['category']=$category;
           echo $this->Rss->item(array(),$arr);
        }
    }
}    
