<?php
$servername = "localhost";
$username = "id13390975_admin";
$password = "#y!z}5Rm@7VP{D/G";
$dbname = "id13390975_website";
error_reporting(E_ERROR | E_WARNING | E_PARSE |E_NOTICE);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
	$ip=$_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["Http_X_FOWARDED_FOR"])) {
	$ip=$_SERVER["Http_X_FOWARDED_FOR"];
}
else
{
	$ip=$_SERVER["REMOTE_ADDR"];
}
$sql= "SELECT * From login_user";
$result = $conn->query($sql);	
$limit =mysqli_num_rows($result);
$sql="SELECT * from login_user";
$result = $conn->query($sql);	
for ($a=0; $a<$limit; $a++) { 
	$row=$result->fetch_assoc();
	if ($ip!=$row["ip_adr"]) {
	$sql="INSERT INTO login_user(ip_adr) VALUES ('".$ip."')";
	mysqli_query($conn,$sql);
	}
}	
if($limit==0)
{
	$sql="INSERT INTO login_user(ip_adr) VALUES ('".$ip."')";
	mysqli_query($conn,$sql);
}
$content=$_GET["content_id"]??"home";
$cat=$_GET["cat_name_id"]??"none";
$sign=$_GET["sign_out"]??"0";
$created_acccount=0;
$account_exist=0;
$login_success=0;
$user_email_array=array();
$user_password_array=array();
if ($sign==1) {
	$sql="UPDATE login_user SET email=' ',login=0 WHERE ip_adr='$ip'";
	mysqli_query($conn,$sql);
}
echo '<!DOCTYPE html>
<html>
<head>
	<title>Wallpaper</title>
