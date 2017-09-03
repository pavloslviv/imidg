<?php
global $post;

$event_args = array(	'post_type' 		=> array( 'tribe_events' ),
						'posts_per_page' 	=> -1,
						'orderby'         	=> 'title',
						'order'           	=> 'ASC',
						'suppress_filters' 	=> 0
					);
$events_array = get_posts( $event_args );

$dt_course_event_id = get_post_meta( $post->ID, 'dt-course-event-id', true );

?>
<p><?php _e( 'Choose course event for this course.', 'dt_themes' ); ?></p>
<select name="dt-course-event-id[]" id="dt-course-event-id" class="dt-chosen-select" multiple>
    <option value=""><?php _e( 'None', 'dt_themes' ); ?></option>
    <?php
    foreach ( $events_array as $event ) {
		$sel_str = '';
		if(!empty($dt_course_event_id) && in_array($event->ID, $dt_course_event_id)) {
			$sel_str = 'selected="selected"'; 
		}
		?>
		<option value="<?php echo $event->ID; ?>" <?php echo $sel_str; ?>><?php _e( $event->post_title, 'dt_themes' ); ?></option>
		<?php
    }
    ?>
</select>