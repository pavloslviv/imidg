<!-- #import -->
<div id="importer" class="bpanel-content">

    <!-- .bpanel-main-content -->
    <div class="bpanel-main-content">
        <ul class="sub-panel"> 
            <li><a href="#tab1"><?php esc_html_e('Import Demo', 'dt_themes');?></a></li>
        </ul>
        
		<?php 
        $content = array(
            '--' => esc_html__('All', 'dt_themes'),
            'pages'  => esc_html__('Pages', 'dt_themes'),
            'posts' => esc_html__('Posts', 'dt_themes'),
            'portfolios' => esc_html__('Portfolio', 'dt_themes'),
            'contactforms' => esc_html__('Contact Forms', 'dt_themes'),
			'media' => esc_html__('Media', 'dt_themes')
        );
        
        function lms_sort( $array ) {
            asort( $array );
            return $array;
        }
        
        $lms_demos = array(
            'default' =>  array(
                'label' => 'Theme Default',
                'link' => 'http://wedesignthemes.com/themes/lms/',
				'content' => $content + array( 'courses' => esc_html('Courses', 'dt_themes'), 'lessons' => esc_html('Lessons', 'dt_themes') )
            ),
            'sensei' =>  array(
                'label' => 'Sensei',
                'link' => 'http://wedesignthemes.com/themes/lms/sensei/',
                'content' => $content + array( 'courses' => esc_html('Courses', 'dt_themes'), 'lessons' => esc_html('Lessons', 'dt_themes'), 'quizzes' => esc_html('Quizzes', 'dt_themes'), 'questions' => esc_html('Questions', 'dt_themes') )
            ),
            'scorm' =>  array(
                'label' => 'Scorm',
                'link' => 'http://wedesignthemes.com/themes/lms/scorm/',
                'content' => $content
            ),
            'kids' =>  array(
                'label' => 'Kids',
                'link' => 'http://wedesignthemes.com/themes/lms/kids/',
                'content' => $content + array( 'classes' => esc_html('Classes', 'dt_themes'), 'courses' => esc_html('Courses', 'dt_themes'), 'lessons' => esc_html('Lessons', 'dt_themes'), 'quizzes' => esc_html('Quizzes', 'dt_themes'), 'questions' => esc_html('Questions', 'dt_themes'), 'assignments' => esc_html('Assignments', 'dt_themes') )
            ),
            'pointssystem' =>  array(
                'label' => 'Points System',
                'link' => 'http://wedesignthemes.com/themes/lms/pointssystem/',
                'content' => $content + array( 'quizzes' => esc_html('Quizzes', 'dt_themes'), 'questions' => esc_html('Questions', 'dt_themes') )
            ),
        );
        
        ?>
        
        <!-- #tab1-import-demo -->
        <div id="tab1" class="tab-content">
            <!-- .bpanel-box -->
            <div class="bpanel-box">
                <div class="box-title">
                    <h3><?php esc_html_e('Import Demo', 'dt_themes');?></h3>
                </div>
                
                <div class="box-content dttheme-import">
					<p class="note"><?php esc_html_e('Before starting the import, you need to install all plugins that you want to use.<br />If you are planning to use the shop, please install WooCommerce plugin.', 'dt_themes');?></p>
                    <div class="hr_invisible"> </div>
                    <div class="column one-third"><label><?php esc_html_e('Demo', 'dt_themes');?></label></div>
                    <div class="column two-third last">
                        <select name="demo" class="demo medium dt-chosen-select">
                            <option data-link="http://wedesignthemes.com/themes/lms/" value="">-- <?php esc_html_e('Select', 'dt_themes');?> --</option>
                            <?php foreach( $lms_demos as $key => $lms_demo ) : ?>
                                    <option data-link="<?php echo esc_attr( $lms_demo['link'] ); ?>" value="<?php echo esc_attr($key); ?>"><?php echo esc_html( $lms_demo['label'] ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="hr_invisible"> </div>
                    
                    <?php foreach($lms_demos as $lms_demo_key => $lms_demo) :?>
                            <div class="lms-demos <?php echo esc_attr($lms_demo_key); ?>-demo hide">
                                <div class="column one-third"><label><?php esc_html_e('Import', 'dt_themes');?></label></div>
                                <div class="column two-third last">
                                    <select name="import" class="import medium dt-chosen-select">
                                        <option value="">-- <?php esc_html_e('Select', 'dt_themes');?> --</option>
                                        <option value="all"><?php esc_html_e('All', 'dt_themes') ?></option>
                                        <option value="content"><?php esc_html_e('Content', 'dt_themes') ?></option>
                                        <option value="menu"><?php esc_html_e('Menu', 'dt_themes') ?></option>
                                        <option value="options"><?php esc_html_e('Options', 'dt_themes') ?></option>
                                        <option value="widgets"><?php esc_html_e('Widgets', 'dt_themes') ?></option>
                                    </select>
                                </div>

                                <div class="hr_invisible"> </div>

                                <!-- 2.1. Content Type  -->
                                <div class="row-content hide">
                                    <div class="column one-third">
                                        <label for="content"><?php esc_html_e('Content', 'dt_themes');?></label>
                                    </div>
                                    <div class="column two-third last">
                                        <select name="content" class="medium dt-chosen-select">
                                            <?php foreach( $lms_demo['content'] as $key => $value ): ?>
                                                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html($value); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;?>

					<div class="row-attachments hide">
						<div class="column one-third"><?php esc_html_e('Attachments', 'dt_themes');?></div>
						<div class="column two-third last">
							<fieldset>
								<label for="attachments"><input type="checkbox" value="0" id="attachments" name="attachments"><?php esc_html_e('Import attachments', 'dt_themes');?></label>
								<p class="description"><?php esc_html_e('Download all attachments from the demo may take a while. Please be patient.', 'dt_themes');?></p>
							</fieldset>
						</div>
						<div class="hr_invisible"> </div>
					</div>
                    <div class="column one-column">
						<div class="hr_invisible"> </div>
						<div class="column one-third">&nbsp;</div>
						<div class="column two-third last">
		                    <a href="#" class="dttheme-import-button bpanel-button black-btn" title="<?php esc_html_e('Import demo data', 'dt_themes');?>"><?php esc_html_e('Import demo data', 'dt_themes');?></a>
                        </div>
                    </div>
                    <div class="hr"></div>
                </div><!-- .box-content -->
            </div><!-- .bpanel-box end -->            
        </div><!--#tab1-import-demo end-->

    </div><!-- .bpanel-main-content end-->
</div><!-- #import end-->