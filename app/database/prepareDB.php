<?php

// Manualno inicijaliziramo bazu ako već nije.
require_once 'db.class.php';

$db = DB::getConnection();

$has_tables = false;

try
{
	$st = $db->prepare(
		'SHOW TABLES LIKE :tblname'
	);

	$st->execute( array( 'tblname' => 'users' ) );
	if( $st->rowCount() > 0 )
		$has_tables = true;

	$st->execute( array( 'tblname' => 'products' ) );
	if( $st->rowCount() > 0 )
		$has_tables = true;

	$st->execute( array( 'tblname' => 'tables' ) );
	if( $st->rowCount() > 0 )
		$has_tables = true;

	$st->execute( array( 'tblname' => 'trainings' ) );
	if( $st->rowCount() > 0 )
		$has_tables = true;
}
catch( PDOException $e ) { exit( "PDO error [show tables]: " . $e->getMessage() ); }


if( $has_tables )
{
	exit( 'Tablice users / products / tables / trainings već postoje. Obrišite ih pa probajte ponovno.' );
}


try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS users (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'username varchar(50) NOT NULL,' .
		'password_hash varchar(255) NOT NULL,'.
		'email varchar(50) NOT NULL,' .
		'registration_sequence varchar(20) NOT NULL,' .
		'has_registered int,' .
		'admin BOOLEAN NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create users]: " . $e->getMessage() ); }

echo "Napravio tablicu users.<br />";

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS products (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_user int NOT NULL,' .
		'title varchar(50) NOT NULL,' .
		'abstract varchar(1000) NOT NULL,' .
		'number_available int NOT NULL,' .
		'status varchar(10) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create products]: " . $e->getMessage() ); }

echo "Napravio tablicu products.<br />";


try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS tables (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_user INT NOT NULL,' .
		'o2_table varchar(24) NOT NULL,' .
		'co2_table varchar(24) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create tables]: " . $e->getMessage() ); }

echo "Napravio tablicu tables.<br />";

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS trainings (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_user INT NOT NULL,' .
		'type varchar(1) NOT NULL,' .
		'duration int NOT NULL,' .
		'date DATE NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create trainings]: " . $e->getMessage() ); }

echo "Napravio tablicu trainings.<br />";

// Ubaci neke korisnike unutra
try
{
	$st = $db->prepare( 'INSERT INTO users(username, password_hash, email, registration_sequence, has_registered, admin) VALUES (:username, :password, \'a@b.com\', \'abc\', \'1\', :admin)' );

	$st->execute( array( 'username' => 'mirko', 'password' => password_hash( 'mirkovasifra', PASSWORD_DEFAULT ), 'admin' => 1 ) );
	$st->execute( array( 'username' => 'ana', 'password' => password_hash( 'aninasifra', PASSWORD_DEFAULT ), 'admin' => 0 ) );
	$st->execute( array( 'username' => 'maja', 'password' => password_hash( 'majinasifra', PASSWORD_DEFAULT ), 'admin' => 0 ) );
	$st->execute( array( 'username' => 'slavko', 'password' => password_hash( 'slavkovasifra', PASSWORD_DEFAULT ), 'admin' => 0 ) );
	$st->execute( array( 'username' => 'pero', 'password' => password_hash( 'perinasifra', PASSWORD_DEFAULT ), 'admin' => 0 ) );
}
catch( PDOException $e ) { exit( "PDO error [insert users]: " . $e->getMessage() ); }

echo "Ubacio u tablicu users.<br />";


// Ubaci neke projekte unutra (ovo nije baš pametno ovako raditi, preko hardcodiranih id-eva usera)
try
{
	$st = $db->prepare( 'INSERT INTO products(id_user, title, abstract, number_available, status) VALUES (:id, :t, :a, :no, :st)' );

	$st->execute( array( 'id' => 1, 't' => 'Spearfishing rifle', 'a' => 'Perfect condition, used only twice.', 'no' => 1, 'st' => 'open' ) ); // mirko
	$st->execute( array( 'id' => 2, 't' => 'Speedo bathing suit', 'a' => 'Brand new, color optional.', 'no' => 50, 'st' => 'open' ) ); // ana
	$st->execute( array( 'id' => 3, 't' => 'Manual of Freediving', 'a' => 'Brand new books (by Umberto Pelizzari).', 'no' => 5, 'st' => 'open' ) ); // maja
 	$st->execute( array( 'id' => 1, 't' => 'Nose clip', 'a' => 'Metal, in two colors.', 'no' => 100, 'st' => 'closed' ) ); // mirko
	$st->execute( array( 'id' => 4, 't' => 'Projekt za RP2', 'a' => 'Već ćemo nešto smisliti, prvo idemo okupiti tim.', 'no' => 4, 'st' => 'open' ) ); // slavko
}
catch( PDOException $e ) { exit( "PDO error [products]: " . $e->getMessage() ); }

echo "Ubacio u tablicu products.<br />";


// Ubaci neke članove unutra (ovo nije baš pametno ovako raditi, preko hardcodiranih id-eva usera i projekata)
try
{
	$st = $db->prepare( 'INSERT INTO tables(id, id_user, o2_table, co2_table) VALUES (:id, :id_user, :o2_table, :co2_table)' );

	$st->execute( array( 'id' => 1, 'id_user' => 1, 'o2_table' => '1:001:001:001:001:001:00', 'co2_table' => '1:101:101:101:101:101:10' ) ); // mirko
	$st->execute( array( 'id' => 2, 'id_user' => 2, 'o2_table' => '1:001:001:001:001:001:00', 'co2_table' => '1:201:201:201:201:201:20'  ) ); // ana
	$st->execute( array( 'id' => 3, 'id_user' => 3, 'o2_table' => '1:001:001:001:001:001:00', 'co2_table' => '1:301:301:301:301:301:30'  ) ); // maja
	$st->execute( array( 'id' => 4, 'id_user' => 4, 'o2_table' => '1:001:001:001:001:001:00', 'co2_table' => '1:401:401:401:401:401:40'  ) ); // slavko
	$st->execute( array( 'id' => 5, 'id_user' => 5, 'o2_table' => '1:001:001:001:001:001:00', 'co2_table' => '1:501:501:501:501:501:50'  ) ); // pero


	// $st->execute( array( 'id_project' => 1, 'id_user' => 1, 'type' => 'member' ) ); // autor (mirko) - go
	// $st->execute( array( 'id_project' => 2, 'id_user' => 2, 'type' => 'member' ) ); // autor (ana) - fejsbuk
	// $st->execute( array( 'id_project' => 3, 'id_user' => 3, 'type' => 'member' ) ); // autor (maja) - recepti
	// $st->execute( array( 'id_project' => 4, 'id_user' => 1, 'type' => 'member' ) ); // autor (mirko) - amazon
	// $st->execute( array( 'id_project' => 5, 'id_user' => 4, 'type' => 'member' ) ); // autor (slavko) - rp2
	// $st->execute( array( 'id_project' => 2, 'id_user' => 3, 'type' => 'invitation_accepted' ) ); // maja - fejsbuk
	// $st->execute( array( 'id_project' => 2, 'id_user' => 5, 'type' => 'application_accepted' ) ); // pero - fejsbuk
	// $st->execute( array( 'id_project' => 4, 'id_user' => 4, 'type' => 'application_accepted' ) ); // slavko - amazon
	// $st->execute( array( 'id_project' => 3, 'id_user' => 5, 'type' => 'member' ) ); // pero - recepti
	// $st->execute( array( 'id_project' => 3, 'id_user' => 1, 'type' => 'application_pending' ) ); // mirko - recepti
	// $st->execute( array( 'id_project' => 5, 'id_user' => 2, 'type' => 'invitation_pending' ) ); // ana - rp2
}
catch( PDOException $e ) { exit( "PDO error [tables]: " . $e->getMessage() ); }

echo "Ubacio u tablicu tables.<br />";

try
{
	$st = $db->prepare( 'INSERT INTO trainings(id, id_user, type, duration, date) VALUES (:id, :id_user, :type, :duration, :date)' );

	$st->execute( array( 'id' => 1, 'id_user' => 1, 'type' => 'o', 'duration' => 6, 'date' => '2022-01-05' ) ); // mirko
	$st->execute( array( 'id' => 2, 'id_user' => 1, 'type' => 'o', 'duration' => 6, 'date' => '2022-01-07'  ) ); // mirko
	$st->execute( array( 'id' => 3, 'id_user' => 1, 'type' => 'o', 'duration' => 12, 'date' => '2022-01-09'  ) ); // mirko
	$st->execute( array( 'id' => 4, 'id_user' => 1, 'type' => 'o', 'duration' => 15, 'date' => '2022-01-11'  ) ); // mirko
	$st->execute( array( 'id' => 5, 'id_user' => 1, 'type' => 'o', 'duration' => 15, 'date' => '2022-01-12'  ) ); // mirko
	
	$st->execute( array( 'id' => 6, 'id_user' => 1, 'type' => 'c', 'duration' => 6, 'date' => '2022-01-05' ) ); // mirko
	$st->execute( array( 'id' => 7, 'id_user' => 1, 'type' => 'c', 'duration' => 15, 'date' => '2022-01-07'  ) ); // mirko
	$st->execute( array( 'id' => 8, 'id_user' => 1, 'type' => 'c', 'duration' => 15, 'date' => '2022-01-09'  ) ); // mirko
	$st->execute( array( 'id' => 9, 'id_user' => 1, 'type' => 'c', 'duration' => 21, 'date' => '2022-01-11'  ) ); // mirko
	$st->execute( array( 'id' => 10, 'id_user' => 1, 'type' => 'c', 'duration' => 27, 'date' => '2022-01-12'  ) ); // mirko

	$st->execute( array( 'id' => 11, 'id_user' => 2, 'type' => 'c', 'duration' => 6, 'date' => '2022-01-05' ) ); // ana
	$st->execute( array( 'id' => 12, 'id_user' => 2, 'type' => 'c', 'duration' => 15, 'date' => '2022-01-07'  ) ); // ana
	$st->execute( array( 'id' => 13, 'id_user' => 2, 'type' => 'c', 'duration' => 15, 'date' => '2022-01-09'  ) ); // ana
	$st->execute( array( 'id' => 14, 'id_user' => 2, 'type' => 'c', 'duration' => 21, 'date' => '2022-01-11'  ) ); // ana
	$st->execute( array( 'id' => 15, 'id_user' => 2, 'type' => 'c', 'duration' => 27, 'date' => '2022-01-12'  ) ); // ana
	
	// $st->execute( array( 'id_project' => 1, 'id_user' => 1, 'type' => 'member' ) ); // autor (mirko) - go
	// $st->execute( array( 'id_project' => 2, 'id_user' => 2, 'type' => 'member' ) ); // autor (ana) - fejsbuk
	// $st->execute( array( 'id_project' => 3, 'id_user' => 3, 'type' => 'member' ) ); // autor (maja) - recepti
	// $st->execute( array( 'id_project' => 4, 'id_user' => 1, 'type' => 'member' ) ); // autor (mirko) - amazon
	// $st->execute( array( 'id_project' => 5, 'id_user' => 4, 'type' => 'member' ) ); // autor (slavko) - rp2
	// $st->execute( array( 'id_project' => 2, 'id_user' => 3, 'type' => 'invitation_accepted' ) ); // maja - fejsbuk
	// $st->execute( array( 'id_project' => 2, 'id_user' => 5, 'type' => 'application_accepted' ) ); // pero - fejsbuk
	// $st->execute( array( 'id_project' => 4, 'id_user' => 4, 'type' => 'application_accepted' ) ); // slavko - amazon
	// $st->execute( array( 'id_project' => 3, 'id_user' => 5, 'type' => 'member' ) ); // pero - recepti
	// $st->execute( array( 'id_project' => 3, 'id_user' => 1, 'type' => 'application_pending' ) ); // mirko - recepti
	// $st->execute( array( 'id_project' => 5, 'id_user' => 2, 'type' => 'invitation_pending' ) ); // ana - rp2
}
catch( PDOException $e ) { exit( "PDO error [trainings]: " . $e->getMessage() ); }

echo "Ubacio u tablicu trainings.<br />";

?>