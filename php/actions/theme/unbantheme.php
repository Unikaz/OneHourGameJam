<?php

//Unmarks a suggested theme as banned (unbans it)
function UnbanTheme($unbannedThemeId){
	global $dbConn, $ip, $userAgent, $loggedInUser, $adminLogData, $themeData;

	//Authorize user (logged in)
	if($loggedInUser === false){
		return "NOT_LOGGED_IN";
	}

	//Authorize user (is admin)
	if(IsAdmin($loggedInUser) === false){
		return "NOT_AUTHORIZED";
	}

	$themeAuthorUserId = -1;
	$themeFound = false;
	$unbannedTheme = "";
	foreach($themeData->ThemeModels as $id => $themeModel) {
		if ($themeModel->Deleted != 0){
			continue;
		}
		if ($themeModel->Id == $unbannedThemeId) {
			$themeAuthorUserId = $themeModel->AuthorUserId;
			$unbannedTheme = $themeModel->Theme;
			$themeFound = true;
		}
	}

	if(!$themeFound){
		return "THEME_DOES_NOT_EXIST";
	}

	$clean_unbannedThemeId = mysqli_real_escape_string($dbConn, $unbannedThemeId);
	$clean_ip = mysqli_real_escape_string($dbConn, $ip);
	$clean_userAgent = mysqli_real_escape_string($dbConn, $userAgent);

	//Check that theme actually exists
	$sql = "SELECT theme_id FROM theme WHERE theme_banned = 1 AND theme_id = '$clean_unbannedThemeId'";
	$data = mysqli_query($dbConn, $sql);
	$sql = "";

	if(mysqli_num_rows($data) == 0){
		return "THEME_DOES_NOT_EXIST";
	}

	$sql = "UPDATE theme SET theme_banned = 0 WHERE theme_banned = 1 AND theme_id = '$clean_unbannedThemeId'";
	$data = mysqli_query($dbConn, $sql);
	$sql = "";

    $adminLogData->AddToAdminLog("THEME_UNBANNED", "Theme '$unbannedTheme' unbanned", $themeAuthorUserId, $loggedInUser->Id, "");

	return "SUCCESS";
}

function PerformAction(&$loggedInUser){
	global $_POST;
	
	if(IsAdmin($loggedInUser) !== false){
		$unbannedThemeId = $_POST["theme_id"];
		return UnbanTheme($unbannedThemeId);
	}
}

?>