<?php   
session_start();
include_once('adminfunction.php');
require('functions.php');
require('uploads.php');
$conn = db_connect();
$msg='';
#Check Department Login or not
if(!checkDepartment($_SESSION['susername'],$_SESSION['spassword']))
{
echo '<script> document.location.href="sign-in.php"; </script>';
exit;
}


$sid = $_SESSION['sid'];
function slugify($text){
$text = preg_replace('~[^\pL\d]+~u', '-', $text);
$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
$text = preg_replace('~[^-\w]+~', '', $text);
$text = trim($text, '-');
$text = preg_replace('~-+~', '-', $text);
$text = strtolower($text);
if (empty($text)) {
return 'n-a';
}
return $text;
}
if(isset($_GET['id']) && is_numeric($_GET['id'])){	
$page_id = mysql_real_escape_string($_GET['id']);
$qry =$conn->prepare("SELECT * FROM tbl_pages WHERE page_id = ? ");
$qry->bind_param("i",$page_id);
$qry->execute();
$res = $qry->get_result();
$count = db_num_rows($res);
$qry->close();
// show records in fields
if($count == 1)
{
$row = db_fetch_array($res);

$page_id = $row['page_id'];
$page_title = $row['page_title'];
$page_description = $row['page_description'];
if (strpos($page_description,'\r') !== false) {
$page_description = str_replace('\r', chr(13), $page_description);
}
$page_image = $row['page_image'];
$page_slug = $row['page_slug'];
$old_page_url  = $row['page_url'];


}
}
else{
$page_title = '';
$page_description = '';
$page_image = '';
$old_page_url = '';
}

$qry_dept =$conn->prepare("SELECT * FROM tbl_departments WHERE dept_id = ? ");
$qry_dept->bind_param("i",$sid);
$qry_dept->execute();
$res_dept = $qry_dept->get_result();
$count_dept = db_num_rows($res_dept);
$qry_dept->close();



if($count_dept == 1)
{
$row_dept = db_fetch_array($res_dept);
$load_folder = $row_dept['dept_upload_folder'];
$upload_folder = ABSPATHH .'/'.$load_folder.'/page/';
$dept_folder = $row_dept['dept_folder'];
$dept_type = $row_dept['dept_type'];

}
$page_title_Err = $page_description_Err = $page_url_Err = "";
$chkimageError = "" ;

if(isset($_POST['page_title']))
{	

function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}
if (empty($_POST["page_title"])) {
$page_title_Err = "page_title is required";
}
else if (!preg_match("/^[\w &.,!?()]+$/",$_POST["page_title"])) {
$page_title_Err = "Only letters,number and white space allowed";
}
else {
$page_title = test_input($_POST["page_title"]);
}

if(empty($_POST["description"]) || $_POST["description"] == "") {
$page_description_Err = "page_description is required";
}
else if (!preg_match("/<(p|span|b|strong|i|u) ?.*>(.*)<\/(p|span|b|strong|i|u)>/",$_POST["description"])) {

$page_description_Err = "Only letters,number and white space allowed";
}
else {
$page_description = test_input($_POST["description"]);
if (strpos($page_description,'\n') !== false) {
$page_description = str_replace('\n', chr(13), $page_description);
}
}

if(empty($_POST["page_url"]) || $_POST["page_url"] == "") {
$page_url_Err = "page_url is required";
}
else if (!preg_match("/^[\w &.,!?()_-]+$/",$_POST["page_url"])) {

$page_url_Err = "Only dot symbol allowed";
}
else {
$page_url = test_input($_POST["page_url"]);
}



$page_title = mysql_real_escape_string(trim($_POST['page_title']));
$page_description = $_POST['description'];
$page_description =rteSafe(	mysql_real_escape_string(trim($_POST['description'])));
if(isset($_GET['id'])){
$page_url  = $row['page_url'];
}else{
$page_url = slugify($_POST['page_url']).'.php';
}

//// image validation function started////////

$filename='';
$fileTmpName = '';
$pstmtclass = new SqlPreparedQuery();
//Validate image/gif
$imgmimetype = array(
            'jpg' => 'image/jpg',
			'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
			'pdf' => 'application/pdf',
        );
