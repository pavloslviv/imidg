<!-- #sensei starts here-->
<div id="senseicourses" class="bpanel-content">
  	<!-- .bpanel-main-content starts here-->
    <div class="bpanel-main-content">
        <ul class="sub-panel">
            <li><a href="#general"><?php _e("General",'dt_themes');?></a></li>
        </ul>
        
        <div id="general" class="tab-content">
        	<div class="bpanel-box">
			<?php  if( class_exists('WooThemes_Sensei') ) : ?>

				<!-- Course Page -->
            	<div class="box-title"><h3><?php _e('Course Detail','dt_themes');?></h3></div>
                <div class="box-content">
                	<!-- Course Detail Page Layout -->
                    <h6><?php _e('Layout','dt_themes');?></h6>
                    <p class="note no-margin"> <?php _e("Choose the Course page layout","dt_themes");?></p>
                    <div class="hr_invisible"> </div>
                    <div class="bpanel-option-set">
                    	<ul class="bpanel-post-layout bpanel-layout-set" id="course-layout">
                        <?php $layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>'right-sidebar','both-sidebar'=>'both-sidebar');
							  $v = 	dttheme_option('sensei',"course-layout");
							  $v = !empty($v) ? $v : "content-full-width";
							  
                        foreach($layout as $key => $value):
                            $class = ( $key ==  $v ) ? " class='selected' " : "";
                            echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' alt='' /></a></li>";
                        endforeach; ?>
                        </ul>
                        <input name="mytheme[sensei][course-layout]" type="hidden" value="<?php echo $v;?>"/>
                    </div><!-- Course Detail Page Layout End-->
					 <?php 
                     $sb_layout = dttheme_option('sensei',"course-layout");
                     $sidebar_both = $sidebar_left = $sidebar_right = '';
                     if($sb_layout == 'content-full-width') {
                        $sidebar_both = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-left-sidebar') {
                        $sidebar_right = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-right-sidebar') {
                        $sidebar_left = 'style="display:none;"'; 
                     } 
                     ?>
                    <div id="bpanel-widget-area-options" <?php echo 'class="course-layout" '.$sidebar_both;?>>
                        
                        <div id="left-sidebar-container" class="bpanel-page-left-sidebar" <?php echo $sidebar_left; ?>>
                            <!-- 2. Every Where Sidebar Left Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Left','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-left-sidebar-for-course-layout'); ?>
                            </div><!-- Every Where Sidebar Left End-->
                        </div>
    
                        <div id="right-sidebar-container" class="bpanel-page-right-sidebar" <?php echo $sidebar_right; ?>>
                            <!-- 3. Every Where Sidebar Right Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Right','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-right-sidebar-for-course-layout'); ?>
                            </div><!-- Every Where Sidebar Right End-->
                        </div>
                        
                    </div>                    
                    
                </div><!-- Course Page -->

				<!-- Lesson Page -->
            	<div class="box-title"><h3><?php _e('Lesson Detail','dt_themes');?></h3></div>
                <div class="box-content">
                	<!-- Lesson Detail Page Layout -->
                    <h6><?php _e('Layout','dt_themes');?></h6>
                    <p class="note no-margin"> <?php _e("Choose the Lesson page layout","dt_themes");?></p>
                    <div class="hr_invisible"> </div>
                    <div class="bpanel-option-set">
                    	<ul class="bpanel-post-layout bpanel-layout-set" id="lesson-layout">
                        <?php $layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>'right-sidebar','both-sidebar'=>'both-sidebar');
							  $v = 	dttheme_option('sensei',"lesson-layout");
							  $v = !empty($v) ? $v : "content-full-width";
							  
                        foreach($layout as $key => $value):
                            $class = ( $key ==  $v ) ? " class='selected' " : "";
                            echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' alt='' /></a></li>";
                        endforeach; ?>
                        </ul>
                        <input name="mytheme[sensei][lesson-layout]" type="hidden" value="<?php echo $v;?>"/>
                    </div><!-- Lesson Detail Page Layout End-->
					 <?php 
                     $sb_layout = dttheme_option('sensei',"lesson-layout");
                     $sidebar_both = $sidebar_left = $sidebar_right = '';
                     if($sb_layout == 'content-full-width') {
                        $sidebar_both = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-left-sidebar') {
                        $sidebar_right = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-right-sidebar') {
                        $sidebar_left = 'style="display:none;"'; 
                     } 
                     ?>
                    <div id="bpanel-widget-area-options" <?php echo 'class="lesson-layout" '.$sidebar_both;?>>
                        
                        <div id="left-sidebar-container" class="bpanel-page-left-sidebar" <?php echo $sidebar_left; ?>>
                            <!-- 2. Every Where Sidebar Left Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Left','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-left-sidebar-for-lesson-layout'); ?>
                            </div><!-- Every Where Sidebar Left End-->
                        </div>
    
                        <div id="right-sidebar-container" class="bpanel-page-right-sidebar" <?php echo $sidebar_right; ?>>
                            <!-- 3. Every Where Sidebar Right Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Right','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-right-sidebar-for-lesson-layout'); ?>
                            </div><!-- Every Where Sidebar Right End-->
                        </div>
                        
                    </div>                    
                    
                </div><!-- Lesson Page -->

				<!-- Quiz Page -->
            	<div class="box-title"><h3><?php _e('Quiz Detail','dt_themes');?></h3></div>
                <div class="box-content">
                	<!-- Quiz Detail Page Layout -->
                    <h6><?php _e('Layout','dt_themes');?></h6>
                    <p class="note no-margin"> <?php _e("Choose the Quiz page layout","dt_themes");?></p>
                    <div class="hr_invisible"> </div>
                    <div class="bpanel-option-set">
                    	<ul class="bpanel-post-layout bpanel-layout-set" id="quiz-layout">
                        <?php $layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>'right-sidebar','both-sidebar'=>'both-sidebar');
							  $v = 	dttheme_option('sensei',"quiz-layout");
							  $v = !empty($v) ? $v : "content-full-width";
							  
                        foreach($layout as $key => $value):
                            $class = ( $key ==  $v ) ? " class='selected' " : "";
                            echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' alt='' /></a></li>";
                        endforeach; ?>
                        </ul>
                        <input name="mytheme[sensei][quiz-layout]" type="hidden" value="<?php echo $v;?>"/>
                    </div><!-- Quiz Detail Page Layout End-->
					 <?php 
                     $sb_layout = dttheme_option('sensei',"quiz-layout");
                     $sidebar_both = $sidebar_left = $sidebar_right = '';
                     if($sb_layout == 'content-full-width') {
                        $sidebar_both = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-left-sidebar') {
                        $sidebar_right = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-right-sidebar') {
                        $sidebar_left = 'style="display:none;"'; 
                     } 
                     ?>
                    <div id="bpanel-widget-area-options" <?php echo 'class="quiz-layout" '.$sidebar_both;?>>
                        
                        <div id="left-sidebar-container" class="bpanel-page-left-sidebar" <?php echo $sidebar_left; ?>>
                            <!-- 2. Every Where Sidebar Left Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Left','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-left-sidebar-for-quiz-layout'); ?>
                            </div><!-- Every Where Sidebar Left End-->
                        </div>
    
                        <div id="right-sidebar-container" class="bpanel-page-right-sidebar" <?php echo $sidebar_right; ?>>
                            <!-- 3. Every Where Sidebar Right Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Right','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-right-sidebar-for-quiz-layout'); ?>
                            </div><!-- Every Where Sidebar Right End-->
                        </div>
                        
                    </div>                    
                    
                </div><!-- Quiz Page -->

				<!-- Course Category Page -->
            	<div class="box-title"><h3><?php _e('Archive Pages','dt_themes');?></h3></div>
                <div class="box-content">
                	<!-- Course Category Page Layout -->
                    <h6><?php _e('Layout','dt_themes');?></h6>
                    <p class="note no-margin"> <?php _e("Choose the archieve page layout Style","dt_themes");?></p>
                    <div class="hr_invisible"> </div>
                    <div class="bpanel-option-set">
                    	<ul class="bpanel-post-layout bpanel-layout-set" id="course-category-layout">
                        <?php $layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>'right-sidebar','both-sidebar'=>'both-sidebar');
							  $v = 	dttheme_option('sensei',"course-category-layout");
							  $v = !empty($v) ? $v : "content-full-width";
							  
                        foreach($layout as $key => $value):
                            $class = ( $key ==  $v ) ? " class='selected' " : "";
                            echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' alt='' /></a></li>";
                        endforeach; ?>
                        </ul>
                        <input name="mytheme[sensei][course-category-layout]" type="hidden" value="<?php echo $v;?>"/>
                    </div><!-- Course Category Page Layout End-->
					 <?php 
                     $sb_layout = dttheme_option('sensei',"course-category-layout");
                     $sidebar_both = $sidebar_left = $sidebar_right = '';
                     if($sb_layout == 'content-full-width') {
                        $sidebar_both = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-left-sidebar') {
                        $sidebar_right = 'style="display:none;"'; 
                     } elseif($sb_layout == 'with-right-sidebar') {
                        $sidebar_left = 'style="display:none;"'; 
                     } 
                     ?>
                    <div id="bpanel-widget-area-options" <?php echo 'class="course-category-layout" '.$sidebar_both;?>>
                        
                        <div id="left-sidebar-container" class="bpanel-page-left-sidebar" <?php echo $sidebar_left; ?>>
                            <!-- 2. Every Where Sidebar Left Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Left','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-left-sidebar-for-course-category-layout'); ?>
                            </div><!-- Every Where Sidebar Left End-->
                        </div>
    
                        <div id="right-sidebar-container" class="bpanel-page-right-sidebar" <?php echo $sidebar_right; ?>>
                            <!-- 3. Every Where Sidebar Right Start -->
                            <div id="page-commom-sidebar" class="bpanel-sidebar-section custom-box">
                                <h6><?php _e('Disable Sensei Course Every Where Sidebar Right','dt_themes');?></label></h6>
                                <?php dttheme_switch("",'sensei','disable-senseicourse-everywhere-right-sidebar-for-course-category-layout'); ?>
                            </div><!-- Every Where Sidebar Right End-->
                        </div>
                        
                    </div>                    
                    
                </div><!-- Course Category Page -->

<?php 	else: ?>
				<div class="box-title"><h3><?php _e('Warning','dt_themes');?></h3></div>
                <div class="box-content"><p class="note"><?php _e("You have to install and activate the Sensei plugin to use this module ..",'dt_themes');?></p></div>
<?php   endif;?>  
              
            </div><!--.bpanel-box End -->
        </div>

    </div><!-- .bpanel-main-content ends here-->    
</div><!-- #sensei end-->