<?php

$servername = "localhost";
$username   = "xyz";
$password   = "xyz";
$dbname     = "test";

$conn = mysqli_connect( $servername, $username, $password );

// Check connection
if ( $conn->connect_error ) {
	die( "Connection failed: " . $conn->connect_error );
}

mysqli_select_db( $conn, $dbname );

$sql    = "SELECT ID, post_name, post_title, post_content FROM wp_posts WHERE post_type='page' AND post_status='publish' ORDER BY ID ASC";
$result = mysqli_query( $conn, $sql );

if ( ! $result ) {
	die( 'Could not get data: ' . mysqli_error( $conn ) );
}

$count = 1;
while( $row = mysqli_fetch_assoc( $result ) ) {
	/**
	 * Uncomment the following code if you have multiple pages with the same name
	 * Helpscout doesn't add the article with the duplicate name
	 *
	 * Following lines make the page name unique by appending the number to the title
	 */
	//$unique = array();
	//if ( isset($unique[ $row['post_name'] ] ) ) {
	//	$row['post_name'] = $row['post_name'] . ' ' . $count;
	//	$count++;
	//}
	//$unique[ $row['post_name'] ] = 'present';

	$page_data[] = array(
		'id'   => $row['ID'],
		'slug' => $row['post_name'],
		'name' => $row['post_title'],
		'text' => utf8_encode($row['post_content']),
	);
}

function doPost( $page_data, $i = 0 ) {
	$page_array = json_encode( array(
		"collectionId" => "xxxxxxxxxxxxx",
		"status"       => "published",
		"slug"         => $page_data[ $i ]['slug'],
		"name"         => ucwords(str_replace( '-', ' ', $page_data[ $i ]['slug'])),
                "text"         => ( "" == $page_data[ $i ]['text'] )? 'Coming soon' : $page_data[ $i ]['text']
	) );

	$httpHeaders   = array();
	$httpHeaders[] = 'Accept: application/json';
	$httpHeaders[] = 'Content-Type: application/json';
	$httpHeaders[] = 'Content-Length: ' . strlen( $page_array );

	$ch = curl_init();

	curl_setopt_array( $ch, array(
		CURLOPT_URL            => 'https://docsapi.helpscout.net/v1/articles',
		CURLOPT_CUSTOMREQUEST  => 'POST',
		CURLOPT_HTTPHEADER     => $httpHeaders,
		CURLOPT_POSTFIELDS     => $page_array,
		CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
		CURLOPT_USERPWD        => 'xxxxxxxxxxxxxxxxxxxxxxx' . ':X',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_CONNECTTIMEOUT => 0,
		CURLOPT_FAILONERROR    => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_HEADER         => true,
		CURLOPT_ENCODING       => 'gzip,deflate',
	) );

	$response = curl_exec($ch);

	sleep( 10 );

	echo "\n" . 'Imported -> ' . $i . ' -> ' . $page_data[ $i ]['id'] . "\n";

	$i++;

	if ( isset( $page_data[ $i ] ) ) {
		doPost( $page_data, $i );
	}
}

doPost( $page_data );

mysqli_close( $conn );
