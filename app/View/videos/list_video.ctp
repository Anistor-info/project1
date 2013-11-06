<?php 
echo $this->Html->css('jplayer.blue.monday'); 
echo $this->Html->script('flowplayer-3.2.10.min'); 
echo $this->Html->script('flowplayer.playlist-3.2.10');
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui.min');
echo $this->Html->script('video_gallery'); 
?> 

<input type="hidden" id="monitor_id" value="<?php echo $monitor_id; ?>" />
<div class="content_wrapper">
<div class="inner">

<?php echo $this->element('messages'); ?>
<?php echo $this->Global->get_bredcrum('add_monitor'); ?>

<div class="monitor_wrapper">
    <div align="center" class="monitor_txtbox">
      <h1>MONITOR <img width="58" height="43" align="top" src="<?php echo $this->webroot; ?>img/one-icon.png"> MEDIA</h1>
    </div>
   
    <div class="video_wrapper ">
    <!-- configure entries inside playlist using standard HTML -->
    <div class="clips">
    
    <?php if($selected_video){ ?>
    <a href="<?php echo $user_dir . $current_video; ?>" style="float:left; display:none"></a> 
    <?php $loop_start = 0; }else{ ?>
    <a href="<?php echo $user_dir . $current_video; ?>" style="float:left; display:none"></a> 
    <?php $loop_start = 1; }?>
    <?php for( $i=$loop_start; $i < count($timeline_videos); $i++){ ?>
    <?php $data = $timeline_videos[$i]['videos']; ?> 
    <?php $video_path = $user_dir . $data['path']; ?>
        <!-- single playlist entry -->
        <a href="<?php echo $video_path; ?>" class="player" style="float:left; display:none"></a>
    <?php } ?>
    </div>
    <a class="player" href="<?php echo $user_dir . $current_video; ?>" style="display:block;width:503px;height:290px; margin:0 auto;"  id="player"></a> 
    <script> i = 1; var swf = <?php echo $this->webroot; ?>+'app/webroot/js/flowplayer-3.2.11.swf';</script>
    <?php echo $this->Html->script('flowplayer_setting');  ?>
    
    <div class="sliderGallery">
    <ul id="timeline_videos_container" bin="timeline" class='parent_bin timeline'>
    <?php for( $i=0; $i < count($timeline_videos); $i++){ ?>
    <?php $data = $timeline_videos[$i]['videos']; ?>
      <li id="t<?php echo $i; ?>" class="li_timeline" vid="<?php echo $data['id']; ?>" path="<?php echo $user_dir.$data['path']; ?>" duration="<?php echo $data['duration']; ?>">
      <div class="hide-button video_close"></div>
        <?php if($data['thumbnail']){ ?>
            <?php $image_path = $user_dir . $data['thumbnail']; ?>
            <a href="#">
            <img class="DragBox" id="Item<?php echo $i;?>" overclass="OverDragBox" dragclass="DragDragBox" src="<?php echo $image_path; ?>" alt="<?php echo $data['name']; ?>" width="96" height="72" />
            </a>
        <?php }else{ ?>
            <?php echo $this->Html->link($this->Html->image('no-thumbnail-available.jpg', array("alt" =>$data['name'],'height'=>150,'width'=>150)),"listVideo/".$data['id'].'/'.$monitor_id,array('escape' => false)); ?>
        <?php } ?>
        <span><?php echo $data['name']; ?></span>
        </li>
    <?php } ?>
    </ul>
    
<div class="slider_container">
 <div class="slider"></div>
 </div>
</div>
<div id="disable_scroller"></div>
</div>
</div>

