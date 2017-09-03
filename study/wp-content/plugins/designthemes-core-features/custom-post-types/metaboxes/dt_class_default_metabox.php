<?php
global $post;
$post_id = $post->ID;

wp_nonce_field( 'dtcore_plugin_class_metabox', 'dtcore_plugin_class_metabox_nonce' );
?>

<div class="custom-box">

	<div class="column one-half">
    
        <div class="column one-third">
            <label><?php _e('Class Type','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php
            $class_type = get_post_meta ( $post->ID, 'dt-class-type', TRUE );
			
			if($class_type == 'onsite') {
				$onsite_hide_cls = '';
				$online_hide_cls = 'style="display:none;"';
			} else {
				$onsite_hide_cls = 'style="display:none;"';
				$online_hide_cls = '';
			}
			
            $class_types = array('online' => 'Online', 'onsite' => 'Onsite');
    
            $out = '';
            $out .= '<select id="dt-class-type" name="dt-class-type" style="width:70%;" data-placeholder="'.__('Select Class Types...', 'dt_themes').'" class="dt-chosen-select">' . "\n";
			foreach ($class_types as $class_type_key => $class_type_value){
				$out .= '<option value="' . esc_attr( $class_type_key ) . '"' . selected( $class_type_key, $class_type, false ) . '>' . esc_html( $class_type_value ) . '</option>' . "\n";
			}
            $out .= '</select>' . "\n";
            echo $out;
            ?>
            <p class="note"> <?php _e('Choose class type here.','dt_themes');?> </p>
        </div>

		<div class="hr_invisible"></div><div class="hr_invisible"></div>

	</div>
    
</div>

<div class="custom-box">

	<div class="column one-half">
    
        <div class="column one-third">
            <label><?php echo $GLOBALS['teachers-singular-label']; ?></label>
        </div>
        <div class="column two-third last">
			<?php
            $post_args = array(	'post_type' 		=> 'dt_teachers',
                                'numberposts' 		=> -1,
                                'orderby'         	=> 'title',
                                'order'           	=> 'ASC',
                                'suppress_filters'  => FALSE
                                );
            $posts_array = get_posts( $post_args );
            
            $dt_class_teacher = get_post_meta($post->ID, "dt-class-teacher", true);
            
            $out = '';
            $out .= '<select id="dt-class-teacher" name="dt-class-teacher" style="width:70%;" data-placeholder="'.sprintf(esc_html__('Select %s', 'dt_themes'), $GLOBALS['teachers-singular-label']).'" class="dt-chosen-select">' . "\n";
            $out .= '<option value="">'.__('None', 'dt_themes').'</option>';
            if ( count( $posts_array ) > 0 ) {
                foreach ($posts_array as $post_item){
                    $out .= '<option value="' . esc_attr( $post_item->ID ) . '"' . selected( $post_item->ID, $dt_class_teacher, false ) . '>' . esc_html( $post_item->post_title ) . '</option>' . "\n";
                }
            }
            $out .= '</select>' . "\n";
            echo $out;
            ?>
            <p class="note"> <?php echo sprintf(esc_html__('Assign %s to this class.', 'dt_themes'), $GLOBALS['teachers-singular-label']); ?> </p>
        </div>

		<div class="hr_invisible"></div><div class="hr_invisible"></div>

	</div>
    
</div>

<div class="custom-box">

	<div class="column one-half">
        <div class="column one-third">
            <label><?php _e('Featured Class','dt_themes');?></label>
        </div>
        <div class="column two-third last">
			<?php
			$dt_class_featured = get_post_meta($post_id, "dt-class-featured", true);
            $switchclass = ($dt_class_featured == 'true') ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked = ($dt_class_featured == 'true') ? ' checked="checked"' : '';
            ?>
            <div data-for="dt-class-featured" class="dt-checkbox-switch <?php echo $switchclass;?>"></div>
            <input id="dt-class-featured" class="hidden" type="checkbox" name="dt-class-featured" value="true" <?php echo $checked;?> />
            <p class="note"> <?php _e("Make this class as featured one.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
	<div class="column one-half last">
        <div class="column one-third">
            <label><?php _e('Sub Title','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php $dt_class_subtitle = get_post_meta ( $post_id, "dt-class-subtitle",true);?>
            <input id="dt-class-subtitle" name="dt-class-subtitle" class="large" type="text" value="<?php echo $dt_class_subtitle;?>" style="width:80%;" />
            <p class="note"> <?php _e("Add subtitle for your class here.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
</div>

<div class="custom-box">

	<div class="column one-half">
        <div class="column one-third">
            <label><?php _e('Price','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php $dt_class_price = get_post_meta($post_id, "dt-class-price", true);?>
            <input id="dt-class-price" name="dt-class-price" class="large" type="text" value="<?php echo $dt_class_price;?>" style="width:80%;" />
            <p class="note"> <?php _e("You can add price for your course here. This price will be used if your are going to use s2Member plugin for payment purpose.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
	<div class="column one-half last">
        <div class="column one-third">
            <label><?php _e('Main Tab Title','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php $dt_class_maintabtitle = get_post_meta ( $post_id, "dt-class-maintabtitle",true);?>
            <input id="dt-class-maintabtitle" name="dt-class-maintabtitle" class="large" type="text" value="<?php echo $dt_class_maintabtitle;?>" style="width:80%;" />
            <p class="note"> <?php _e("Add main tab title for your class here.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
</div>

<div class="custom-box">

	<div class="column one-half">
    
        <div class="column one-third">
            <label><?php _e('Content Options','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php
            $class_content_options_value = get_post_meta ( $post->ID, 'dt-class-content-options', TRUE );
			
			if($class_content_options_value == 'timetable') {
				$course_hide_cls = 'style="display:none;"';
				$timetable_hide_cls = '';
			} else {
				$course_hide_cls = '';
				$timetable_hide_cls = 'style="display:none;"';
			}
			
            $class_content_options = array('' => 'None', 'course' => 'Add Course', 'timetable' => 'Add Timetable');
    
            $out = '';
            $out .= '<select id="dt-class-content-options" name="dt-class-content-options" style="width:70%;" data-placeholder="'.__('Select Content Options...', 'dt_themes').'" class="dt-chosen-select">' . "\n";
			foreach ($class_content_options as $class_content_key => $class_content_value){
				$out .= '<option value="' . esc_attr( $class_content_key ) . '"' . selected( $class_content_key, $class_content_options_value, false ) . '>' . esc_html( $class_content_value ) . '</option>' . "\n";
			}
            $out .= '</select>' . "\n";
            echo $out;
            ?>
            <p class="note"> <?php _e('Choose your content type here.','dt_themes');?> </p>
        </div>

		<div class="hr_invisible"></div><div class="hr_invisible"></div>

	</div>
    
</div>

<div class="custom-box">

    <div class="column one-sixth">
        <label><?php _e('Content Title','dt_themes');?></label>
    </div>
    <div class="column five-sixth last">
        <?php $dt_class_content_title = get_post_meta($post_id, "dt-class-content-title", true);?>
        <input id="dt-class-content-title" name="dt-class-content-title" class="large" type="text" value="<?php echo $dt_class_content_title;?>" style="width:80%;" />
        <p class="note"> <?php _e("Add title for your content here.",'dt_themes');?> </p>
        <div class="clear"></div>
    </div>
    
</div>

<div class="custom-box dt-sc-course-content" <?php echo $course_hide_cls; ?>>

	<div class="column one-sixth dt-add-quiz">
    
       <label><?php _e('Add Courses','dt_themes');?></label>

	</div>
	<div class="column five-sixth last">
    
        <?php
        $courses_args = array( 'post_type' => 'dt_courses', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC', 'suppress_filters' => FALSE );
        $courses_array = get_posts( $courses_args );
		?>		
    
    	<div id="dt-class-courses-container">
        
        	<?php 
			$class_courses = get_post_meta ( $post_id, "dt-class-courses", true);
			
			$j = 0;
			if(isset($class_courses) && is_array($class_courses)) {
				foreach($class_courses as $class_course) {
				?>
					<div id="dt-course-box">
						<?php
						$out = '';
						$out .= '<select id="dt-class-courses" name="dt-class-courses[]" data-placeholder="'.__('Choose a Class...', 'dt_themes').'" class="dt-chosen-select" style="width:80%;">' . "\n";
						$out .= '<option value=""></option>';
						if ( count( $courses_array ) > 0 ) {
							foreach ($courses_array as $course){
								$out .= '<option value="' . esc_attr( $course->ID ) . '"' . selected( $course->ID, $class_course, false ) . '>' . esc_html( $course->post_title ) . '</option>' . "\n";
							}
						}
						$out .= '</select>' . "\n";
						echo $out;
						?>
						<span class="dt-remove-course">X</span>
                        <span class="fa fa-sort"></span>
					</div>
				<?php
				$j++;
				}
			}
			?>
            
        </div>
		
        <a href="#" class="dt-add-course custom-button-style"><?php _e('Add Course', 'dt_themes'); ?></a>
        
        <p class="note"> <?php _e('You can add course here.','dt_themes');?> </p>
        <p class="note"> <?php _e('Also make sure you haven\'t added the same course to other classes. System won\'t work properly if same course is assigned to more than one class.','dt_themes');?> </p>
        
    	<div id="dt-course-to-clone" class="hidden">
        
			<?php
            $out = '';
            $out .= '<select data-placeholder="'.__('Choose a Course...', 'dt_themes').'" style="width:80%;">' . "\n";
            $out .= '<option value=""></option>';
            if ( count( $courses_array ) > 0 ) {
                foreach ($courses_array as $course){
                    $out .= '<option value="' . esc_attr( $course->ID ) . '">' . esc_html( $course->post_title ) . '</option>' . "\n";
                }
            }
            $out .= '</select>' . "\n";
            echo $out;
            ?>
            <span class="dt-remove-course">X</span>
            <span class="fa fa-sort"></span>
        
        </div>
    
	</div>
    
</div>

<div class="custom-box dt-sc-timetable-content" <?php echo $timetable_hide_cls; ?>>

	<div class="column one-sixth dt-add-quiz">
    
       <label><?php _e('Add Timetable Schortcode','dt_themes');?></label>

	</div>
	<div class="column five-sixth last">
    
		<?php $class_timetable_sc = get_post_meta ( $post_id, "dt-class-timetable-sc", true); ?>
        <textarea id="dt-class-timetable-sc" name="dt-class-timetable-sc" class="large" type="text" style="width:80%; height:100px"><?php echo $class_timetable_sc; ?></textarea>
        <p class="note"> <?php _e("Add timetable shortcode here. Make sure \"Timetable Wordpress Plugin - Weekly Class Schedule\" plugin is installed and activated.",'dt_themes');?> </p>
        <div class="clear"></div>

	</div>
    
</div>

<div class="custom-box dt-sc-onsite-items" <?php echo $onsite_hide_cls; ?>>

	<div class="column one-half">
        <div class="column one-third">
            <label><?php _e('Start Date','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php $dt_class_start_date = get_post_meta ( $post_id, "dt-class-start-date",true);?>
            <input id="dt-class-start-date" name="dt-class-start-date" class="large" type="text" value="<?php echo $dt_class_start_date;?>" style="width:30%;" />
            <p class="note"> <?php _e("Choose class start date here.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
	<div class="column one-half last">
        <div class="column one-third">
            <label><?php _e('Capacity','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php $dt_class_capacity = get_post_meta ( $post_id, "dt-class-capacity",true);?>
            <input id="dt-class-capacity" name="dt-class-capacity" class="large" type="text" value="<?php echo $dt_class_capacity;?>" style="width:30%;" />
            <p class="note"> <?php _e("Add class total capacity here.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
</div>

<div class="custom-box dt-sc-onsite-items" <?php echo $onsite_hide_cls; ?>>

	<div class="column one-half">
        <div class="column one-third">
            <label><?php _e('Disable Purchases / Registration','dt_themes');?></label>
        </div>
        <div class="column two-third last">
			<?php
			$dt_class_disable_purchases_regsitration = get_post_meta($post_id, "dt-class-disable-purchases-regsitration", true);
            $switchclass = ($dt_class_disable_purchases_regsitration == 'true') ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked = ($dt_class_disable_purchases_regsitration == 'true') ? ' checked="checked"' : '';
            ?>
            <div data-for="dt-class-disable-purchases-regsitration" class="dt-checkbox-switch <?php echo $switchclass;?>"></div>
            <input id="dt-class-disable-purchases-regsitration" class="hidden" type="checkbox" name="dt-class-disable-purchases-regsitration" value="true" <?php echo $checked;?> />
            <p class="note"> <?php _e("Disable purchases / registration if total purchases / registration exceeds class capacity.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
	<div class="column one-half last">
        <div class="column one-third">
            <label><?php _e('Enable Purchases','dt_themes');?></label>
        </div>
        <div class="column two-third last">
			<?php
			$dt_class_enable_purchases = get_post_meta($post_id, "dt-class-enable-purchases", true);
            $switchclass = ($dt_class_enable_purchases == 'true') ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked = ($dt_class_enable_purchases == 'true') ? ' checked="checked"' : '';
            ?>
            <div data-for="dt-class-enable-purchases" class="dt-checkbox-switch <?php echo $switchclass;?>"></div>
            <input id="dt-class-enable-purchases" class="hidden" type="checkbox" name="dt-class-enable-purchases" value="true" <?php echo $checked;?> />
            <p class="note"> <?php _e("Enable purchase option for this class.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
</div>

<div class="custom-box dt-sc-onsite-items" <?php echo $onsite_hide_cls; ?>>

	<div class="column one-half">
        <div class="column one-third">
            <label><?php _e('Enable Registration','dt_themes');?></label>
        </div>
        <div class="column two-third last">
			<?php
			$dt_class_enable_registration = get_post_meta($post_id, "dt-class-enable-registration", true);
            $switchclass = ($dt_class_enable_registration == 'true') ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked = ($dt_class_enable_registration == 'true') ? ' checked="checked"' : '';
            ?>
            <div data-for="dt-class-enable-registration" class="dt-checkbox-switch <?php echo $switchclass;?>"></div>
            <input id="dt-class-enable-registration" class="hidden" type="checkbox" name="dt-class-enable-registration" value="true" <?php echo $checked;?> />
            <p class="note"> <?php _e("Enable registration option for this class.",'dt_themes');?> </p>
            <p class="note"> <?php _e("Note this option will be enabled only when purchase option is disabled.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
	<div class="column one-half last">
        <div class="column one-third">
            <label><?php _e('Shyllabus Preview','dt_themes');?></label>
        </div>
        <div class="column two-third last">
			<?php
			$dt_class_shyllabus_preview = get_post_meta($post_id, "dt-class-shyllabus-preview", true);
            $switchclass = ($dt_class_shyllabus_preview == 'true') ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked = ($dt_class_shyllabus_preview == 'true') ? ' checked="checked"' : '';
            ?>
            <div data-for="dt-class-shyllabus-preview" class="dt-checkbox-switch <?php echo $switchclass;?>"></div>
            <input id="dt-class-shyllabus-preview" class="hidden" type="checkbox" name="dt-class-shyllabus-preview" value="true" <?php echo $checked;?> />
            <p class="note"> <?php _e("If you don't wish to show the course detail pages for onsite courses, you can disable it. Enabling this option will only show the preview of the courses.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
</div>

<div class="custom-box dt-sc-onsite-items" <?php echo $onsite_hide_cls; ?>>
	<div class="column one-sixth">
		<label><?php _e('Address','dt_themes');?></label>
	</div>
	<div class="column five-sixth last">
		<?php $dt_class_address = get_post_meta($post_id, "dt-class-address", true);?>
		<textarea id="dt-class-address" name="dt-class-address" class="widefat"><?php echo $dt_class_address;?></textarea>
		<p class="note"> <?php _e("Add address here",'dt_themes');?> </p>
        <div class="clear"></div>
	</div>
</div>

<div class="custom-box dt-sc-onsite-items" <?php echo $onsite_hide_cls; ?>>
	<div class="column one-sixth">
		<label><?php _e('GPS Location','dt_themes');?></label>
	</div>
	<div class="column five-sixth last">
		<?php 
        $dt_class_gps = get_post_meta($post_id, "dt-class-gps", true);
        $dt_class_gps = is_array($dt_class_gps) ? $dt_class_gps : array();
        $latitude = array_key_exists("latitude", $dt_class_gps) ? $dt_class_gps['latitude'] : "";
        $longitude = array_key_exists("longitude", $dt_class_gps) ? $dt_class_gps['longitude'] : "";
        ?>
		<input id="dt-class-latitude" name="dt-class-gps[latitude]" type="text" class="small" placeholder="<?php _e("Latitude","dt_themes");?>" value="<?php echo $latitude;?>"> -
		<input id="dt-class-longitude" name="dt-class-gps[longitude]" type="text" class="small" placeholder="<?php _e("Longitude","dt_themes");?>" value="<?php echo $longitude;?>" > -
		<a href="#" class="dt-set-gps button button-primary"><?php _e( 'Click Here to get GPS Location', 'dt_themes' );?> </a>
		<p class="note alert"> <?php _e("Add GPS location here to enable map.",'dt_themes');?> </p>
        <div class="clear"></div>
	</div>
</div>

<div class="custom-box">

	<div class="column one-half">
        <div class="column one-third">
            <label><?php _e('Accessories Tab Title','dt_themes');?></label>
        </div>
        <div class="column two-third last">
            <?php $dt_class_accessories_tabtitle = get_post_meta ( $post_id, "dt-class-accessories-tabtitle",true);?>
            <input id="dt-class-accessories-tabtitle" name="dt-class-accessories-tabtitle" class="large" type="text" value="<?php echo $dt_class_accessories_tabtitle; ?>" style="width:80%;" />
            <p class="note"> <?php _e("Add title for accessories tab here.",'dt_themes');?> </p>
            <div class="clear"></div>
        </div>
    </div>
    
	<div class="column one-half last">
    </div>
    
</div>

<div class="custom-box">

	<div class="column one-sixth dt-add-quiz">
    
       <label><?php _e('Add Accessories','dt_themes');?></label>

	</div>
	<div class="column five-sixth last">
    
    	<div id="dt-class-accessories-container">
        
        	<?php 
			$class_accessories_icon = get_post_meta ( $post_id, "dt-class-accessories-icon", true);
			$class_accessories_label = get_post_meta ( $post_id, "dt-class-accessories-label", true);
			$class_accessories_value = get_post_meta ( $post_id, "dt-class-accessories-value", true);
			
			$j = 0;
			if(isset($class_accessories_value) && is_array($class_accessories_value)) {
				foreach($class_accessories_value as $class_accessory_value) {
				?>
					<div id="dt-accessory-box">
						<?php
						$out = '<input id="dt-class-accessories-icon" name="dt-class-accessories-icon[]" class="large" type="text" value="'.$class_accessories_icon[$j].'" style="width:20%;" />';
						$out .= '<input id="dt-class-accessories-label" name="dt-class-accessories-label[]" class="large" type="text" value="'.$class_accessories_label[$j].'" style="width:30%;" />';
						$out .= '<input id="dt-class-accessories-value" name="dt-class-accessories-value[]" class="large" type="text" value="'.$class_accessory_value.'" style="width:40%;" />';
						echo $out;
						?>
						<span class="dt-remove-accessory">X</span>
                        <span class="fa fa-sort"></span>
					</div>
				<?php
				$j++;
				}
			}
			?>
            
        </div>
		
        <a href="#" class="dt-add-accessory custom-button-style"><?php _e('Add Accessory', 'dt_themes'); ?></a>
        
        <p class="note"> <?php _e('You can additional items with icon, label and value here.','dt_themes');?> </p>
        <p class="note"> <?php _e('Among these three its necessary to add value atleast.','dt_themes');?> </p>
        
    	<div id="dt-accessory-to-clone" class="hidden">
        
			<?php
			$out = '<input id="dt-class-accessories-icon" name="dt-class-accessories-icon[]" placeholder="'.esc_html__('Icon', 'dt_themes').'" class="large" type="text" value="" style="width:20%;" />';
			$out .= '<input id="dt-class-accessories-label" name="dt-class-accessories-label[]" placeholder="'.esc_html__('Label', 'dt_themes').'" class="large" type="text" value="" style="width:30%;" />';
			$out .= '<input id="dt-class-accessories-value" name="dt-class-accessories-value[]" placeholder="'.esc_html__('Value', 'dt_themes').'" class="large" type="text" value="" style="width:40%;" />';
            echo $out;
            ?>
            <span class="dt-remove-accessory">X</span>
            <span class="fa fa-sort"></span>
        
        </div>
    
	</div>
    
</div>

<div class="custom-box">

	<div class="column one-sixth dt-add-quiz">
    
       <label><?php _e('Add Tabs','dt_themes');?></label>

	</div>
	<div class="column five-sixth last">
    
    	<div id="dt-class-tabs-container">
        
        	<?php 
			$class_tabs_title = get_post_meta ( $post_id, "dt-class-tabs-title", true);
			$class_tabs_content = get_post_meta ( $post_id, "dt-class-tabs-content", true);
			
			$j = 0;
			if(isset($class_tabs_content) && is_array($class_tabs_content)) {
				foreach($class_tabs_content as $class_tab_content) {
				?>
					<div id="dt-tab-box">
						<?php
						$out = '<input id="dt-class-tabs-title" name="dt-class-tabs-title[]" class="large" type="text" value="'.$class_tabs_title[$j].'" style="width:20%;" />';
						$out .= '<textarea id="dt-class-tabs-content" name="dt-class-tabs-content[]" class="large" type="text" style="width:70%; height:100px">'.$class_tab_content.'</textarea>';
						echo $out;
						?>
						<span class="dt-remove-tab">X</span>
                        <span class="fa fa-sort"></span>
					</div>
				<?php
				$j++;
				}
			}
			?>
            
        </div>
		
        <a href="#" class="dt-add-tab custom-button-style"><?php _e('Add Tab', 'dt_themes'); ?></a>
        
        <p class="note"> <?php _e('If you wish you can add additional tabs along with content for your class.','dt_themes');?> </p>
        
    	<div id="dt-tab-to-clone" class="hidden">
        
			<?php
			$out = '<input id="dt-class-tabs-title" name="dt-class-tabs-title[]" class="large" type="text" placeholder="'.esc_html__('Title', 'dt_themes').'" style="width:20%;" />';
			$out .= '<textarea id="dt-class-tabs-content" name="dt-class-tabs-content[]" class="large" type="text" placeholder="'.esc_html__('Content', 'dt_themes').'" style="width:70%; height:100px"></textarea>';
            echo $out;
            ?>
            <span class="dt-remove-tab">X</span>
            <span class="fa fa-sort"></span>
        
        </div>
    
	</div>
    
</div>