</head>
<style>';
include 'index.css';
include 'create_account.css';
include 'profile.css';
include 'contact.css';
include 'upload.css';
include 'signin.css';
include 'update_profile.css';
include 'help.css';
include 'aboutus.css';
include 'community_rules.css';
include 'privacy.css';
include 'categrious_page.css';
include 'recover.css';
echo'</style>
<body id="main_body">
	<header id="main_header">
		<div id="logo">
			<a href="index.php?content_id=home">
				<img src="logo.png" height="100px" width="100px">
			</a>
		</div>
		<div id="search">
		<form action="index.php?content_id=Search_result" method="POST">
			  <div class="autocomplete" style="width:300px;">
			    <input id="myInput" type="text" name="search_text" value="">
			  </div>
			  <a href="index.php?content_id=Search_result"><button type="submit" name="search" id="search_button">Search</button></a>
			</form>

		</div>

		<div id="menu" onclick="displaymenu()">
			<div id="menu_icon">
				<div id="bar1">
					
				</div>
				<div id="bar2">
					
				</div>
				<div id="bar3">
					
				</div>
			</div>
		</div>
	</header>
	<div id="display_image">
		<div id="display_image_body">';
		if ($content=='Search_result') {
			if (isset($_POST["search"])) {
			$search_txt=$_POST["search_text"];
			$sql= "SELECT * From picture where cat_name LIKE '%$search_txt%'";
			$result = $conn->query($sql);	
			$limit =mysqli_num_rows($result);
			$result = $conn->query($sql);
			for ($i=0; $i<$limit;$i++) { 
				$row = $result->fetch_assoc();
				$picture_name=$row["picture_name"];
				echo '<div id="outsidebox">
							<div id="insidebox">
								 <img src="'.$picture_name.'" id="imagesize" onclick=display("'.$picture_name.'")>
							</div>
						</div>
						';
			}
			if ($limit==0) {
				echo '<div id="outsidebox" style="margin-left:35%;margin-top:3%">					
								 <img src="no_image.png" id="imagesize" >
						</div>
						';
			}
			}
		}
	if ($content=='home') 
	{
		$sql= "SELECT * From main_cover";
		$result = $conn->query($sql);	
		$limit =mysqli_num_rows($result);
		$sql = "SELECT main_cover_id, main_cover_name, main_cover_title FROM main_cover";
		$result = $conn->query($sql);
		for ($a=0;$a<$limit;$a++) 
		{
			$row = $result->fetch_assoc();
			echo '<div id="outsidebox">
							<div id="insidebox">
								<a href="index.php?content_id=categrious_page&cat_name_id='.$row["main_cover_title"].'">
									<img src="'.$row["main_cover_name"].'" id="imagesize">
									<div id="cattext">
										'.$row["main_cover_title"].'
									</div>
								</a>
							</div>
						</div>';
					}
		}
		if ($content=='create_account') 
		{

			$sql="SELECT login from login_user where ip_adr='$ip'";
			$result = $conn->query($sql);
			$row=$result->fetch_assoc();
			if ($row['login']==0) {
					echo '<div id="create_account_box">
					  		<div class="form">';
					  			if(isset($_POST['submit'])){
									$username=$_POST["create_name"];
									$email=$_POST["create_email"];
									$password=md5($_POST["create_password"]);
									$image = $_FILES['create_image']['name'];
									$imgTmp_dir = $_FILES['create_image']['tmp_name'];
									$imgSize = $_FILES['create_image']['size'];
									move_uploaded_file($imgTmp_dir, "" . $image);
                			        
									$sql="SELECT * from User";
									$result = $conn->query($sql);	
									$limit =mysqli_num_rows($result);
									for ($i=0; $i<$limit; $i++) { 
										$row=$result->fetch_assoc();
										$check_email=$row["email"];
										if ($email==$check_email) {
											echo'<div class="succesfully">Account Already Exist Go to <a href="index.php?content_id=sign_in_page">Sign in</a></div>';
										}
										else
										{
											if ($imgSize>0) {
											$sql="INSERT INTO User( username, email,password, user_image_name) VALUES ('".$username."','".$email."','".$password."','".$image."')";
											}
											else
											{
											$sql="INSERT INTO User( username, email,password) VALUES ('".$username."','".$email."','".$password."')";
											}
											if(mysqli_query($conn,$sql))
											{
												echo'<div class="succesfully">Account Cretaed Succesfully Go to <a href="index.php?content_id=sign_in_page">Sign in</a></div>';
											}
                                            else 
                                            {
             echo'<div class="succesfully">Not done</div>';                               }
										}
									}
								}
					  			echo'<form class="login-form" action="" method="POST" enctype="multipart/form-data">
							      <input type="text" placeholder="Name" name="create_name"  value="" required/>
							      <input type="email" placeholder="E-mail" name="create_email"  value="" required/>
							      <input type="password" placeholder="Password" name="create_password"  value="" required/>
							      <label for="profile_photo_file">Select Profile photo</label>
							      <input type="file" accept="image/*" id="profile_photo_file" onchange="preview_image(event)" name="create_image" value=""><br>
							      <img id="output_image">
	
						      <input type="submit" name="submit" value="create" id="create_btn"></input>
						      <p class="forgot">Do you have an account already?<br><a href="index.php?content_id=sign_in_page">Sign In</a></p>
    						</form>
						 </div>
				</div>';
			}
			else{
				echo '<div class="already_login_box">
						<div id="question_main_head">Already Login</div><br>
						You are already Sign in to website do want to sign out from the website<br><br>
						<a href="index.php?content_id=home&sign_out=1">SIGN OUT</a><br><br>
						Do You want to go to Profile Page<br><br>
						<a href="index.php?content_id=profile_page">PROFILE</a>
					</div>';
			}
		}
		if ($content=='profile_page') {
			$sql="SELECT email,login from login_user WHERE ip_adr='$ip'";
			$result = $conn->query($sql);	
			$row=$result->fetch_assoc();
			$user_email=$row["email"];						
			if ($row["login"]==1) {
			$sql="SELECT * from user WHERE email='$user_email'";
			$result = $conn->query($sql);	
			$row=$result->fetch_assoc();
			echo '<div class="profile_box"> 
				<span class="profile_box_profile">PROFILE</span>
				<div id="user_image_box">';
				$user_image=$row["user_image_name"];
				if ($user_image=='') {
					echo '<img src="logo.png" height="100px" width="100px">'; 
				}
				else
				{
					echo '<img src="'.$user_image.'" height="100px" width="100px">'; 
				}
				echo'</div><br>
				<span class="profile_box_name">
					'.$row["username"].'
				</span><br>
				<span class="profile_box_gallery_head">
					YOUR WALLPAPER	
				</span><br><br>
				<div class="profile_box_user_image">';
				// <!-- 
				// 	if no images then display no images has been uploaded
				// 	<div id="profile_box_user_no_image">No Images Uploaded<br>&nbsp&nbsp&nbsp&nbspUpload Images</div> -->
				$sql="SELECT * FROM picture WHERE email='$user_email'";
				$result = $conn->query($sql);	
				$limit =mysqli_num_rows($result);
				if ($limit==0) {
					echo '<div id="profile_box_user_no_image">No Images Uploaded Upload Images</div>';
				}		
				if ($limit>0) {
						for($a=0;$limit>$a;$a++)
						{
							$row=$result->fetch_assoc();	
							$image_name=$row["picture_name"];
							echo'<img src="'.$image_name.'" height="100px" width="100px">';
						}
				}	
			echo'</div> 
				<div class="profile_box_button">
					<a href="index.php?content_id=upload_page"><button>Upload</button></a>
				</div>
				<br>
				<a href="index.php?content_id=edit_page"><button id="profile_box_edit">Edit Profile </button><br></a>
				<a href="index.php?content_id=home&sign_out=1"><button id="profile_box_signout">Sign Out</button><br></a>
				<div id="profile_box_issue">
				If you are having issue with your account then &nbsp<a href="contact.html">contact us</a>.
				</div>
			</div>';
			}
			else
			{
			$content="sign_in_page";	
			}
		}

		if ($content=='contact_page') {
			echo '<div class="form">
			    		<form class="login-form" method="POST" action="">
			    		';
			    		if (isset($_POST["send_msg"])) {
			    			$msg_name=$_POST["msg_nam"];
			    			$main_msg=$_POST["main"];
			    			$email_msg=$_POST["msg_email"];
			    			$sub=$msg_name."=".$email_msg;
			    			$msg_mail=mail("gshah779@gmail.com",$sub,$main_msg);
							if ($msg_mail) {
								echo'<div class="succesfully">Message Sent Succesfully.</div>';			
							}else
							{
								echo'<div class="succesfully">Some Error In Sending Message Try Again</div>';		
							}
			    		}
					    echo'<input type="text" placeholder="Name" name="msg_nam" required/>
					      <input type="email" placeholder="E-mail" name="msg_email" required/>
						  <textarea placeholder="Message" name="main" required></textarea>    
					      <button type="submit" name="send_msg">Send</button>
					    </form>
			</div>';
		}
		if ($content=='upload_page') {
			$sql= "SELECT * From login_user";
			$result = $conn->query($sql);	
			$limit =mysqli_num_rows($result);
			$sql="SELECT email,login from login_user WHERE ip_adr='$ip'";
			$result = $conn->query($sql);	
			$row=$result->fetch_assoc();
			$user_email=$row["email"];	

			if ($row["login"]==1) {
				if (isset($_POST["submit"])) {
					$tags=$_POST["upload_tag"];
					$low=strtolower($tags);
					$split_str=explode(",",$low);
					$image = $_FILES['upload_image']['name'];
					$imgTmp_dir = $_FILES['upload_image']['tmp_name'];
					$imgSize = $_FILES['upload_image']['size'];
					$imgerror = $_FILES['upload_image']['error'];
					move_uploaded_file($imgTmp_dir, "" . $image);
					$sql="SELECT * FROM categrious_list";
					$result = $conn->query($sql);	
					$limit=mysqli_num_rows($result);
					if ($imgerror>1) {
						
					}
					else
					{
						for ($i=0; $i <$limit; $i++) { 
							$row=$result->fetch_assoc();
							$titles=$row["cat_names"];
							$low_titles=strtolower($titles);	
							if (in_array($low_titles, $split_str)) {
								$sql="INSERT INTO picture(picture_name,cat_name,email) VALUES ('".$image."','".$low_titles."','".$user_email."')";
								mysqli_query($conn,$sql);
								$sql="UPDATE login_user SET matches=1 WHERE email='$user_email'";
								mysqli_query($conn,$sql);
								$upload_done=1;
							}
						}
					}	
					$sql="SELECT * From login_user WHERE email='$user_email'";
					$result=$conn->query($sql);
					$row=$result->fetch_assoc();
					if ($row["matches"]==0) {
						$sql="INSERT INTO picture(picture_name,cat_name,email) VALUES ('".$image."','others','".$user_email."')";
						mysqli_query($conn,$sql);	
						$upload_done=1;
					}
					$sql="UPDATE login_user SET matches=0 WHERE email='$user_email'";
					mysqli_query($conn,$sql);
				}
			echo '<div id="update_account_box">
			  		<div class="upload_form">
			    		<form class="login-form" method="POST" action="" enctype="multipart/form-data">';
			    		if ($imgerror>1) {
					  				echo'<div class="succesfully">There is some error in the Image</div>';
			    		}
			    		if ($upload_done==1) {
					  				echo'<div class="succesfully">Upload Done Succesfully.</div>';
			    		}
				    	  echo'<label for="profile_photo_file">Select photo</label>
					      <input type="file" accept="image/*" id="profile_photo_file" name="upload_image" onchange="preview_image(event)" value="" required>
					      <img id="output_image"/>
					      <input type="text" placeholder="Tags , separated by comma" name="upload_tag" value="" required>
					      <button type="submit" name="submit">Upload</button>
    					</form>
					    <a href="index.php?content_id=home" style="text-decoration: none;
  color: #fff;"><button>Cancel</button></a>
					 </div>
			</div>';
			}
			else
			{
				$content='sign_in_page';	
			}
		}
		if ($content=='sign_in_page') {
			$sql="SELECT login from login_user where ip_adr='$ip'";
			$result = $conn->query($sql);
			$row=$result->fetch_assoc();
			if ($row['login']==0) {
			echo '<div id="Sign_In">
			  		<div class="sign-form">
			    		<form class="signin-form" action="" method="POST">';
				if (isset($_POST['submit']))
				{
						$check_email=$_POST['go_email'];
						$sql="SELECT * FROM User WHERE email='$check_email'";
						$result = $conn->query($sql);	
						$limit =mysqli_num_rows($result);
						$row=$result->fetch_assoc();
						$check_password=$row["password"];
						$check_name=$row["email"];
						if ($limit==1) {
							if ($check_password==$_POST["password"]) {
								$sql="UPDATE login_user SET email='$check_name',login=1 WHERE ip_adr='$ip'";
								mysqli_query($conn,$sql);
								echo '<div class="succesfully">Login Done Succesfully.</div>';
							}else{
								echo '<div class="succesfully">Password is Wrong</div>';
							}
						}else{
							echo'<div class="succesfully">Account Doesnot Exist Go to <a href="index.php?content_id=create_account">Create Account</a></div>';
						}

				}

			    		echo'
					      <input type="email" placeholder="E-mail" name="go_email" value="" required/>
					      <input type="password" placeholder="Password" name="password" value="" required/>
					      <input type="submit" value="SIGN IN" name="submit" id="go_btn">
					      <div id="wrong_password">Wrong password!! Or Profile does not exist</div>
					      <p class="forgot">Forgot your password?<br><a href="index.php?content_id=recover_page">Recover your password</a></p>
					      <p class="message">Don&#39t Have an account yet?<br><a href="index.php?content_id=create_account">Create an account</a></p>
			    		</form>
					</div>
			</div>';
			}
			else
			{
				echo '<div class="already_login_box">
						<div id="question_main_head">Already Login</div><br>
						You are already Sign in to website do want to sign out from the website<br><br>
						<a href="index.php?content_id=home&sign_out=1">SIGN OUT</a><br><br>
						Do You want to go to Profile Page<br><br>
						<a href="index.php?content_id=profile_page">PROFILE</a>
					</div>';
			}
		}
		if ($content=='edit_page') {
			$sql="SELECT email,login from login_user WHERE ip_adr='$ip'";
			$result = $conn->query($sql);	
			$row=$result->fetch_assoc();
			$user_email=$row["email"];						
			if ($row["login"]==1) {
				$sql="SELECT * from user WHERE email='$user_email'";
				$result = $conn->query($sql);	
				$row=$result->fetch_assoc();
				if (isset($_POST['submit'])) {
				$new_name=$_POST["edit_name"];
				$image = $_FILES['edit_image']['name'];
				$imgTmp_dir = $_FILES['edit_image']['tmp_name'];
				$imgSize = $_FILES['edit_image']['size'];
				move_uploaded_file($imgTmp_dir, "" . $image);
				if ($imgSize>0) {
					$sql="UPDATE user SET user_image_name='$image' WHERE email='$user_email'";
					$change=1;
				}
				if (strlen($new_name)>0) {
					$sql="UPDATE user SET username='$new_name' WHERE email='$user_email'";		
					$change=1;
				}
				mysqli_query($conn,$sql);
				}
			
			echo '<div id="update_account_box">
			  		<div class="update_form">';
			  			if ($change==1) {
					    	echo'<div class="succesfully">Changes done succesfully go to <a href="index.php?content_id=profile_page">Profile page</a></div>';
			  			}
			    		echo'<form class="login-form" action="" method="POST" enctype="multipart/form-data">
			    		   		 <div id="user_image_box">';
			    		  		$user_image=$row["user_image_name"];
								if ($user_image=='') {
									echo '<img src="logo.png" height="100px" width="100px">'; 
								}
								else
								{
									echo '<img src="'.$user_image.'" height="100px" width="100px">'; 
								}
						  echo'</div><br>
						  <span class="update_box_name">
						  	'.$row["username"].'
						  </span><br><br><br>
						  <input type="text" placeholder="New Username" name="edit_name" value="">
					      <label for="profile_photo_file">New Profile photo</label>
					      <input type="file" accept="image/*" id="profile_photo_file" onchange="preview_image(event)" name="edit_image" value="">
					      <img id="output_image"/>
					      <a href="index.php?content_id=profile_page" style="text-decoration: none;
  color: #fff;"><button type="submit" name="submit">Update</button></a>
    					</form>
					    <a href="index.php?content_id=profile_page" style="text-decoration: none;
  color: #fff;"><button>Cancel</button></a>
					 </div>
			</div>';
			}
		}
		if ($content=='help_page') {
			echo '<div class="help_box">
				<div id="question_main">How to download a wallpaper</div><br>
				You can download wallpapers free of charge and you don&#39t need to sign up. First you need to find the wallpaper from the album page. On the left side of the image you&#39ll see several buttons and icons, you can <span id="question_main_line">download the wallpaper by clicking the arrow button:</span>
				<div id="image_box">
					<img src="walpaper10.jpg" height="100%" width="100%"> 
				</div>
				Your download will begin immediately and the image file will be saved in your hard drive. In most cases it will be saved on your Downloads folder, but it may be on a different location, depending on your personal settings.<br><br>
				<div id="question_main">How to change your wallpaper on Windows 10</div><br>
				So you downloaded a nice wallpaper and now you want to use, right? A new wallpaper can make a computer feel more like home and it&#39s easy to change it on Windows 10. Here’s how:<br><br>

				Log on to your Windows 10 computer using your desired account and, on the desktop, right-click anywhere. From the contextual menu, click Personalize.
				<div id="image_box">
					<img src="help_image1.jpg" height="100%" width="100%"> 
				</div>
				In the Desktop Background window, click on "Browse..." from the Picture location list of options:
				<div id="image_box">
					<img src="help_image2.jpg" height="100%" width="100%"> 
				</div>
				Now you just need to select the image file you downloaded from our site and click on "Save changes".<br><br>

				<div id="question_main">How to use a wallpaper on MacOSX</div><br>
				In Desktop & Screen Saver preferences, you can change the picture that’s displayed on your desktop. Here is how:
				<div id="image_box">
					<img src="help_image3.jpg" height="100%" width="100%"> 
				</div>
				Choose Apple menu > System Preferences, click Desktop & Screen Saver, then click Desktop. Now, to find the picture you want to use, click "+", navigate to and select the folder, then click Choose.
				<div id="image_box">
					<img src="help_image4.jpg" height="100%" width="100%"> 
				</div>
				All your pictures in the folder will load on the right side. Click the picture you want to use as wallpaper and you are done.
			</div>
		</div>';
		}
		if ($content=='about_us_page') {
			echo '<div class="about_us_box">
				This website is the largest collection of high quality wallpapers that you can find online. It&#39s easy, convenient and fun to use. Everyday we sort through thousands of images to find the very best wallpapers we can bring to you.<span id="question_main_line"> Wallpapers for every taste, occasion and device.</span>
				<div id="image_box">
					<img src="about_us_image1.jpg" height="100%" width="100%"> 
				</div><br>
				<span id="question_main_line"> For all your devices</span><br><br>
				If you like to make all your devices feel familiar, don&#39t worry! Our wallpapers are suitable for devices of all sizes, from your desktop computer to your tablet, phone and even your smart watch!<br><br>
				<span id="question_main_line">Drop us a line!</span><br><br>
We want to keep this site useful and fun for everybody, so if there is anything that comes up in your mind about how we could improve it, please <a href="">contact us</a>. We&#39d love to hear from you.

			</div>';
		}
		if ($content=='community_rule_page') {
			echo '<div class="community_rules_box">
				<span id="question_main">Contributing new wallpapers</span><br><br>
				Share your work and upload your favorites files to Website_name<br><br>

				please read our upload rules. To keep our content safe, respectful and high quality, we ask you to follow these rules:<br>
					<ul>
						<li>No nudity or suggestive images.</li>
						<li>No offensive images.</li>
						<li>No disrespectful, hurtful, or provoking images.</li>
						<li>Only high-resolution images. No stretched or rotated images.</li>
						<li>Respect copyright. Attribute the author wherever possible Do not remove artist signatures or any watermarks.</li>
						<li>No private or personal photos allowed.</li>
						<li>Please add a descriptive image caption and tag your content accurately.</li>
					</ul>
				Every uploaded wallpaper will be manually reviewed (usually in less than a day) and your images will not be shown publicly until the review is done.<br><br>

				Please remember that Website_name is a family friendly website and for that reason we don&#39t allow nude images, regardless of artistic merit.<br><br>

				<span id="question_main">Bannned users</span><br><br>
				If we find that your uploads break our rules more than 3 times, we will ban your account and you won&#39t be allowed to upload images again. If you want to request the removal of a ban, please contact us and we&#39ll study your case.
			</div>';
		}
		if ($content=='privacy_policy_page') {
			echo '<div id="privacy_policy_box">
				The owners and operators of WallpaperCave.com take your online privacy seriously. This document outlines the types of information collected by our servers and provides links to the privacy policies of our third-party advertising partners.<br><br>
			  	<span id="question_main">Contributing new wallpapers</span><br><br>
			  	Membership is an optional part of the Website_name web site. No user account is required in order to browse or download the content we provide. However, creating an account enables extra features such as (but not limited to) the ability to submit content.<br><br>

				By creating an account, you are volunteering certain information about yourself that will be stored on our servers. This data includes at a minimum a username, password, and valid email address. Your user profile information is public and may appear on our web site, with the exception of your email address and password.<br><br>
				
				Our commenting feature allows non-registered users to post comments. In these cases we display the provided name associated with the comment. It is up to you to ensure that your username /email address is anonymous.<br><br><br>
					
				<span id="question_main">Personally Identified Data</span><br><br>
				We strongly discourage users from entering their full name, phone number, physical address, or other sensitive information in user profiles, comments, or other areas of the site. Furthermore, we request that minors and any individuals with limited decision making ability not create an account on this site without the approval and supervision of a guardian.<br><br>

				We make a reasonable attempt to monitor for cases where users post personally identifiable information about other individuals to public portions of the web site and remove the offending material.<br><br><br>
				<span id="question_main">Server Logs</span><br><br>

				Like most web sites, Website_name logs web, database, and other server-software usage and access information. This information may include your internet protocol (IP) address, which in many cases can be translated to an affiliation (such as your work, school, or internet service provider), or a geographical location. We only use this information for debugging purposes and for aggregating into anonymous usage and traffic statistics.<br><br><br>

				<span id="question_main">Sharing/Selling of Data</span><br><br>
				Website_name does NOT share or sell personally identifiable data to third parties such as direct marketers. We respect your privacy.<br><br><br>
				<span id="question_main">Data Retention</span><br><br>
				The data submitted to and generated by this web site may be copied to additional machines for redundancy and backup purposes.<br><br><br>
			</div>';
		}
		if ($content=='categrious_page') {
			
			echo'<div id="Realated_text">
				Realated Categrious:';
				$sql = "SELECT related_id, related_name, cat_name FROM related_categrious where cat_name='$cat'";
				$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
					$name=$row["related_name"];	
			    	echo '<a href="index.php?content_id=categrious_page&cat_name_id='.$name.'">'.$name.'</a>';
			    }
			}
			else
			{
				echo '<a href="#">Not Found</a>';
			}
				echo'</div>';
			$sql= "SELECT * From picture where cat_name='$cat'";
			$result = $conn->query($sql);	
			$limit =mysqli_num_rows($result);
			$result = $conn->query($sql);
			for ($i=0; $i<$limit;$i++) { 
				$row = $result->fetch_assoc();
				$picture_name=$row["picture_name"];
				echo '<div id="outsidebox">
							<div id="insidebox">
								 <img src="'.$picture_name.'" id="imagesize" onclick=display("'.$picture_name.'")>
							</div>
						</div>
						';
			}
			if ($limit==0) {
				echo '<div id="outsidebox" style="margin-left:35%;margin-top:3%">					
								 <img src="no_image.png" id="imagesize" >
						</div>
						';
			}
		}
		if ($content=='recover_page') {
			echo '<div class="recover-form">';
			if (isset($_POST["send_otp"])) {
				$otp_email=$_POST['otp_email'];
				$sql="SELECT * FROM User WHERE email='$otp_email'";
				$result = $conn->query($sql);	
				$limit =mysqli_num_rows($result);
				if ($limit==0) {
					echo'<div class="succesfully">Account Doesnot Exist Go to <a href="index.php?content_id=create_account">Create Account</a></div>';
				}
				else
				{
						$otp_number=rand(1000,9999);
						$sql="UPDATE login_user SET otp_no='$otp_number',otp_email='$otp_email' WHERE ip_adr='$ip'";
						mysqli_query($conn,$sql);
						$msg="OTP For Recovering Account.";
						$mail = new PHPMailer;
						$mail->isSMTP(); 
						$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
						$mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
						$mail->Port = 587; // TLS only
						$mail->SMTPSecure = 'tls'; // ssl is deprecated
						$mail->SMTPAuth = true;
						$mail->Username = 'gautamshah2904@gmail.com'; // email
						$mail->Password = 'gautam779'; // password
						$mail->setFrom('gautamshah2904@gmail.com', 'gautam'); // From email and name
						$mail->addAddress($otp_email,''); // to email and name
						$mail->Subject = 'OTP FOR RECOVERING ACCOUNT';
						$mail->msgHTML($otp_number); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
						$mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
						// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
						$mail->SMTPOptions = array(
						                    'ssl' => array(
						                        'verify_peer' => false,
						                        'verify_peer_name' => false,
						                        'allow_self_signed' => true
						                    )
						                );

						if ($mail->send()) {
						echo'<div class="succesfully">OTP Send Succesfully.</div>';			
						echo'<form class="login-form" method="POST" action="">
					      		<input type="text" placeholder="OTP(One Time Password)" name="otp_user" value="" required/>
					      		<button type="submit" name="check_otp">Continue</button>
					    	</form>';
							
						}
						else
						{
							echo'<div class="succesfully">OTP can not send please try again</div>';		
						}
				}						
			}
			if (isset($_POST["new_pwd_set"])) {
				$new_psd=$_POST["new_pwd"];
				$sql="SELECT * from login_user WHERE ip_adr='$ip'";
				$result=$conn->query($sql);
				$row=$result->fetch_assoc();
				$change_email=$row["otp_email"];
				$sql="UPDATE user set password='$new_psd' where email='$change_email'";
				if (mysqli_query($conn,$sql)) {
					echo'<div class="succesfully">New password change succesfully <a href="index.php?content_id=sign_in_page">Sign In</a></div>';				
				}
				else
				{
					echo'<div class="succesfully">Some Error in changing password</div>';				
				}
			}
			 if ($email->send()) {
			 	
			 }
			 else
			 {echo'<form id="login-form" method="POST" action="" id="otpmail">
					      <input type="email" placeholder="E-mail" name="otp_email" value="" required/>
					      <button type="submit" name="send_otp">Send OTP</button>
					    </form>';
			}
			if (isset($_POST["check_otp"])) {
				$otp_no_user=$_POST['otp_user'];
				$sql="SELECT otp_no FROM login_user WHERE ip_adr='$ip'";
				$result=$conn->query($sql);
				$row=$result->fetch_assoc();
				$real_otp=$row["otp_no"];
				if ($otp_no_user==$real_otp) {
						echo'<div id="new_password">
					 	  	<form action="" method="POST">
					 	  		<input type="text" placeholder="New Password" name="new_pwd" required/>
					      		<button type="submit" name="new_pwd_set">Confirm</button>
					 	 	</form>
					 	  </div>'; 	
					
				}
				else
				{
					echo'<div class="succesfully">Wrong OTP!!</div>';				
				}
			}
			echo'</div>';
		}
		echo'
		</div>
		<div id="popup_image_box">
			<div id="dis2" onclick="cross()" >
				<div id="stick1"></div>
			</div>
			<div id="align_image">
				<div id="image_box">
					<div id="main_image_box">
						<img src="about_us_image1.jpg" height="100%" id="display_image_part"> 
					</div>
					<a href="about_us_image1.jpg" download id="img_link"><button id="download">Download</button></a>
					<a href="about_us_image1.jpg" target="_blank" id="img_link2"><button id="view">View</button></a>
					<a href="index.php?content_id=contact_page"><button id="report">Report</button></a>
				</div>
			</div>
		</div>';
		$sql="SELECT email,login from login_user WHERE ip_adr='$ip'";
		$result = $conn->query($sql);	
		$row=$result->fetch_assoc();
		$user_email=$row["email"];						
		$login_detail=$row["login"];	
	echo'<div id="on_menu_click">
				<div id="profile">
					<div id="user_detail">
						<div id="user_image">
							<a href="index.php?content_id=profile_page">';
								if ($login_detail==1) {
									$sql="SELECT user_image_name FROM user WHERE email='$user_email'";
									$result=$conn->query($sql);
									$row=$result->fetch_assoc();
									$user_image=$row["user_image_name"];
									if ($user_image!='') {
									echo'<img src="'.$user_image.'" height="100px" width="100px" style="border-radius:200%">';		
									}
									else{
									echo'<img src="logo.png" height="100px" width="100px" style="border-radius:200%">';
									}
								}else{
									echo'<img src="logo.png" height="100px" width="100px" style="border-radius:200%">';		
								}
							echo'</a>
						</div>
					</div>
					<div id="account">
						<a href="index.php?content_id=profile_page">
							Account
						</a>
					</div>		
					<div id="upload">
						<a href="index.php?content_id=upload_page">
							Upload
						</a>
					</div>
					<div id="create_account">
						<a href="index.php?content_id=create_account">
							Create Account
						</a>
					</div>
					<div id="sign_in_or_out">
						<a href="index.php?content_id=sign_in_page">
							Sign In
						</a>
					</div>
					<div id="help">
						<a href="index.php?content_id=help_page">
							Help
						</a>
					</div>
					<div id="contact_us">
						<a href="index.php?content_id=contact_page">
							Contact us
						</a>
					</div>
					<div id="about_us">
						<a href="index.php?content_id=about_us_page">
							About us
						</a>
					</div>
			</div>
		</div>

		<footer>
			<div id="detail">
				<div id="website_detail">
					<img src="logo.png" height="150px" width="150px">	
				</div>
				<div id="website_description">
				</div>
				<div id="detail_link1">
					<a href="index.php?content_id=profile_page">Account</a><br><br>
				</div>		
				<div id="detail_link2">
					<a href="index.php?content_id=about_us_page">About us</a><br><br>
				</div>
				<div id="detail_link3">
					<a href="index.php?content_id=community_rule_page">Community Rules</a><br><br>
				</div>
				<div id="detail_link4">
					<a href="index.php?content_id=privacy_policy_page">Privacy Policy</a><br><br>
				</div>
				<div id="detail_link5">
					<a href="index.php?content_id=contact_page">Contact</a><br><br>
				</div>
				<div id="detail_link6">
					<a href="index.php?content_id=help_page">Help & FAQs</a><br><br>	
				</div>
				<div id="detail_link7">
					<a href="index.php?content_id=contact_page">Report & Feedback</a><br><br>	
				</div>
				<div id="website_rights">
					Copyright &copy website_name 2020. All Rights Reserved.	
				</div>
			</div>
		</footer>
	</div>
	<script type="text/javascript">
			click=0;
		function remove_body()
		{
			document.getElementById("display_image_body").style.display="none";
		}
		function text_display()
		{
			document.getElementById("profile").style.visibility="visible";
		}	
		function displaymenu() {
			$bar2=document.getElementById("bar2");
			$bar1=document.getElementById("bar1");
			$bar3=document.getElementById("bar3");
			if (click==0) {
			document.getElementById("on_menu_click").style.width="25.5%";
			//document.getElementById("menu").style.display="none";
			$bar2.style.width="0%";
			$bar2.style.marginLeft="45%";
			$bar1.style.transform="rotate(43deg)";
			$bar1.style.marginTop="30%";
			$bar1.style.marginLeft="-25%";
			$bar1.style.width="160%";
			$bar3.style.transform="rotate(-45deg)";
			$bar3.style.marginLeft="-25%";
			$bar3.style.marginBottom="30%";
			$bar3.style.width="160%";
			document.getElementById("menu").style.marginRight="18%";
			document.getElementById("search").style.marginRight="15%";
			document.getElementById("menu").style.position ="fixed";
			document.getElementById("main_body").style.position="fixed";
			click=1;
			setTimeout(text_display,650);
			}
			else
			{
			document.getElementById("profile").style.visibility="hidden";
			document.getElementById("on_menu_click").style.width="0%";
			$bar2.style.width="80%";
			$bar2.style.marginLeft="0%";
			$bar1.style.transform="rotate(0deg)";
			$bar1.style.marginTop="0%";
			$bar1.style.marginLeft="0%";
			$bar1.style.width="80%";
			$bar3.style.transform="rotate(0deg)";
			$bar3.style.marginLeft="0%";
			$bar3.style.marginBottom="0%";
			$bar3.style.width="80%";
			document.getElementById("menu").style.marginRight="0%";
			document.getElementById("search").style.marginRight="0%";
			document.getElementById("menu").style.position ="absolute";
			document.getElementById("main_body").style.position="initial";
			click=0;
			}
		}
				function preview_image(event) 
{
 var reader = new FileReader();
 reader.onload = function()
 {
  var output = document.getElementById("output_image");
  output.style.display="block";
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}
function display(imagename) 
		{
		document.getElementById("popup_image_box").style.display="block";
		var link = document.getElementById("img_link");
		link.setAttribute("href",imagename);
		var link = document.getElementById("img_link2");
		link.setAttribute("href",imagename);
		var imagelocation = document.getElementById("display_image_part");
		imagelocation.setAttribute("src", imagename);
		document.getElementById("display_image_body").style.display="none";
		// var download = document.getElementById("download");
		// download.setAttribute("href",imagename);
		}
		function cross()
		{ 
		document.getElementById("popup_image_box").style.display="none";
		document.getElementById("display_image_body").style.display="block";		
		}
	</script>
<script>
function load()
{
location.reload();
}
</script>
</body>
</html>';
?>