<div class="sidebar">
<div class="monitor_txtbox">
    <h5> MEDIA BIN </h5>
    </div>
    <div class="main_acc_container">

	<h6 id="new_bin_trigger" class="acc_trigger" drop="new_videos_container"><a href="#">New Media</a></h6>
	<div class="acc_container block">
     	 <?php 
		  if(count($new_videos) > MAX_ITEMS_IN_BIN){
			$number_of_items = MAX_ITEMS_IN_BIN;  
		  }else{
			$number_of_items = count($new_videos);  
		  }
		 ?>
         
         <?php if($number_of_items < 1){ ?>
        	<br /><br />
            <center> No video </center>
            <br /><br />
         <?php } ?>
         
        <ul id="new_videos_container" bin="new" class='parent_bin new new_b'>  
            <?php for( $i=0; $i < $number_of_items; $i++){ ?>
			<?php $data = $new_videos[$i]['Video']; ?>
              <li id="n<?php echo $i?>" class="draggable_new" path="<?php echo $user_dir.$data['path']; ?>" vid="<?php echo $data['id']; ?>" duration="<?php echo $data['duration']; ?>">
              	<div class="hide-button video_close"></div>
                <?php if($data['thumbnail']){ ?>
                    <?php $image_path = $user_dir . $data['thumbnail']; ?>
                    <a href="#">
                    <img class="DragBox" id="Item<?php echo $i;?>" overclass="OverDragBox" dragclass="DragDragBox" src="<?php echo $image_path; ?>" alt="<?php echo $data['name']; ?>" width="96" height="72" />
                    </a>
                <?php }else{ ?>
                    <?php echo $this->Html->link($this->Html->image('no-thumbnail-available.jpg', array("alt" => $data['name'],'height'=>150,'width'=>150)),"listVideo/".$data['id'].'/'.$monitor_id,array('escape' => false)); ?>
                <?php } ?>
                 <span style="text-align:center"><?php echo $data['name']; ?></span>
                </li>
            <?php } ?>
       </ul> 
       <?php if(count($new_videos) > MAX_ITEMS_IN_BIN){ ?>
        <button type="button" id="new_load_more" items="<?php echo $number_of_items?>" max_items="<?php echo $number_of_items?>" bin="NEW_BIN" class="load_more loadbtn" ul_class="new_b">
    <span class="yt-uix-button-content">Load more videos </span>
        </button>
        <?php } ?>    
	</div>
	
	<h6 id="current_bin_trigger" class="acc_trigger" drop="current_videos_container"><a href="#current_videos_container">Current Media</a></h6>
	<div class="acc_container block">
       <ul id="current_videos_container" bin="current" class='test1 parent_bin current'>
		<?php 
            if(count($current_videos) > MAX_ITEMS_IN_BIN){
            	$number_of_items = MAX_ITEMS_IN_BIN;  
            }else{
            	$number_of_items = count($current_videos);  
            }
        ?>
		<?php for( $i=0; $i < $number_of_items; $i++){ ?>
			<?php $data = $current_videos[$i]['Video']; ?>
              <li id="cq<?php echo $i?>" class="draggable_current" path="<?php echo $user_dir.$data['path']; ?>" vid="<?php echo $data['id']; ?>" duration="<?php echo $data['duration']; ?>" >
              	<div class="hide-button video_close"></div>
                <?php if($data['thumbnail']){ ?>
                    <?php $image_path = $user_dir . $data['thumbnail']; ?>
                    <a href="#">
                    <img class="DragBox" id="Item<?php echo $i;?>" overclass="OverDragBox" dragclass="DragDragBox" src="<?php echo $image_path; ?>" alt="<?php echo $data['name']; ?>" width="96" height="72" />
                    </a>
                <?php }else{ ?>
                    <?php echo $this->Html->link($this->Html->image('no-thumbnail-available.jpg', array("alt" => "video" ,'height'=>150,'width'=>150)),"listVideo/".$data['id'].'/'.$monitor_id,array('escape' => false)); ?>
                <?php } ?>
                <span style="text-align:center"><?php echo $data['name']; ?></span>
               </li>
           <?php } ?>
    	</ul>
         <?php if(count($current_videos) > MAX_ITEMS_IN_BIN){ ?>
            <button type="button" id="current_load_more" items="<?php echo $number_of_items?>" max_items="<?php echo $number_of_items?>" bin="CURRENT_BIN" class="load_more loadbtn" ul_class="current">
        	<span class="yt-uix-button-content">Load more videos </span>
        	</button>
        <?php } ?> 
	</div>
	
	<h6 id="archieved_bin_trigger" class="acc_trigger" drop="archieved_videos_container"><a href="#archieved_videos_container">Archived Media</a></h6>
	<div class="acc_container block">
		<ul id="archieved_videos_container" bin="archived" class='parent_bin archived'>
			  <?php 
			  if(count($archived_videos) > MAX_ITEMS_IN_BIN){
				$number_of_items = MAX_ITEMS_IN_BIN;  
			  }else{
				$number_of_items = count($archived_videos);  
			  }
			  ?>
			  <?php for( $i=0; $i < $number_of_items; $i++){ ?>
				<?php $data = $archived_videos[$i]['Video']; ?>
                  <li id="a<?php echo $i; ?>" class="draggable_archived" vid="<?php echo $data['id']; ?>" path="<?php echo $user_dir.$data['path']; ?>" duration="<?php echo $data['duration']; ?>">
                  	<div class="hide-button video_close"></div>
                    <?php if($data['thumbnail']){ ?>
                        <?php $image_path = $user_dir . $data['thumbnail']; ?>
                        <a href="#">
                        <img class="DragBox" id="Item<?php echo $i;?>" overclass="OverDragBox" dragclass="DragDragBox" src="<?php echo $image_path; ?>" alt="<?php echo $data['name']; ?>" width="96" height="72" />
                        </a>
                    <?php }else{ ?>
                        <?php echo $this->Html->link($this->Html->image('no-thumbnail-available.jpg', array("alt" => $data['name'],'height'=>150,'width'=>150)),"listVideo/".$data['id'].'/'.$monitor_id,array('escape' => false)); ?>
                    <?php } ?>
                   <span style="text-align:center"><?php echo $data['name']; ?></span>
                    </li>
               <?php } ?>
		</ul> 
           <?php if(count($archived_videos) > MAX_ITEMS_IN_BIN){ ?>
           <button type="button" id="archived_load_more" items="<?php echo $number_of_items?>" max_items="<?php echo $number_of_items?>" bin="ARCHIVED_BIN" class="load_more loadbtn" ul_class="archived">
            <span class="yt-uix-button-content">Load more videos </span>
            </button>
           <?php } ?>       
	</div>
    </div>
    
      </div>
    </div>
		
    </div>
</div>
</div>
