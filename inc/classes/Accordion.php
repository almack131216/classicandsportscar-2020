<?php
		
	Class Accordion {		
		
		//////////////////////////
		/// FUNCION // PRINT ITEMS
		function AddAccordion(){
			global $PageBuild,$jsTags;
			
			$PageBuild->SetBodyTag('<body id="slide-effect">');
			$PageBuild->AddTags("accordion/style.css");
			$PageBuild->AddTags("accordion/mootools_v1_11.js");
			$jsTags .= <<<EOD
<script type="text/javascript">
	//<![CDATA[	

	var Site = {

		start: function(){
			if($('vertical')) Site.vertical();
		},
		
		vertical: function(){
			var list = $$('#vertical li div.collapse');
			var headings = $$('#vertical li h3');
			var collapsibles = new Array();
			
			headings.each( function(heading, i) {

				var collapsible = new Fx.Slide(list[i], { 
					duration: 500, 
					transition: Fx.Transitions.linear,
					onComplete: function(request){ 
						var open = request.getStyle('margin-top').toInt();
						if(open >= 0) new Fx.Scroll(window).toElement(headings[i]);
					}
				});
				
				collapsibles[i] = collapsible;
				
				heading.onclick = function(){
					
					var span = this.getElementsByTagName("acronym");
					//alert(span.innerHTML +" / "+spanExpand[0].innerHTML );

					if(span){
						if(span[0].className=="expand"){
							span[0].className = "collapse";
						}else{
							span[0].className = "expand";
						}
					}

					collapsible.toggle();
					return false;
				}
				
				collapsible.hide(); 
				
			});
			
			$('collapse-all').onclick = function(){
				headings.each( function(heading, i) {
					collapsibles[i].hide();
					var span = heading.getElementsByTagName("acronym");
					span[0].className = "expand";
				});
				return false;
			}
			
			$('expand-all').onclick = function(){
				headings.each( function(heading, i) {
					collapsibles[i].show();
					var span = heading.getElementsByTagName("acronym");
					span[0].className = "collapse";
				});
				return false;
			}
			
		}
	};
	window.addEvent('domready', Site.start);
//]]>	
</script>
EOD;
		}
		//END
			
	}
	
	$Accordion = new Accordion();

?>