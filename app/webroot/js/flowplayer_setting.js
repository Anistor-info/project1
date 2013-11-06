// JavaScript Document

 flowplayer("player",swf, {
		  canvas: {
			backgroundColor:'white',
			backgroundGradient: [0.1, 0]
		}, 
		onFinish: function(clip) {
			//var next_index = clip.index + 1;
			//$f().getClip(next_index).update({autoPlay: true});
			
			var div = $('.clips a');
			if(div.length > i){
				this.play($(div).eq(i).attr('href'));
				i++;
			}else{
				this.play($(div).eq(0).attr('href'));
				i = 1;	
			}
		},
		plugins: {
			controls: {
				//playlist: true,
				//stop:true
				//next:true
				}
		 },
		 clip: {
			  autoPlay: false,
			  scaling: 'orig',
			  title: '',
			  autoBuffering: true
		 }
	  }).playlist("div.clips", {
				continuousPlay: true,
				loop: true
		});	
		
	  // play the video 
	   $('.parent_bin li').live('click',function(){
		 if($(this).hasClass('li_timeline')){
			flowplayer("player",swf, {
			  canvas: {
				backgroundColor:'white',
				backgroundGradient: [0.1, 0]
			}, 
			onFinish: function(clip) {
				//var next_index = clip.index + 1;
				//$f().getClip(next_index).update({autoPlay: true});
				
				var div = $('.clips a');
				if(div.length > i){
					this.play($(div).eq(i).attr('href'));
					i++;
				}else{
					this.play($(div).eq(0).attr('href'));
					i = 1;	
				}
			},
			plugins: {
				controls: {
					//playlist: true,
					//stop:true
					//next:true
					}
			 },
			 clip: {
				  autoPlay: false,
				  scaling: 'orig',
				  title: '',
				  autoBuffering: true
			 }
		     }).playlist("div.clips", {
					continuousPlay: true,
					loop: true
			});	

			var video_path = $('#'+this.id).attr('path');
			$f().play(video_path);
			$f().addClip({url: video_path}, 0);
			i = 2;
		}else{
			var video_path = $('#'+this.id).attr('path');
			flowplayer("player",swf).play(video_path);
		}
		})	