try{
	if(isset($_FILES["page_image"]["name"]) && $_FILES["page_image"]["name"] != "")
	{	
     $validextention = $pstmtclass->CheckFileType($_FILES["page_image"]["name"]);
	 $ext = end(explode(".", $_FILES["page_image"]["name"]));
		if ($_FILES['page_image']['error'] === UPLOAD_ERR_OK) {			
			$flag = $pstmtclass->check_file_content($_FILES["page_image"]["tmp_name"]);
			if (!array_search($_FILES["page_image"]["type"],array_values($imgmimetype),true)) {
				$_FILES['page_image']['error'] = 8;
				throw new RuntimeException($_FILES['page_image']['error']);
			}
			else if($validextention == 0) { 
				 throw new RuntimeException(8);
			 }
			else if($flag) { 
				 throw new RuntimeException(7);
			 }else{
				$filename=$_FILES["page_image"]["name"];
				$fileTmpName = $_FILES['page_image']['tmp_name'];	 
			 }
			
		} else {
		throw new RuntimeException($_FILES['page_image']['error']);
		}
	}else if(isset($page_image) &&  $page_image != "" && file_exists($upload_folder.$page_image))
	{
					
			
			$flag = $pstmtclass->check_file_content($upload_folder.$page_image);			
			if($flag) 
			{ 	
				throw new RuntimeException(7);
			}		
		
	}else{
		throw new RuntimeException(4);
	}
}catch (RuntimeException $e) {
    $chkimageError = $pstmtclass->codetotxt($e->getMessage());

}
///////image validation function end////////

if($page_title_Err == "" && $page_description_Err == ""  && $page_url_Err == "" && $filename != ""  && $chkimageError == "" ){
if ($_SESSION['token']!=$_POST['token']) { die("INVALID TOKEN"); }
if(isset($_GET['id']) && is_numeric($_GET['id'])){	
$id =$page_id = mysql_real_escape_string($_GET['id']); 
unlink(ABSPATH .$dept_folder.'/'.$old_page_url); 

$qry =$conn->prepare("UPDATE tbl_pages SET 
page_title= ? ,page_description= ?,
page_image= ?,page_url= ? where page_id = ? ");
$qry->bind_param("ssssi",$page_title,$page_description,$filename,$page_url,$page_id);
$qry->execute();
$qry->close();
}
else{
$id = db_insert_id();
$page_slug = 'page_'.$id;
$qry = $conn->prepare("INSERT INTO tbl_pages (page_title, page_description, page_image,dept_id,page_url) VALUES (?,?,?,?,?)");
$qry->bind_param("sssis", $page_title,$page_description,$filename,$sid,$page_url);
$qry->execute();
$id = $qry->insert_id;
$page_slug = 'page_'.$id;
$qry =$conn->prepare("UPDATE tbl_pages SET 
page_slug= ? where page_id = ? ");
$qry->bind_param("si",$page_slug,$id);
$qry->execute();
$qry->close();
}
if ($id > 0){
   move_uploaded_file($fileTmpName,$upload_folder.$filename);
}	
if(isset($_GET['id'])){

$id = $page_id;
}
else{
//pageCreation
$file_to_make = ABSPATH.$dept_folder.'/'.$page_url;

fopen($file_to_make ,'w') or die('could not open/create file');
$root = $_SERVER['DOCUMENT_ROOT'];
$Handle = fopen($file_to_make, 'w');
$Data = "<?php echo 'Page Under Construction from panel.'; ?> ";

fwrite($Handle, $Data);

fclose($Handle);
//fopen(BASEURL .$dept_folder.'/'.$page_url ,'w') or die('could not open/create file');
//end PageCreation


}

//create_dept_page($sid,$page_id);	
if($dept_type == 2 ){ 

create_dept_page($sid ,$id);
}
else if($dept_type == 3){ 

create_university_page($sid ,$id);
}
else{

create_college_page($sid ,$id);


}
   
/*$res = db_query($qry) or die('Department Login Error: '.db_error());*/
echo '<script language="javascript"> alert("Data successfully entered") </script>'; 
echo '<script> document.location.href="page_list.php"; </script>';
}	

}
?>
<!DOCTYPE html>
<head>
<head>
<meta charset="utf-8">
<meta name="referrer" content="origin">
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<title>Dashboard - Department|Admin</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width">        
<link rel="stylesheet" href="css/templatemo_main.css">
<script src="ckeditor_/ckeditor.js"></script>
<script language="JavaScript" type="text/javascript" src="cbrte/html2xhtml.min.js"></script>
<script language="JavaScript" type="text/javascript" src="cbrte/richtext.min.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
//make sure hidden and iframe values are in sync for all rtes before submitting form
updateRTEs();

return true;
}

//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML, encHTML)
initRTE("cbrte/images/", "cbrte/", "", true);
//-->

