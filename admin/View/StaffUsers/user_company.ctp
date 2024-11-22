<?php 
echo $this->Form->input('Company.id'); 
echo $this->Form->input('Company.staff_user_id',array("type" => 'hidden')); 
echo $this->Form->input('Company.contact_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"div"=>"col-sm-6"));
echo $this->Form->input('Company.job_title', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"div"=>"col-sm-6"));

echo $this->Form->input('Company.organization_type_id',array('options'=>$organizationList,'class'=>'form-control select2',"div"=>"col-sm-6"));
echo $this->Form->input('Company.name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"div"=>"col-sm-6",'label'=>"Company name"));
echo $this->Form->input('Company.phone_number', array("type" => 'text','maxlength'=>"15",'class'=>'form-control validate[required, custom[phone],maxSize[15],minSize[10]]',"div"=>"col-sm-6",));
echo $this->Form->input('Company.fax_number', array("type" => 'text','maxlength'=>"50","div"=>"col-sm-6"));

echo $this->Form->input('Company.address', array("type" => 'text','maxlength'=>"255",'class'=>'form-control validate[required]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.city', array("type" => 'text','maxlength'=>"100",'class'=>'form-control validate[required]',"div"=>"col-sm-6"));

echo $this->Form->input('Company.state', array("type" => 'text','maxlength'=>"100",'class'=>'form-control validate[required]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.country_id', array('empty' => '-Select-', "options" => $country_list,'class'=>'form-control validate[required]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.zip_code', array("type" => 'text','maxlength'=>"6",'class'=>'form-control validate[required]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.web_site', array("type" => 'text','class'=>'form-control validate[required,custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.blog_url',array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.linkedin', array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.twitter_link', array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.fb_link', array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.pinterest', array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.instagram', array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.tumblr', array("type" => 'text','class'=>'form-control validate[custom[url]]',"div"=>"col-sm-12"));

echo $this->Form->input('Company.description', array("type" => 'textarea','class'=>'form-control validate[required]',"div"=>"col-sm-6"));
echo $this->Form->input('Company.hear_about_us', array("type" => 'textarea','class'=>'form-control validate[required]',"div"=>"col-sm-6")); 

echo "<div class='col-sm-6'>";
echo $this->Form->hidden('Company.logo');
echo $this->Form->hidden('Company.logo_path');
echo $this->Form->input('Company.newlogo', array("type" => 'file','class'=>'form-control','id'=>'newlogo','accept'=>'image/*','onchange'=>"imagevalidation('newlogo',80,80,'both_less_greater',100,100)"));
echo ' <label style="display: none;" id="newlogo-error"></label>';

echo $this->Html->image(FRONTURL.'files/company/logo/'.$this->data['Company']['logo_path'].'/'.$this->data['Company']['logo'],array('width'=>'200px;')); 
echo "</div>";
echo "<div class='col-sm-6'>";
echo $this->Form->hidden('Company.banner_image');
echo $this->Form->hidden('Company.banner_path');
echo $this->Form->input('Company.newbanner_image', array("type" => 'file','class'=>'form-control','id'=>"newbanner_image",'accept'=>'image/*','onchange'=>"imagevalidation('newbanner_image',300,300,'both_less_greater',500,500)"));
echo ' <label style="display: none;" id="newbanner_image-error"></label>';
echo $this->Html->image(FRONTURL.'files/company/banner/'.$this->data['Company']['banner_path'].'/'.$this->data['Company']['banner_image'],array('width'=>'200px;')); 
echo "</div>";
?>

