// Prepare Image Gallery
function prepareGallery() {
	var galleryList = document.getElementById("thumbs");
	if(!galleryList) return;
	var images = galleryList.getElementsByTagName("img");
	
	for (i=0;i<images.length;i++) {
		images[i].onmouseover = showPic; //onclick
		images[i].style.cursor = "pointer";
		};
	
	function showPic() {
		var source = this.getAttribute("name");
		var title = this.getAttribute("title");
		var caption = document.getElementById("caption");
		var placeholder = document.getElementById("placeholder");
		placeholder.setAttribute("src",source);
		caption.innerHTML = title;
	};
}
addLoadEvent(prepareGallery);