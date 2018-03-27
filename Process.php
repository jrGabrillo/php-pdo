<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {
	    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	    header('Access-Control-Allow-Credentials: true');
	    header('Access-Control-Max-Age: 86400');
	}
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	}

	session_start();
	include("Functions.php");
	$Functions = new DatabaseClasses;

	if (isset($_GET['auth'])){ /**/
		print_r('162165146157156147141142162151154154157152162');
	}

	if (isset($_GET['kill-session'])){ /**/
		if(isset($_POST["data"])){
			print_r(session_destroy());
		}
		else{
			echo "Hacker";
		}
	}

	if(isset($_GET['check-login'])){ /**/
		print_r(json_encode($_SESSION['kareer7836']));
	}

	if(isset($_GET['validateEmail'])){/**/
		$data = $_POST['data'];
        $access = $Functions->escape($data);
		$query = $Functions->PDO("SELECT count(*) FROM tbl_businessmanagers INNER JOIN tbl_applicant ON tbl_applicant.email = tbl_businessmanagers.email WHERE tbl_businessmanagers.email = {$access}");
		print_r($query[0][0]);
	}

	if(isset($_GET['validateEmployer'])){/**/
		$data = $_POST['data'];
		$count = 0;
		$query = $Functions->PDO("SELECT count(*) FROM tbl_employer WHERE email = '{$data}'");
		$count = $count + $query[0][0];
		print_r($count);
	}

	if(isset($_GET['send-mail'])){/**/
		$data = $_POST['data'];
		$message = "<div style='text-align: center;width: 500px;position: relative;margin: 0 auto;border-radius: 3px;background: #4485F4;color: #fff;padding: 30px;border-top: yellow solid 10px;top: 50px;box-shadow: 0px 0px 50px #ccc;margin-top: 50px;margin-bottom: 50px;'><b><font size='6'>Welcome to Kareer</font></b><br/><br/><br/>Thank you for registering to Kareer. Here is your&nbsp;system generated password: {$data[1]}&nbsp;<br/><br/><br/>Please change your password as soon as you get in to your account. <br/><br/><br/><br/>Thanks and God bless.</div> ";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Kareer' . "\r\n";
		$subject = 'Kareer - Applicant Account Registration';
		$result = mail($data[0],$subject,$message,$headers);
	}

	if (isset($_GET['login'])){/**/
		$data = $_POST['data']; $flag = 0;
        $access = $Functions->escape($data[0]);
        $password = $data[1];
		$query = $Functions->PDO("SELECT * FROM tbl_businessmanagers WHERE email = {$access}");
		if(count($query)==1){
            if($Functions->testPassword($password,$query[0][4]) && ($query[0][8] == 1)){
				$_SESSION["kareer7836"] = [$query[0][0],$access,'employer'];
                print_r(json_encode($_SESSION["kareer7836"]));
            }
		}
		else{
			echo "Log in failed";
		}
	}


	if(isset($_GET['do-addBusinessAccount'])){/**/
        $id = $Functions->PDO_IDGenerator('tbl_businessmanagers','id');
		$date = $Functions->PDO_DateAndTime();
		$data = $_POST['data'];
		$business_id = $Functions->escape($data[1]);
		$name = $Functions->escape($data[2]);
		$position = $Functions->escape($data[3]);
		$email = $Functions->escape($data[4]);
		$password = $Functions->password($data[5]);

		$query = $Functions->PDO("INSERT INTO tbl_businessmanagers(id,business_id,name,email,position,password,status,`date`) VALUES('{$id}',{$business_id},{$name},{$email},{$position},'{$password}','1','{$date}')");
		if($query->execute()){
			echo 1;
		}
		else{
			$Data = $query->errorInfo();
			print_r($Data);
		}
	}

?>