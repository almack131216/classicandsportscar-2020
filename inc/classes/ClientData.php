<?php

	$client = array('email'		=> "sales@classicandsportscar.ltd.uk",
					'email_wm'	=> "alex@amactive.co.uk",//Web Master
					'tel'		=> "01944 758000",
					'mob'		=> "",
					'fax'		=> "",
					'regNo'		=> "",
					'textNo'	=> "",
					'missingimage'	=> "_missingimage.jpg",
					'domain'	=> "classicandsportscar.ltd.uk",
					'web'		=> "www.classicandsportscar.ltd.uk",
					'siteroot'	=> "http://www.classicandsportscar.ltd.uk/",
					'homepage'	=> "homepage",
					'itemPage'	=> "details.php",
					'name'		=> "Classic and Sportscar Centre",
					'DateFormat'=> "jS F Y",
					'address'	=> "Corner Farm, West Knapton, Malton, North Yorkshire, UK",
					'postcode'	=> "YO17 8JB",
					'pagetitleBasic'	=> "Classic and Sportscar Centre, Malton, North Yorkshire, UK",
					'descriptionBasic'	=> "Classic & Sportscar Centre, formally Grundy Mack stock over 50 classic, vintage and collectors cars. We hold a wide range of stock from pre war cars to modern classics and buy and sell all makes ranging from Ford Model T's to Jaguar E-Type's. We offer a brokerage service and can also arrange classic car and modern car transportation. All our cars comes fully prepared in our own in house workshop and are fully checked over prior to collection or enclosed delivery.",
					'keywordsBasic'		=> "classic jags, rolls royce, grundy mack, cars, classic cars, collectors cars, vintage cars, car sales, car brokerage, for sale, collectors, AC, Alfa Romeo, Alvis, Armstrong Siddeley, Austin Healey, Austin, MG, Mercedes-Benz, Daimler, Jaguar, Rolls Royce, Rover, Bedford, Bentley, BMW, BSA, Bus, Caterham, Chevrolet, Citroen, Cobra, Delage, Ferrari, Ford, Humber, JBA, Jensen, Lagonda, Land Rover, Lotus, Motorcycles, Morris, Plymouth, Porsche, Riley, Saab, Singer, Standard, Sunbeam, TVR, Triumph, Vauxhall, Willys Overland, Wolseley, yorkshire, uk, selling, brokerage, registration numbers, plates, classic and sportscars centre");
					
if($_SERVER['HTTP_HOST']=="localhost") $client['siteroot'] = "http://localhost/csc/";
/*
$catid_testimonials = 71;
$catid_press = 86;
$catid_news = 76;
$catid_transport = 85;
*/
	$clientCats = array(	'Links'		=> '8',
							'Plates'		=> '6',
							//'Transport'		=> '85',
							'Press'			=> '4',
							'Testimonials'	=> '3',
							'News'			=> '5',
							'Classifieds'	=> '2',
							'General'		=> '1'
							);
							
	$clientSubCats = array(	'PageText'	=> '');						
							
	$GoogleMap = array(	'title'		=> $client['name'].', '.$client['address'].', '.$client['postcode'],
						//'key'		=> 'ABQIAAAAvVBk7xOgEj-MJaaECzD0kBRJAQEclOu_RX1i4rQmLtlzeW9FrhTiifcU2eLzcsDOR22JdtvginEz9Q',
						'key'		=> 'ABQIAAAAl-4RrOZAcQYJ4CRwg2ou9RTPUKfXJ9wEhHKijNGnCnj1BvkPThQSxzdaylXAfSPPzoGq5tkBuQ_5Bw',
						'latitude'	=> '54.167354',
						'longitude'	=> '-0.657774',
						'zoom'		=> '10',
						'width'		=> '360px',
						'height'	=> '270px');


?>