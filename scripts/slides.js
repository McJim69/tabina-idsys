var t;
var currentLeft=0;
var slides=18;
var speed=3000;
function gotoSlide(s){
	if(s=="next"){
		if(currentLeft<=((slides-1)*500*-1)){
			$("#slidecontainer").animate({
				left:currentLeft-500
			},function(){
				currentLeft=0;
				$("#slidecontainer").animate({
					left:currentLeft
				},speed);
			});
		}
		else{
			$("#slidecontainer").animate({
				left:currentLeft+500
			},function(){
				currentLeft-=500;
				$("#slidecontainer").animate({
					left:currentLeft
					},speed);
				});
			}
		}
		else{
			if(currentLeft==500){
				$("#slidecontainer").animate({
			left:currentLeft+500
		},function(){
			currentLeft=(slides-1)*500*-1;
				$("#slidecontainer").animate({
					left:currentLeft
				},speed);
			});
		}
		else{
		$("#slidecontainer").animate({
			left:currentLeft-500
		},function(){
			currentLeft+=0;
			$("#slidecontainer").animate({
			left:currentLeft
			},speed);
			});
		}
	}
}
//t=setInterval("gotoSlide('next')",6000);
