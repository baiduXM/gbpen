$(document).ready(function(){
	 jQuery(".focus").slide({ titCell:"#tip li", mainCell:"#pic ul",effect:"left",autoPlay:true,delayTime:200 })

		// 产品展示
	$('#prizes .photos-content').jCarouselLite({
		btnPrev: '#prizes a.photos-prev',
		btnNext: '#prizes a.photos-next',
		visible: 5,
		auto: 3000,
		speed: 1000
	}).css({visibility:"visible"});




})
function setTab(m,n){ 
	var tli=document.getElementById("menu"+m).getElementsByTagName("li"); 
	var mli=document.getElementById("main"+m).getElementsByTagName("ul"); 
	for(i=0;i<tli.length;i++){ 
	tli[i].className=i==n?"hover":""; 
	mli[i].style.display=i==n?"block":"none"; 
	} 
} 


