<?php

App::uses('Folder', 'Utility');

App::uses('File', 'Utility');

App::uses('AppHelper', 'View/Helper');

App::uses('Helper', 'View/Helper');

App::uses('QimageComponent', 'Controller/Component');

class PostHelper extends AppHelper
{

    public $helpers = array('Html', 'Form');
    public function getPrSingleImage($imageArr = '', $isCropOrResize = "resize", $width = "", $height = "0", $x = "0", $y = "0", $is_desc = '', $class = 'press-image')
    {
        $noimageUrl = SITEFRONTURL . "img/ew_no_image.jpg";
        $noImgfileUrl = ROOT.'app'.DS.'webroot'.DS . 'img' . DS . 'ew_no_image.jpg';
        $noImgdest = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb' . DS . str_replace(".jpg", "", 'ew_no_image.jpg') . '-' . $width . 'x' . $height . '.jpg';
        // $imageUrl=$this->cropImage('ew_no_image.jpg',$noimageUrl,$width,$height,$x,$y,'crop','',$noImgdest,$noImgfileUrl);

        $image_alt = '';

        if (!empty($imageArr)) {
            $imageInfo=$imageArr[0];
            $class .= ' ' . 'primg-' . $imageInfo['id'];
            $image_path = $imageInfo['image_path'];
            $image_name = $imageInfo['image_name'];
            $image_alt = (isset($imageInfo['image_text'])) ? $imageInfo['image_text'] : "";
            $describe_image = (isset($imageInfo['describe_image'])) ? $imageInfo['describe_image'] : ""; 
            $ext = pathinfo($image_name, PATHINFO_EXTENSION); $ext;
            // $fileUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
            $imageUrl = SITEFRONTURL . 'files/company/press_image/' . $image_path . '/' . $image_name;
            $fileUrl = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'press_image' . DS . $image_path . DS . $image_name;
            if (!file_exists($fileUrl)) {
                // $imageUrl=$this->resizeImage('ew_no_image.jpg',$noimageUrl,$width,$height,'resize','',$noImgdest,$noImgfileUrl);
                
                $imageUrl = $this->cropImage($image_name, $noimageUrl, $width, $height, $x, $y, 'crop', '', $noImgdest, $noImgfileUrl);
            } else {
                $dest = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb' . DS . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
              
                if ($isCropOrResize == "resize") {
                    $imageUrl = $imageUrl = $this->resizeImage($image_name, $imageUrl, $width, $height, 'resize', '', $dest, $fileUrl);
                }else if ($isCropOrResize == "crop") {
                    $imageUrl = $this->cropImage($image_name, $imageUrl, $width, $height, $x, $y, 'crop', '', $dest, $fileUrl);
                }else if ($isCropOrResize == "resizeandcrop") {
                    $imageUrl = $this->resizeAndCropImage($image_name, $imageUrl, $width, $height, $x, $y, $dest, $fileUrl);
                }
            }
        }else{
            $imageUrl = $this->resizeImage('ew_no_image.jpg', $noimageUrl, $width, $height, 'resize', '', $noImgdest, $noImgfileUrl);
        }
        return $this->Html->image($imageUrl, array('class' => $class, "width" => "100%", "alt" => $image_alt));
    } 

    private function resizeImage($image_name, $imageUrl, $width = '', $height = "0", $prefix = "resize", $isOrignalname = "", $dest, $fileUrl)
    {

        if (extension_loaded('imagick')) {

            if (class_exists("Imagick")) {

                list($imageWidth, $imageHeight) = getimagesize($fileUrl);

                $ext = pathinfo($image_name, PATHINFO_EXTENSION);

                if ($width > 0 && $imageWidth > $width && $imageHeight >= $height) {

                    if (!file_exists($dest)) {

                        if ($this->crop_resize_image($fileUrl, $dest, 80, $width, $height)) {

                            $imageUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
                        }
                    } else {

                        $imageUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
                    }
                }
            }

            return $imageUrl;
        } else {

            $collection = new ComponentCollection();

            $this->Qimage = new QimageComponent($collection);

            $image_data = array('width' => $width, 'height' => $height, 'file' => $imageUrl, 'output' => ROOT.'app'.DS.'webroot'.DS . 'files/company/press_image/thumb/', "prefix" => $prefix, "isOrignalname" => $isOrignalname);

            $this->Qimage->resize($image_data);

            $imageUrl = $this->getResizedImage($image_name, $width, $height, $prefix, $isOrignalname);

            return $imageUrl;
        }
    }



