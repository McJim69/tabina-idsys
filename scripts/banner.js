////////////////////////////////////////
//	McJim Cyberworks & Development	  //
//  Malim, Tabina, Zamboanga del Sur  //
//	7034 Philippines				  //
//	www.mcjim-cyberworks.co.nr		  //
//	www.facebook.com/cybermcjim 	  //
//////////////////////////////////////// 
	var t;
	var currentLeft=0;
	var slides=12;
	var speed=3000;
		function gotoSlide(s){
			if(s=="next"){
			if(currentLeft<=((slides-1)*265*-1)){
				$("#slidecontainer").animate({
					left:currentLeft-265
					},function(){
					currentLeft=0;
						$("#slidecontainer").animate({
					left:currentLeft
						},speed);
					});
				}
			else{
				$("#slidecontainer").animate({
					left:currentLeft+265
						},function(){
					currentLeft-=265;
						$("#slidecontainer").animate({
					left:currentLeft
						},speed);
						});
					}
				}
			else{
				if(currentLeft==0){
					$("#slidecontainer").animate({
						left:currentLeft+265
							},function(){
					currentLeft=(slides-1)*265*-1;
						$("#slidecontainer").animate({
						left:currentLeft
						},speed);
					});
				}
			else{
					$("#slidecontainer").animate({
					left:currentLeft-265
						},function(){
					currentLeft+=265;
						$("#slidecontainer").animate({
					left:currentLeft
						},speed);
					});
				}
			}
		}
	t=setInterval("gotoSlide('next')",6000);
