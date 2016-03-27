<?php
date_default_timezone_set('Europe/Warsaw');
function dbConnect() {
  $servername = "localhost";
  $username = "root";
  $password = "haslo";
  $database = "forigi-cms";

  $con = mysqli_connect($servername, $username, $password, $database);
  if (!$con || mysqli_connect_errno()) {
  	die('MySQL Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
  } else {
    query($con, "SET time_zone = 'Europe/Warsaw';");
  }
  return $con;
}

function query($con, $que){
	$result = mysqli_query($con, $que) or die ("MySQLi query ERROR! ".mysqli_error($con));
  if (!$result) {
  	//return '';
    	return mysqli_error($con);
  }
  return $result;
}

function multiQuery($con, $que) {
  mysqli_multi_query($con, $que) or die ("MySQLi query ERROR! ".mysqli_error($con));
}

function checkInput($con, $value) {
	if ($value && !is_numeric($value)) {
		$value = "'".mysqli_real_escape_string($con, $value)."'";
	}
	return $value;
}

function getCategories() {
  $con = dbConnect();
  $q = 'SELECT * FROM categories;';
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

function getCategoriesNotEmpty($pCat) {
  $pCat = (is_numeric($pCat)) ? (int)$pCat : NULL;
  $qPart = ($pCat) ?  ' AND c.parent_id = '.$pCat : '';
  $con = dbConnect();
  $q = "SELECT c.* FROM categories c, categories_to_topics ct WHERE ct.category_id = c.id".$qPart." GROUP BY c.id;";
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

function getSubCategories($parentCatId) {

  $con = dbConnect();
  $q = "SELECT * FROM categories WHERE parent_id = $parentCatId;";
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

function getTopics() {
  $con = dbConnect();
  $q = 'SELECT * FROM topics;';
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

function getTopicsFromCat($c) {
  $con = dbConnect();
  $q = "SELECT t.id, t.name, t.caption, t.img, v.url FROM (topics t LEFT JOIN videos v ON v.topic_id = t.id), categories_to_topics ct, categories c WHERE ct.topic_id = t.id AND ct.category_id = c.id AND c.id = $c ORDER BY t.add_date DESC;";
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

function getVideos() {
  $con = dbConnect();
  $q = 'SELECT * FROM videos;';
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

function getImages() {
  $con = dbConnect();
  $q = 'SELECT * FROM images;';
  $result = query($con, $q);
  mysqli_close($con);
  return $result;
}

?>