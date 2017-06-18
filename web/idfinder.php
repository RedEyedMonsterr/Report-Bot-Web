<?php
//error_reporting(0);
require("functions.php");
bcscale(0);
if(isset($_GET['s'])){$steamid=htmlentities($_GET['s']);}else{$steamid="";} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>.SteamID Finder</title>
		<meta name="keywords" content="Steam, Tool, Search, ID, Steam ID, finder" />
		<meta name="description" content="Searches the Steam database for a player then returns the player information. (If Any)" />	
	</head>
	<body>
		<div id="container">
			<div id="content">
				<div id="searchBox" class="steamBox">
					<form id="searchForm">
						<input type="text" id="searchBar" name="s" size="52" placeholder="Search by Steamid, Community ID, or Custom URL" />
						<br>
						<br>
						<input type="submit" id="searchButton" class="button1" value="Submit"/>
					</form>
					<?php if($steamid!="") echo '<div class="center" style="margin-top:5px;">Search: '.htmlentities($steamid).'</div>'; ?>
				</div>
				<br>
				<div id="infoBox" class="steamBox">
				<?php if($steamid==""){
					echo '<div style="margin: 5px 0;"> </div>';
				}else{
					$xmlf = buildSteamURL($steamid);
					libxml_use_internal_errors(TRUE);
					$xml = simplexml_load_file($xmlf);
					if(!isset($steam64)){$steam64 = $xml->steamID64;}
					if(!isset($steam32)){$steam32 = get_steamid_community($steam64);}
					$vac['0']="No";
					$vac['1']="Has Bans on record";
					if(libxml_get_errors()!=NULL){
						echo '<div class="error">Oops. Steam is overloaded. Try again Later, or <a href="?'.htmlentities($_SERVER['QUERY_STRING']).'">Re-Search</a> now.</div>';
					}elseif(error_get_last()!=NULL){
						echo '<div class="error">An Error has occured. Try again later, or <a href="?'.htmlentities($_SERVER['QUERY_STRING']).'">Re-Search</a> now.</div>';
					}elseif($xml->error=="The specified profile could not be found." || $xml->error=="115"){
						echo '<div class="warning">You searched for a Player that does not exist. (Or do they :O)</div>';
					}elseif($xml->privacyMessage){ ?>
						<div class="warning">This user is being Anti-Social and didnt set up their Page. But Heres some Info Anyway.</div>
						<div id="steamData">
							<div>SteamID32: <?php echo htmlentities($steam32); ?></div>
							<div>SteamID64: <?php echo htmlentities($steam64); ?></div>
							<div>Vac Banned?: <?php echo htmlentities($vac["$xml->vacBanned"]); ?></div>
							<div>Profile: <a href="http://steamcommunity.com/profiles/<?php echo htmlentities($xml->steamID64); ?>" title="Click here to go to <?php echo htmlentities($xml->steamID); ?>'s Steam Page" target="_blank">Click Here</a></div>
						</div>
						<div style="display: block; clear: both;"></div>
					<?php }else{
						$username = $xml->steamID;
						if($xml->privacyState!="public"){$steamRating= "Profile Private";}else{$steamRating = $xml->steamRating;}
						if($xml->privacyState!="public"||!isset($xml->hoursPlayed2Wk)){$playTime="Unavailable";}else{$playTime = $xml->hoursPlayed2Wk;} ?>
						<div id="steamData">
							<div>Username: <?php echo htmlentities($username); ?></div>
							<div>SteamID64: <?php echo htmlentities($steam64); ?></div>
							<div>Banned?: <?php echo htmlentities($vac["$xml->vacBanned"]); ?></div>
							<div>Profile: <a href="http://steamcommunity.com/profiles/<?php echo htmlentities($xml->steamID64); ?>" title="Click here to go to <?php echo htmlentities($xml->steamID); ?>'s Steam Page" target="_blank">Click Here</a></div>
						</div>
						<div style="display: block; clear: both;"></div>
					<?php }
					} ?>
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
</html>
