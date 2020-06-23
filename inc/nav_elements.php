<?php				

	/* --------------------------------- */
	/* --------------------------------- */
	/* --------------------------------- */
	/* -----||   NAV ELEMENTS  ||------- */
	
								
	$arr_page	= array(
						array(	array('name'=>'home',			'title'=>'Homepage',		'title_x'=>'Link to the '.$client['name'].' homepage',			'href'=>$SEO_links->GenerateLink("homepage")),								
								array('name'=>'catalogue',		'title'=>'Classic cars For Sale',	'title_x'=>'Link to our classic car showroom page',		'href'=>$SEO_links->GenerateLink("classifieds")),
								array('name'=>'archive',			'title'=>'Classic Cars Sold',	'title_x'=>'Link to archive showing cars sold over the years',				'href'=>$SEO_links->GenerateLink("sold")),
								array('name'=>'selling',		'title'=>'Selling a Classic Car',	'title_x'=>'Link to seller page, we can help you sell your classic car',				'href'=>$SEO_links->GenerateLink("selling")),
								array('name'=>'transport',		'title'=>'Classic Car Transportation',	'title_x'=>'Link to transport services page - we can help transport your classic car',			'href'=>$SEO_links->GenerateLink("transport")),
								array('name'=>'testimonials',	'title'=>'Customer Testimonials',	'title_x'=>'Link to our customer comments page',		'href'=>$SEO_links->GenerateLink("testimonials")),
								array('name'=>'press',			'title'=>'Press / Media Articles',	'title_x'=>'Link to press &amp; media articles',		'href'=>$SEO_links->GenerateLink("press")),
								array('name'=>'news',			'title'=>'Latest News &amp; Events',	'title_x'=>'Link to news stories, past and present',			'href'=>$SEO_links->GenerateLink("news")),
								array('name'=>'plates',			'title'=>'Registration Numbers',	'title_x'=>'Link to see private plates we have in stock',		'href'=>$SEO_links->GenerateLink("plates")),
								array('name'=>'request',		'title'=>'Request a Car',	'title_x'=>'Link to register your specific requirements and we will help you find one',		'href'=>$SEO_links->GenerateLink("request")),
								array('name'=>'mcw',			'title'=>'Automotive Body &amp; Paint',	'title_x'=>'Link to Malton Coachworks',		'href'=>"http://www.maltoncoachworks.co.uk/",'target'=>"_blank",'class'=>"current mcw"),
								//array('name'=>'about',			'title'=>'About Us',	'title_x'=>'Link to full story of Classic and Sportscar / Grundy Mack Classic Cars',		'href'=>$SEO_links->GenerateLink("about")),
								//array('name'=>'contact',		'title'=>'Contact Us',	'title_x'=>'Link to contact page, where you can find all our contact details',		'href'=>$SEO_links->GenerateLink("contact"))
								array('name'=>'filmTV',		'title'=>'Film & TV Hire',	'title_x'=>'Link to Film & TV Hire page',		'href'=>$SEO_links->GenerateLink("filmTV"))								
							)
					);
								
					
	//echo $arr_page[0][1]['name'];
	
?>