    private function cropImage($image_name, $imageUrl, $width, $height, $x, $y, $prefix = "crop", $isOrignalname = "", $dest, $fileUrl)
    {
     

        if (extension_loaded('imagick')) {

            if (class_exists("Imagick")) {

                list($imageWidth, $imageHeight) = getimagesize($fileUrl);

                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                
                if ($width > 0 && $imageWidth > $width && $imageHeight >= $height) {
                    if (!file_exists($dest)) {
                        if ($this->crop_resize_image($fileUrl, $dest, 80, $width, $height)) {
                            $imageUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
                        }
                    } else {
                        $imageUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
                    }
                }
            }

            return $imageUrl;
        } else {

            $collection = new ComponentCollection();

            $this->Qimage = new QimageComponent($collection);

            $this->Qimage->crop(array('w' => $width, 'h' => $height, 'x' => $x, 'y' => $y, 'file' => $imageUrl, 'output' => ROOT.'app'.DS.'webroot'.DS . 'files/company/press_image/thumb/', "prefix" => $prefix, "isOrignalname" => $isOrignalname));

            $imageUrl = $this->getResizedImage($image_name, $width, $height, $prefix, $isOrignalname);

            return $imageUrl;
        }
    }



    private function resizeAndCropImage($image_name, $imageUrl, $width, $height, $x = 0, $y = 10, $prefix = "reszcrp")
    {

        if (extension_loaded('imagick')) {

            if (class_exists("Imagick")) {

                list($imageWidth, $imageHeight) = getimagesize($fileUrl);

                $ext = pathinfo($image_name, PATHINFO_EXTENSION);

                if ($width > 0 && $imageWidth > $width && $imageHeight >= $height) {

                    if (!file_exists($dest)) {

                        if ($this->crop_resize_image($fileUrl, $dest, 80, $width, $height)) {

                            $imageUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
                        }
                    } else {

                        $imageUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
                    }
                }
            }

            return $imageUrl;
        } else {

            $collection = new ComponentCollection();

            $this->Qimage = new QimageComponent($collection);

            $rewidth = $width + 100;

            $reheight = 0;

            $this->Qimage->resize(array('width' => $width, 'height' => $reheight, 'file' => $imageUrl, 'output' => ROOT.'app'.DS.'webroot'.DS . 'files/company/press_image/thumb/', "isOrignalname" => "true"));

            $resizedImageUrl = $this->getResizedImage($image_name, $width, $reheight, "", "true");



            $imageUrl = $this->cropImage($image_name, $resizedImageUrl, $width, $height, $x, $y, $prefix);



            $file_path = ROOT.'app'.DS.'webroot'.DS . DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb';

            $delfile = new File($file_path . DS . $image_name, false, 0777);

            $delfile->delete();



            return $imageUrl;
        }
    }



    public function crop_resize_image($src, $dest, $quality = 50, $thum_width = 315, $thum_height = null)
    {

        if (extension_loaded('imagick')) {

            if (class_exists("Imagick")) {

                $img = new Imagick();

                $img->readImage($src);

                $img->setImageFormat('jpg');

                $img->setImageCompression(imagick::COMPRESSION_JPEG);

                $img->setImageCompressionQuality($quality);

                $img->stripImage();

                // $img->thumbnailImage($thum_width,$thum_height);

                $img->cropThumbnailImage($thum_width, $thum_height, true);

                $img->writeImage($dest);

                return true;
            } else {

                echo "<br /> Imagick class not found.";
            }
        } else {

            echo 'imagick extension not loaded';
        }
    }