function validatepage(){
var strsing = document.getElementById('page_url');
validatex(strsing);
}

</script>
<body>

<?php require('side_menu.php')?>

<div class="templatemo-content-wrapper">
<div class="templatemo-content">
<ol class="breadcrumb">
<li><a href="status.php">Admin Panel</a></li>

<li class="active">Add/Update Page</li>
</ol>
<h1>Add/Update Page</h1>
<p class="margin-bottom-15">Please add/update page form here</p>
<div class="row">
<div class="col-md-12">
<form role="form" id="pageForm" enctype="multipart/form-data" action="#" method="post"  onsubmit="submitForm()">

<div class="row">
<div class="col-md-12 margin-bottom-15">
<?php if(isset($_GET['id'])){ $readonly = 'readonly' ;}else{$readonly = '' ;}?>
<label for="page_url" class="control-label">Page URL*</label>
<input type="text" class="form-control" id="page_url" name="page_url" <?php echo $readonly; ?> <?php if(!isset($_GET['id'])){?>  onblur="checkUrlStatus()" <?php } ?> value="<?php echo $old_page_url; ?>"  >                 
<span class="error" style="color: red;">* <?php echo $page_url_Err;?></span>
</div>
</div>
<div class="row">
<div class="col-md-12 margin-bottom-15">
<label for="lastName" class="control-label">Page Title*</label>
<input type="text" class="form-control" id="lastName" name="page_title" value="<?php echo $page_title; ?>" >                 
<span class="error" style="color: red;">* <?php echo $page_title_Err;?></span>
</div>
</div>

<div class="row">
<div class="col-md-12 margin-bottom-15">
<label for="notes">Page Content </label>
<textarea class="form-control" rows="5" id="descriptions" name="description" ><?php echo $page_description; ?></textarea>
<span class="error" style="color: red;">* <?php echo $page_description_Err;?></span>

</div>
</div>


<div class="row">
<div class="col-md-6 margin-bottom-30">
<label for="exampleInputFile">Upload Image Upload Image (Specified Dimension :400px*250px)</label>
<input type="file" id="page_image" name="page_image">
<p class="help-block">You can upload image here.</p>  
</div> 
<div class="col-md-12">
<label for="image">Your uploaded image for this page</label>
<?php if($page_image == ''){
echo "<p>You haven't uploaded any image for this page</p>";
}
else{
$img = ABSPATH .$load_folder .'/page/'. $page_image;
echo '<p><img src="'.$img.'" width="50" height="50"/></p>';
}?>

<span class="error" style="color: red;">* <?php echo $chkimageError;?></span>
</div>						
</div>
<input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
<div class="row templatemo-form-buttons">
<div class="col-md-12">
<button type="submit" class="btn btn-primary" onClick="return validatepage();">Submit</button>

</div>
</div>
</form>
</div>
</div>
</div>
</div>

<!-- Modal -->
<?php require('footer.php'); ?>
</div>
</div>

</body>
</html>
<script type="text/javascript">
function checkUrlStatus(){

var page_url=$("#page_url").val();
<?php if(isset($_GET['id'])){$page_slug_id = $_GET['id'];}else{$page_slug_id = '0';} ?>
var ps_id = <?php echo $page_slug_id; ?>;
$.ajax({
type:'post',
url:'checkUrl.php?page_url_check='+page_url+'&ps_id='+ps_id,

success:function(msg){  
//alert(msg);

var response = $.trim(msg);
if(response!='')
{
alert(response);
document.getElementById('page_url').value='';
document.getElementById('page_url').focus();
}
// alert("Page Url is already exist in Database. please choose new.");  

}
});

}

</script>
<script type="text/javascript">
window.URL = window.URL || window.webkitURL;

$("form").submit( function( e ) {
var form = this;
e.preventDefault(); //Stop the submit for now
//Replace with your selector to find the file input in your form
var fileInput = $(this).find("input[type=file]")[0],
file = fileInput.files && fileInput.files[0];

if( file ) {
var img = new Image();

img.src = window.URL.createObjectURL( file );

img.onload = function() {
var width = img.naturalWidth,
height = img.naturalHeight;

window.URL.revokeObjectURL( img.src );

if( width <=400 && height <=250 ) {
form.submit();
}
else {
alert("Image Height and Width is greater than specified size.\n Please upload within specified image size.");
}
};
}
else { 
form.submit();
}

});
</script>
<!--<script>
CKEDITOR.replace( 'descriptions' );

</script>-->

<script>
CKEDITOR.replace( 'descriptions' );
</script>