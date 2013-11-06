$(function() {
		
		// global variables
		var counter = 1; 
		var NEW_BIN = 0;
		var CURRENT_BIN = 1;
		var ARCHIVE_BIN = 2;
		var timeline_duration = 0;
		var MAXIMUM_TIME_DURATION = 86399;
				
		$( "#archieved_videos_container,#new_videos_container,#current_videos_container,#timeline_videos_container" ).sortable();
		
		// new video options for dragging and sorting
		$( "#new_videos_container" ).sortable("option", "connectWith","#current_bin_trigger,#archieved_bin_trigger,#timeline_videos_container");
		//$( "#current_bin_trigger" ).sortable("option", "connectWith","#archieved_bin_trigger,#timeline_videos_container");
		
	
		// current cloning
		$('.draggable_current').live('mouseenter',function(){
			$(this).draggable({
				connectToSortable: "#archieved_bin_trigger,#timeline_videos_container",
				helper: "clone",
				revert: "invalid",
			})
		})
		
		// archived cloning
		$('.draggable_archived').live('mouseenter',function(){
			$(this).draggable({
				connectToSortable: "#timeline_videos_container",
				helper: "clone",
				revert: "invalid",
			})
		})
				
		// drop on current media
		$( "#current_bin_trigger" ).droppable({
			accept: "#new_videos_container li",
			over:function(){
				// when item over
				$(this).children().text('Drop Here !').css({'color':'red','font-size' : '25px', 'text-align': 'center'});
			},
			out:function(){
				// when item out
				$('#current_bin_trigger').html('<a href="#current_videos_container">Current Media</a>');
			},
			drop: function( event, ui ) {
				var $item = $( this );
				var $list = $( $item.find( "a" ).attr( "href" ) );
				ui.draggable.hide( "slow", function() {	
				   	
					// get dragged item
					var drag_item = ui.draggable;
					var drag_item_id = drag_item.attr('id');
					var video_id = drag_item.attr('vid');
					var path = drag_item.attr('path');
					var duration = drag_item.attr('duration');
					var item_text = drag_item.html();
					
					// create new element
					new_item = $('<li class="draggable_current ui-draggable" id="'+drag_item_id+'" vid="'+video_id+'" path="'+path+'" duration="'+duration+'" >'+item_text+'</li>');
					new_item.draggable({
							connectToSortable: "#archieved_videos_container,#timeline_videos_container,.acc_trigger",
							helper: "clone",
							revert: "invalid"
					})
					
					// append new element to its new position
					$(new_item).prependTo( $list ).show( "slow" );
					set_bin(video_id,CURRENT_BIN);	
				});
				
				// replace drop here with current media
				$(this).children().text('dropped!').fadeOut(2000, function() {
					// Animation complete.
					$('#current_bin_trigger').html('<a href="#current_videos_container">Current Media</a>');
				  });
				  
			}
		});
		
		// drop on archived media
		$( "#archieved_bin_trigger" ).droppable({
			accept: "#new_videos_container li,#current_videos_container li",
			over:function(){
				// when item over
				$('#archieved_bin_trigger').children().text('Drop Here !').css({'color':'red','font-size' : '25px', 'text-align': 'center'});
			},
			out:function(){
				// when item out
				$('#archieved_bin_trigger').html('<a href="#archieved_videos_container">Archived Media</a>');	
			},
			drop: function( event, ui ) {
				var $item = $( this );
				var $list = $( $item.find( "a" ).attr( "href" ) );
				ui.draggable.hide( "slow", function() {
					
					// get dragged item
					var drag_item = ui.draggable;
					var drag_item_id = drag_item.attr('id');
					var video_id = drag_item.attr('vid');
					var path = drag_item.attr('path');
					var duration = drag_item.attr('duration');
					var item_text = drag_item.html();
					
					// creating the new element
					new_item = $('<li class="draggable_archived ui-draggable" id="'+drag_item_id+'" vid="'+video_id+'" path="'+path+'" duration="'+duration+'">'+item_text+'</li>');
					new_item.draggable({
						connectToSortable: "#archieved_videos_container,#timeline_videos_container",
						helper: "clone",
						revert: "invalid"
					})
					
					// append new item to its new position
					$( new_item ).prependTo( $list ).show( "slow" );
					set_bin(video_id,ARCHIVE_BIN);	
				});
			
			// replace drop here with the archived media
			//$(this).html('<a href="#archieved_videos_container">Archived Media</a>');
				$(this).children().text('dropped!').fadeOut(2000, function() {
					// Animation complete.
					$('#archieved_bin_trigger').html('<a href="#archieved_videos_container">Archived Media</a>');
				  });
			}
		});
		
		
		$('.parent_bin li').hover(function(){
			if(!$(this).hasClass('li_timeline')){
				if(!$('#timeline_videos_container').hasClass('width_400')){
						$('.timeline_videos_container li').css('float','left'); 
						$('#timeline_videos_container').addClass('width_400');
				}
			}
			},function(){
				$('#timeline_videos_container').removeClass('width_400');
				$('.timeline_videos_container li').css('float','none');
				
		});
		
				
		// when timeline receives item
		$("#timeline_videos_container").sortable({
			over: function(){
				//alert('in')
				$('#timeline_videos_container').addClass('timeline_border');
				$('#timeline_videos_container').removeClass('width_400');
				//alert('fgd');
			},
			out: function(){
				$('#timeline_videos_container').removeClass('timeline_border');
				$('#timeline_videos_container').removeClass('width_400');
			},
			stop: function(event,ui){
				 change_in_timeline();
			},
			receive: function(event, ui) {
				
				$('#timeline_videos_container').removeClass('timeline_border');
				// find whether item is cloned item
				var is_clone = ui.sender.hasClass('parent_bin')==true ? 0 : 1;
				var video_id = ui.item.attr('vid');
				var duration = ui.item.attr('duration');
				var path = ui.item.attr('path');
				var error = 0;
				
				
				// duration on timeline
				drag_video_duration = convert_to_sec(duration);			
				
				var timeline_duration = 0;
				$('.timeline li').each(function(index) {
					  duration_li = convert_to_sec($('.timeline #'+this.id).attr('duration'));
					  timeline_duration = Number(timeline_duration) + Number(duration_li);
				 });
				 
				 
				// clone =
				// $(this).find('.clone_fields_container:first').clone();
				if(MAXIMUM_TIME_DURATION < timeline_duration){
					alert('Duration exceeded from 24 hours!');
					var orginal_id = ui.item.attr('id');
					var dynamic_id = 'clone'+counter;
					ui.item.attr('id',dynamic_id);	
					ui.item.attr('id');			
					$('#'+orginal_id).remove();
					counter++;
					timeline_duration =  timeline_duration - drag_video_duration;
					error = 1;
				}
												
				if(!error){
					// make clone in current video if video from new video
					if(!is_clone && ui.sender.attr('id')){
						var item_text = ui.item.html();
						
						// switching the ids
						var item_id = ui.item.attr('id');
					    var dynamic_id = 'clone'+counter;
					    ui.item.attr('id',dynamic_id);
						counter++;
						
						// create the element and copy it to the current bin
						new_item = $('<li class="draggable_current ui-draggable" path="'+path+'" id="'+item_id+'" vid="'+video_id+'" duration="'+duration+'">'+item_text+'</li>');
						new_item.draggable({
							connectToSortable: "#archieved_videos_container,#timeline_videos_container,.acc_trigger",
							helper: "clone",
							revert: "invalid"
						})
						// $('#current_videos_container').append(new_item);
						$('#current_videos_container').prepend(new_item);
						$('#'+dynamic_id).attr('class','li_timeline');

						// set the video to the bin
						change_in_timeline();
						set_bin(video_id,CURRENT_BIN);
						
					}else if(is_clone){
						// switching of ids
						var orginal_id = ui.item.attr('id');
						var dynamic_id = 'clone'+counter;
						ui.item.attr('id',dynamic_id);
						var item_text = ui.item.html();
						counter++;
						
						// switching of the listing
						new_item = $('<li class="li_timeline" id="'+orginal_id+'" vid="'+video_id+'" path="'+path+'" duration="'+duration+'" >'+item_text+'</li>');
						$('#'+orginal_id).replaceWith(new_item);
					}
					
				 }
			}	
		})
				
		// on video close button click
		$('.video_close').live('click',function(event){
			     // confirm the deletion
				 var ans;
				 ans=window.confirm('Are you sure to delete the video ?');            
				 if (ans!=true)
				 {
					return;
				 }
				 
				var id = $(this).parent().attr('id');
											
				// check whether deletion action for timeline or any other bin
				if($(this).parent().hasClass('li_timeline')){
					delete_from_timeline(id);
					event.stopPropagation();
				}else{
					delete_video(id);
					event.stopPropagation();
				}
		});
		
	});
	
	// delete video from timeline
	function delete_from_timeline(id){
		
		var video_id = $('#'+id).attr('vid');
		var monitor_id = $('#monitor_id').val();
		var order = $("#"+id).index();
						
		$.ajax({
		  url: baseUrl + '/videos/removeFromTimeline',
		  type: "POST",
		  data: {video_id: video_id,monitor_id: monitor_id,order:order},
		  async: false,
		  success: function(result){
			  $('#'+id).remove();
			  change_in_timeline();
		  }
		})
	}
	
	// moving from one bin to another
	function set_bin(video_id,bin){
		$.ajax({
		  url: baseUrl + '/videos/changeBinAjax',
		  type: "POST",
		  data: {video_id : video_id,bin : bin},
		  async: false,
		  success: function(result){
			  //alert(result);
			  refresh_bin();
		  }
		})
		return true;  
	}
	
	// set videos to timeline
	function set_timeline(string){
		//alert(string)
		$.ajax({
		  url: baseUrl + "/videos/setTimelineAjax",
		  type: "POST",
		  data:{data : string},
		  async: true,
		  success: function(result){
			// alert(result);
		  },
		  error: function(jqXHR, textStatus, errorThrown){
			alert(errorThrown);  
		  },
		})
	}
	
	// when any chage occur in timeline
	function change_in_timeline(){
		
		 $('#timeline_videos_container').removeClass('width_400');
		 var string = '';
		 monitor_id = $('#monitor_id').val();
		 var dhtml= '';
		 
		 $('.timeline li').each(function(index) {
		  video_id = $('.timeline #'+this.id).attr('vid');
		  dhtml = dhtml + '<a href="' + $('.timeline #'+this.id).attr('path') + '"></a>';
		  if(video_id){
				string = string + video_id + '%';
		  }
		 });		 
		 $('.clips').html(dhtml);
		 string = string + '#' + monitor_id;
		 set_timeline(string);
		 refresh_scroller();
		 update_timeline_duration(); 
	}
	
	// convert the hours to seconds
	function convert_to_sec(time_stamp){
		    var array=time_stamp.split(":");
			var sec = array[2] ? array[2] : 0;
			var min = array[1] ? array[1] : 0;
			var hour = array[0] ? array[0] : 0;
			return parseInt(hour*3600) + parseInt(min*60) + parseInt(sec);
	}
	
	// delete the video
	function delete_video(id){
		var video_id = $('#'+id).attr('vid');
		$.ajax({
		  url: baseUrl + '/videos/removeVideoAjax',
		  type: "POST",
		  data: {video_id: video_id},
		  async: false,
		  success: function(result){
			  // alert(result);
			  $('#'+id).remove();
			  $('li[vid="'+video_id+'"]').remove();
			  change_in_timeline();
		  }
		})
	}
	
	// convert seconds to hours
	function rectime(secs) {
		
		var hr = Math.floor(secs / 3600);
		var min = Math.floor((secs - (hr * 3600))/60);
		var sec = secs - (hr * 3600) - (min * 60);
		
		if (hr < 10) {hr = "0" + hr; }
		if (min < 10) {min = "0" + min;}
		if (sec < 10) {sec = "0" + sec;}
		if (hr < 1) {hr = "00";}
		return hr + ':' + min + ':' + sec;
    }
	
	// refresh bin
	function refresh_bin(){
		
			// initialize counters
			var new_container_length = 0;
			var current_container_length = 0;
			var archived_container_length = 0;
		
		
		// count the videos in bin
		$('#new_videos_container li').each(function(){
			if(!$(this).is(":hidden") ){
				new_container_length++;
			}
		});
		
		$('#current_videos_container li').each(function(){
			if(!$(this).is(":hidden") ){
				current_container_length++;	
			}
			
		});
		$('#archieved_videos_container li').each(function(){
			if(!$(this).is(":hidden") ){
				archived_container_length++;
			}
		});
		
		// get more videos
		if($('#new_bin_trigger').hasClass('active')){
			
			if(new_container_length < 1){
				if (document.getElementById('new_load_more')) {
				  $('#new_load_more').click();
				} else {
				  $('#new_videos_container').before('<br><br><center> No video </center>')
				}	
			}
		}else if($('#current_bin_trigger').hasClass('active')){
			if(current_container_length < 1){
				if (document.getElementById('current_load_more')) {
				  $('#current_load_more').click();
				} else {
				  $('#current_load_more').before('<br><br><center> No video </center>')
				}	
			}
		}else if($('#archieved_bin_trigger').hasClass('active')){
			if(archived_container_length < 1){
				if (document.getElementById('archived_load_more')) {
				  $('#archived_load_more').click();
				} else {
				  $('#archived_load_more').before('<br><br><center> No video </center>')
				}	
			}
		}
	}
	
	function get_current_videos(ul_id){
		if('new_load_more' == ul_id){
			var bin_ul = 'new_videos_container';	
		}else if('archived_load_more' == ul_id){
			var bin_ul = 'current_videos_container';	
		}else{
			var bin_ul = 'archieved_videos_container';	
		}
		var counter = 0;
		$('#'+bin_ul+ ' li').live('each',function(){
			if(!$(this).is(":hidden") ){
				counter++;
			}
		});
		return counter;
	}
	
	// update the timeline duration
	function update_timeline_duration(){
		var timeline_duration = 0;
		
		$('.timeline li').each(function(index) {
			duration_li = convert_to_sec($('.timeline #'+this.id).attr('duration'));
			timeline_duration = Number(timeline_duration) + Number(duration_li);
		 });
			
		$('#timeline_duration').text('Timeline Duration ' +rectime(timeline_duration));
	}
	
	// refresh the scroller
	function refresh_scroller(){
		 	$('#timeline_videos_container').removeClass('width_400');
			var container = $('div.sliderGallery');
            var ul = $('ul', container);
            var itemsWidth = ul.innerWidth() - container.outerWidth();
			
			if(itemsWidth > 1){
			  $('.slider_container').show();
			  $('.slider').show();
			   $('#disable_scroller').hide();
			  $('.video_wrapper').css('min-height','500px');
			}else{
			  $('.slider_container').hide();
			  $('.slider').hide();
			   $('#disable_scroller').show();
			  $('#timeline_videos_container').css('left','0px');
			  $('.video_wrapper').css('min-height','437px');	
			}
            
            $('.slider', container).slider({
                min: 0,
                max: itemsWidth,
                handle: '.handle',
                stop: function (event, ui) {
                    ul.animate({'left' : ui.value * -1}, 500);
                },
                slide: function (event, ui) {
                    ul.css('left', ui.value * -1);
                }
            });	
	}
	window.onload = function(){
			$('#timeline_videos_container').removeClass('width_400');
            var container = $('div.sliderGallery');
            var ul = $('ul', container);
            var itemsWidth = ul.innerWidth() - container.outerWidth();
            
			if(itemsWidth > 1){
			  $('.slider_container').show();
			  $('.slider').show();
			  $('#disable_scroller').hide();
			  $('.video_wrapper').css('min-height','500px');
			}else{
			  $('.slider_container').hide();
			  $('#disable_scroller').show();
			  //$('.slider').hide();			  
			  $('.timeline_videos_container').css('left','0px');
			  $('.video_wrapper').css('min-height','431px');	
			}
			
            $('.slider', container).slider({
                min: 0,
                max: itemsWidth,
                handle: '.handle',
                stop: function (event, ui) {
                    ul.animate({'left' : ui.value * -1}, 500);
                },
                slide: function (event, ui) {
                    ul.css('left', ui.value * -1);
                }
            });
     }; 	
	 
	 // GET MORE VIDEOS FROM DATABASE
	 $('.load_more').live('click',function(event){
		 		
				// get set variables
				var _this = $(this);
				var items = $(this).attr('items');
				var max_items = $(this).attr('max_items');
				var bin = $(this).attr('bin');
				var ul_class = $(this).attr('ul_class');
				var monitor_id = $('#monitor_id').val();
				$(this).after('<img alt="" src="'+baseUrl+'/img/loading.gif" class="loader">');
				$(this).attr('disabled', 'disabled');
				
				var background = $(this).css('background');
				$(this).css('border','solid 1px #999');
				
				// get current items
				items = get_current_videos(this.id);	
				     
							
				// send ajax request and
				$.ajax({
				  url: baseUrl + '/videos/getVideosAjax',
				  type: "POST",
				  data: {items: items,max_items: max_items,bin: bin,monitor_id: monitor_id},
				  async: false,
				  success: function(response){
					  
					 				  
					  // remove the content
					  $('.loader').remove();
					  _this.removeAttr('disabled');
					  _this.css('background',background);
					 
					  // parse the response
					  var data = JSON.parse(response);
					  var content = data.html;
					  var total_results = data.total_results;	
					  var has_more_result = data.has_more_result;
					  		  		  					  
					  $('.'+ul_class).prepend(content);
					  // _this.before(content);
					  
					  // remove or update the load more suggestion
					  if(!has_more_result){
						 _this.remove();
					  }else{
						new_items = parseInt(items)+ parseInt(total_results);
						_this.attr('items',new_items);  
					  } 
					  // $('.loader').hide();
				  }
				})
		});
			