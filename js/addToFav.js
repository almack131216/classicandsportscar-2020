/////////////////////////////////////////////////////////////////////////
// addToFav script
// Author: Alec Hill
/////////////////////////////////////////////////////////////////////////

// DESCRIPTION
//this script will open the add bookmark dialogue of your browser and automatically fill in the url and the title 
//you will need a link with id="addtofav" which will be the link to click, use href="#" so that it is valid, and do not add any text to click, as this script will add that at runtime, so if the script will not add a favourite on a particular browser, it will not display a link, degrading gracefully


//////////////////////////////////////////////////////////////////////////////////
function initiateFavLink(){
	if(!document.getElementsByTagName) return false;
	if(!document.getElementsByTagName("a")) return false;
	if(!document.createTextNode) return false;
	if(!window.sidebar && !document.all) return false;
	if(!document.title) return false;
	if(!location.href) return false;
	var linkText = document.createTextNode("Bookmark this page");
	var pageToBookmark = location.href;
	var pageTitle = document.title;
	var links = document.getElementsByTagName("a");
	for (var i=0; i<links.length; i++){
		if(links[i].getAttribute("id") == "addtofav"){
			links[i].style.display = "block";
			links[i].onclick = function() {
									if (window.sidebar) {
										window.sidebar.addPanel(pageTitle, pageToBookmark,"");
										return false;
									} else if( document.all ) {
										window.external.AddFavorite( pageToBookmark, pageTitle);
										return false;
									} else if( window.opera && window.print ) {
										return true;
									}
								}
			links[i].appendChild(linkText);
			return false;
		}else{
			continue;
		}
	}
	return false;
}