    public function getResizedImage($imageName = '', $width, $height, $prefix = "", $isOrignalname = "")
    {

        $output = "";

        if (!empty($imageName)) {

            $filebasename = basename($imageName);

            $ext = pathinfo($filebasename, PATHINFO_EXTENSION);

            $dir = 'thumb';



            if (!empty($prefix)) {

                $prefix .= "-";
            }

            $output = SITEFRONTURL . 'files/company/press_image/' . $dir . '/' . $prefix . str_replace("." . $ext, "", $filebasename) . '-' . $width . 'x' . $height . '.' . $ext;

            if ((!empty($isOrignalname) && $isOrignalname == "true")) {

                $output = SITEFRONTURL . 'files/company/press_image/' . $dir . '/' . str_replace("." . $ext, "", $filebasename) . '.' . $ext;
            }
        }

        return $output;
    }



    public function getEmbedCode($url = '')
    {

        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';

        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {

            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {

            $youtube_id = $matches[count($matches) - 1];
        }

        $videoIfram = "<iframe width='100%' height='300' src='https://www.youtube.com/embed/$youtube_id' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";

        return $videoIfram;
    }







    public function getYouTubeId($url = '')
    {
        $youtube_id = '';
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';

        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {

            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {

            $youtube_id = $matches[count($matches) - 1];
        }

        return $youtube_id;
    }

    public function createprslug($slug = '')
    {

        return SITEFRONTURL . 'release/' . $slug;
    }



    public function get_title($title = '', $slug = '')
    {

        return ($slug != '') ? "<a class='ew-link-title' href='" . SITEFRONTURL . "release/" . $slug . "'>$title</a>" : $title;
    }



    public function getNewsroomLogo($logo_path = '', $logo = '', $slug = "")
    {

        $obj = ClassRegistry::init('companies');

        $data = $obj->find('all', array('conditions' => array('companies.slug' => $slug)));

        $check_incomplete_newsroom = $data[0]['companies'];

        unset($check_incomplete_newsroom['docfile']);

        unset($check_incomplete_newsroom['docfile_path']);

        unset($check_incomplete_newsroom['symbol']);

        unset($check_incomplete_newsroom['email']);

        unset($check_incomplete_newsroom['about_us']);

        unset($check_incomplete_newsroom['fb_link']);

        unset($check_incomplete_newsroom['twitter_link']);

        unset($check_incomplete_newsroom['youtube_link']);

        unset($check_incomplete_newsroom['instagram']);

        unset($check_incomplete_newsroom['pinterest']);

        unset($check_incomplete_newsroom['term_and_conditions']);

        unset($check_incomplete_newsroom['payment_status']);

        unset($check_incomplete_newsroom['disapproval_reason']);

        unset($check_incomplete_newsroom['disapproval_date']);

        unset($check_incomplete_newsroom['fax_number']);

        unset($check_incomplete_newsroom['web_site']);

        unset($check_incomplete_newsroom['blog_url']);

        unset($check_incomplete_newsroom['tumblr']);

        unset($check_incomplete_newsroom['linkedin']);

        unset($check_incomplete_newsroom['suspended_date']);

        if ($check_incomplete_newsroom['status'] != 1) {

            if ($check_incomplete_newsroom['organization_type_id'] == '0') {

                $slug = '';
            } elseif ($check_incomplete_newsroom['country_id'] == '0') {

                $slug = '';
            } elseif (in_array("", $check_incomplete_newsroom)) {

                $slug = '';
            }
        }





        $imageUrl = SITEFRONTURL . 'files/company/logo/' . $logo_path . '/' . $logo;



        $fileUrl = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'logo' . DS . $logo_path . DS . $logo;



        if (!file_exists($fileUrl)) {

            $imageUrl = SITEFRONTURL . "img/no-logo-provided.png";
        }

        if (empty($logo)) {

            $imageUrl = SITEFRONTURL . "img/no-logo-provided.png";
        }

        $logo = "<img src='" . $imageUrl . "' width='50px' height='50px'> <meta itemprop='url' content='" . $imageUrl . "'>";

        if (!empty($slug)) {

            $logo = "<a href='" . SITEFRONTURL . 'newsroom/' . $slug . "'><img src='" . $imageUrl . "' width='50px' height='50px' /></a><meta itemprop='url' content='" . $imageUrl . "'>";
        }

        return $logo;
    }



    public function company_name($id)
    {

        $obj = ClassRegistry::init('companies');

        $data = $obj->find('all', array('conditions' => array('companies.id' => $id)));

        return $data[0]['companies']['name'];
    }





    public function get_company($title = '', $slug = '')
    {

        if ($slug != '') {

            $obj = ClassRegistry::init('companies');

            $data = $obj->find('all', array('conditions' => array('companies.slug' => $slug)));

            $check_incomplete_newsroom = $data[0]['companies'];

            unset($check_incomplete_newsroom['docfile']);

            unset($check_incomplete_newsroom['docfile_path']);

            unset($check_incomplete_newsroom['symbol']);

            unset($check_incomplete_newsroom['email']);

            unset($check_incomplete_newsroom['about_us']);

            unset($check_incomplete_newsroom['fb_link']);

            unset($check_incomplete_newsroom['twitter_link']);

            unset($check_incomplete_newsroom['youtube_link']);

            unset($check_incomplete_newsroom['instagram']);

            unset($check_incomplete_newsroom['pinterest']);

            unset($check_incomplete_newsroom['term_and_conditions']);

            unset($check_incomplete_newsroom['payment_status']);

            unset($check_incomplete_newsroom['disapproval_reason']);

            unset($check_incomplete_newsroom['disapproval_date']);

            unset($check_incomplete_newsroom['fax_number']);

            unset($check_incomplete_newsroom['web_site']);

            unset($check_incomplete_newsroom['blog_url']);

            unset($check_incomplete_newsroom['tumblr']);

            unset($check_incomplete_newsroom['linkedin']);

            unset($check_incomplete_newsroom['suspended_date']);

            if ($check_incomplete_newsroom['status'] != 1) {

                if ($check_incomplete_newsroom['organization_type_id'] == '0') {

                    return "<a class='ew-link-title'>$title</a>";
                } elseif ($check_incomplete_newsroom['country_id'] == '0') {

                    return "<a class='ew-link-title'>$title</a>";
                } elseif (in_array("", $check_incomplete_newsroom)) {

                    return "<a class='ew-link-title'>$title</a>";
                }
            } else {

                return "<a class='ew-link-title' href='" . SITEFRONTURL . "newsroom/" . $slug . "'>$title</a>";
            }
        } else {

            return $title;
        }
    }



    // public function get_company($title='',$slug=''){

    //     return ($slug!='')?"<a class='ew-link-title' href='".SITEFRONTURL."newsroom/".$slug."'>$title</a>":$title;

    // }



    //Jaswinder

    // public function get_company_status($id=''){

    //     if($id!=''){

    //         $obj = ClassRegistry::init('companies');

    //         $data=$obj->find('first',array('conditions'=>array('companies.id'=>$id),'fields'=>array('status')));

    //     }

    //     return $data['companies']['status'];

    // }



    
    public function getSocialShares()
    {

        $obj = ClassRegistry::init('SocialShare');

        $list = $obj->find('all', array('conditions' => array('SocialShare.status' => 1), 'fields' => ['title', 'sharer_url', 'icon_url', 'weight'], 'order' => 'weight ASC'));

        return $list;
    }



    public function sharelinks($title = '', $slug = '', $body = '', $image = '')
    {

        $html = '';

        $socials = $this->getSocialShares();

        // $fapp_id ='916184228770270';



        if (!empty($socials)) {

            $html .= "<ul class='sharelinks'>";

            foreach ($socials as $social_values) {

                $socialIcon = strtolower($social_values['SocialShare']['title']);
                $iconUrl = $social_values['SocialShare']['icon_url'];
                $sharer_url = $social_values['SocialShare']['sharer_url'];
                $get_html = $this->getSocialSharesHtml($title, $slug, $body, $image, $socialIcon, $sharer_url,$iconUrl);
                $html_slug = str_replace('$slug', $slug, $get_html);
                $html_title = str_replace('$title', $title, $html_slug);
                $html_body = str_replace('$body', $body, $html_title);
                $html .= str_replace('$image', $image, $html_body);
            }

            // $url = SITEURL . 'rss/release.rss?s=' . $slug; $html .= $this->getSocialSharesHtml($title, $slug, $body, $image, 'rss', $url);

            $html .= "</ul>";
        }



        return $html;
    }



    public function getSocialSharesHtml($title = '', $gslug = '', $body = '', $image = '', $socialIcon, $sharer_url = '',$iconUrl='')
    {

        $slug = $gslug;

        $html = '';

        $html = "<li class='ew-" . $socialIcon . "'><a target='_blank' id='prev-" . $socialIcon . "' rel='nofollow'  href='" . $sharer_url . "'><img width='35px;' src='".SITEFRONTURL.$iconUrl."' /></a></li>";



        return $html;
    }





    public function getRssNewsSingleImage($imageArr = '')
    {

        $noimageUrl = SITEFRONTURL . "img/ew_no_image.jpg";

        if (!empty($imageArr)) {

            $image_path = $imageArr[0]['image_path'];

            $image_name = $imageArr[0]['image_name'];

            $imageUrl = SITEFRONTURL . 'files/company/press_image/' . $image_path . '/' . $image_name;
        }

        return $this->Html->image($imageUrl);

        // return $imageUrl;

    }



    public function getRssNewsImages($imageArr)
    {

        $noimageUrl = SITEFRONTURL . "img/ew_no_image.jpg";

        $imgs = '';

        if (!empty($imageArr)) {

            foreach ($imageArr as $key => $value) {

                $image_path = $value['image_path'];

                $image_name = $value['image_name'];

                $imageUrl = SITEFRONTURL . 'files/company/press_image/' . $image_path . '/' . $image_name;

                $imgs .= $this->Html->image($imageUrl);
            }
        }

        return $imgs;
    }



    public function getRssNewsYoutubeVideos($youtube)
    {

        $videos = '';

        if (!empty($youtube)) {

            foreach ($youtube as $key => $value) {

                $video_id = explode("?v=", $value['url']);

                $videos .= '<iframe width="100%" height="300" src="https://www.youtube.com/embed/' . $video_id[1] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>';
            }
        }

        return $videos;
    }



    public function getRssNewsPodcasts($podcast)
    {

        $podcasts = '';

        if (!empty($podcast)) {

            foreach ($podcast as $key => $value) {

                $podcasts .= $value['url'];
            }
        }

        return $podcasts;
    }



    public function countPRviews($id = '')
    {

        $obj = ClassRegistry::init('PressRelease');

        $data = $obj->find('first', array('conditions' => array('PressRelease.id' => $id), 'fields' => array('views')));



        return $data['PressRelease']['views'];
    }





    public function rudr_instagram_api_curl_connect($api_url)
    {

        $connection_c = curl_init(); // initializing

        curl_setopt($connection_c, CURLOPT_URL, $api_url); // API URL to connect

        curl_setopt($connection_c, CURLOPT_RETURNTRANSFER, 1); // return the result, do not print

        curl_setopt($connection_c, CURLOPT_TIMEOUT, 20);

        $json_return = curl_exec($connection_c); // connect and get json data

        curl_close($connection_c); // close connection

        return json_decode($json_return); // decode and return

    }





    public function getInstagramUsername($instagram_url)
    {

        $instagram_username = "";

        $regex = '/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/([A-Za-z0-9-_\.]+)/im';

        if (preg_match($regex, $instagram_url, $matches)) {

            $instagram_username = $matches[1];
        }

        return $instagram_username;
    }



    public function insta_user_images($instagram_url)
    {

        $insta_array = [];

        $username = $this->getInstagramUsername($instagram_url);

        if (!empty($username)) {

            $insta_source = file_get_contents('https://www.instagram.com/' . $username . '/'); // instagram user url

            $shards = explode('window._sharedData = ', $insta_source);

            $insta_json = explode(';</script>', $shards[1]);

            $insta_array = json_decode($insta_json[0], TRUE);
        }

        return $insta_array;
    }



    public function get_companyfile($docpath, $docfile)
    {

        return SITEFRONTURL . "files/company/docfile/" . $docpath . '/' . $docfile;
    }





    public function wordLimit($str, $slug = "", $charlimit = 35, $page = 'homepage')
    {

        $pieces = explode(" ", $str);

        if (count($pieces) > $charlimit) {

            $limit = (int)$charlimit;

            $str = (!empty($slug)) ? implode(" ", array_splice($pieces, 0, (int)$charlimit)) . ' ...<a href="' . SITEFRONTURL . 'release/' . $slug . '">Read more</a>' : implode(" ", array_splice($pieces, 0, (int)$charlimit)) . ' ...';
        } elseif ($page == 'homepage') {

            $str = implode(" ", array_splice($pieces, 0, (int)$charlimit)) . ' ...<a href="' . SITEFRONTURL . 'release/' . $slug . '">Read more</a>';
        }

        return $str;
    }



    public function getEmailforClippingReport($getemail = '')
    {

        $email = "";

        $emailFirstPart = explode("@", $getemail);

        $countemailchar = strlen($emailFirstPart[0]);

        $newemailpart = $emailFirstPart[0];

        for ($loop = 0; $loop < $countemailchar; $loop++) {

            if ($loop > 1) {

                if ($loop == ($countemailchar - 1)) {

                    $email .= $newemailpart[$loop];
                } else {

                    $email .= "*";
                }
            } else {

                $email .= $newemailpart[$loop];
            }
        }



        return $email . "@" . $emailFirstPart[1];
    }





    public function getads()
    {

        $obj = ClassRegistry::init('Advertisement');

        $limit = strip_tags(Configure::read('Sidebar.Display.Advertisement'));

        $data = $obj->find('all', array('conditions' => array('Advertisement.status' => 1), 'limit' => $limit, 'order' => 'id DESC'));

        return (!empty($data)) ? $data : "";
    }



    public function getNewsByCompany($cId = '')
    {

        $obj = ClassRegistry::init('PressRelease');

        $conditions = array();

        $obj->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressPoadcast',), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'), 'belongsTo' => array('Plan')));

        $prConditions = array('company_id' => $cId, 'PressRelease.status' => 1, 'PressRelease.release_date <=' => date('Y-m-d'));



        $fields = ['PressRelease.id', 'PressRelease.country_id', 'PressRelease.company_id', 'PressRelease.title', 'PressRelease.slug', 'PressRelease.summary', 'PressRelease.release_date','PressRelease.language', 'Company.name', 'Company.id', 'Company.slug', 'Company.logo_path', 'Company.logo','Company.status'];

        $data_arr =  $obj->find("all", array('conditions' => $prConditions, 'limit' => 3, 'fields' => $fields, 'order' => 'PressRelease.release_date DESC'));

        return $data_arr;
    }



    public function get_users_page_content($action = '')
    {

        $slug = str_replace("_", "-", $action);

        $obj = ClassRegistry::init('StaffUsersPage');

        $data = $obj->find('all', array('conditions' => array('StaffUsersPage.slug' => $slug)));

        return $data[0]['StaffUsersPage'];
    }


    public function summaryPrefix($sourceCity = '', $sourceState = '', $sourceCountry = '', $isSourceManually = '', $date = '')
    {
        $sourceName = '';
        $site_name = strip_tags(Configure::read('Site.name'));
        if (!empty($isSourceManually)) {
            $sourceName = $sourceCity . ', ' . $sourceState . ', ' . $sourceCountry;
        } else {
            $sourceName = (!empty($sourceCity)) ? $sourceCity : $sourceState;
        }
        return '<span class="cntnt-prfx">' . $sourceName . "--(<a style='text-decoration:none;'target='_blank' rel='nofollow' href='" . SITEFRONTURL . "' title='" . $site_name . ".com'>" . $site_name . "</a>)--</span>";
    }


    public function getPressReleaseReseizImage($imageInfo = '',$width = "", $height = "0", $isCropOrResize = "resize")
    {
        $noimageUrl = SITEFRONTURL . "img/ew_no_image.jpg";
        $noImgfileUrl = ROOT.'app'.DS.'webroot'.DS . 'img' . DS . 'ew_no_image.jpg';
        $noImgdest = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb' . DS . str_replace(".jpg", "", 'ew_no_image.jpg') . '-' . $width . 'x' . $height . '.jpg';
        // $imageUrl=$this->cropImage('ew_no_image.jpg',$noimageUrl,$width,$height,$x,$y,'crop','',$noImgdest,$noImgfileUrl);

        $image_alt = '';
        if (!empty($imageInfo)) { 
            $image_path = $imageInfo['image_path'];
            $image_name = $imageInfo['image_name'];
            $image_alt = (isset($imageInfo['image_text'])) ? $imageInfo['image_text'] : "";
            $describe_image = (isset($imageInfo['describe_image'])) ? $imageInfo['describe_image'] : ""; 
            $ext = pathinfo($image_name, PATHINFO_EXTENSION); $ext;
            // $fileUrl = SITEFRONTURL . 'files/company/press_image/thumb/' . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
            $imageUrl = SITEFRONTURL . 'files/company/press_image/' . $image_path . '/' . $image_name;
            $fileUrl = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'press_image' . DS . $image_path . DS . $image_name;
            if (!file_exists($fileUrl)) {
                // $imageUrl=$this->resizeImage('ew_no_image.jpg',$noimageUrl,$width,$height,'resize','',$noImgdest,$noImgfileUrl);
                
                $imageUrl = $this->cropImage($image_name, $noimageUrl, $width, $height, 'crop', '', $noImgdest, $noImgfileUrl);
            } else {
                $dest = ROOT.DS.'app'.DS.'webroot'.DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb' . DS . str_replace("." . $ext, "", $image_name) . '-' . $width . 'x' . $height . '.' . $ext;
              
                if ($isCropOrResize == "resize") {
                    $imageUrl = $imageUrl = $this->resizeImage($image_name, $imageUrl, $width, $height, 'resize', '', $dest, $fileUrl);
                }else if ($isCropOrResize == "crop") {
                    $imageUrl = $this->cropImage($image_name, $imageUrl, $width, $height,'crop', '', $dest, $fileUrl);
                }else if ($isCropOrResize == "resizeandcrop") {
                    $imageUrl = $this->resizeAndCropImage($image_name, $imageUrl, $width, $height, $dest, $fileUrl);
                }
            }
        }else{
            $imageUrl = $this->resizeImage('ew_no_image.jpg', $noimageUrl, $width, $height, 'resize', '', $noImgdest, $noImgfileUrl);
        }
         
        return $imageUrl;
    }


    public function classAccordingToLanguage($language=""){
        $class="englishlt";
        if(!empty($language)&& in_array($language,['ar'])){  // 2 for arablc lass
            $class="arabicrtl";
        }
        return $class;
    } 
}
