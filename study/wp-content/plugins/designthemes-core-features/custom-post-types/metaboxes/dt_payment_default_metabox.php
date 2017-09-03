<?php
global $post;
$post_id = $post->ID;

$payment_data = get_post_meta ( $post->ID, 'payment-data', TRUE );

$payment_method = dttheme_option('general','payment-method');	
?>

<div class="custom-box">


	<div class="column one-column">
    	
        <?php
		
		if(isset($payment_data) && is_array($payment_data)) {
		
			$out = '';
			$out .= '<table border="0" cellpadding="0" cellspacing="20">
					  <tr>
						<th scope="col" class="aligncenter">'.__('#', 'dt_themes').'</th>
						<th scope="col" class="aligncenter">'.__('Course', 'dt_themes').'</th>
						<th scope="col" class="aligncenter">'.__('Purchases', 'dt_themes').'</th>
						<th scope="col" class="aligncenter">'.__('Price', 'dt_themes').'</th>
						<th scope="col" class="aligncenter">'.__('Commission (%)', 'dt_themes').'</th>';
						if($payment_method == 'woocommerce') {
							$out .= '<th scope="col" class="aligncenter">'.__('Amount ('.get_woocommerce_currency_symbol().')', 'dt_themes').'</th>';
						} else {
							$out .= '<th scope="col" class="aligncenter">'.__('Amount ('.dttheme_wp_kses(dttheme_option('dt_course','currency')).')', 'dt_themes').'</th>';
						}
			$out .= '</tr>';
			
			$i = 1; $total = 0;
			
			foreach($payment_data as $payment) {
				
				$course = get_post($payment['course_id']);
	
				$out .= '<tr>
							<td class="aligncenter">'.$i.'</td>
							<td class="aligncenter">'.$course->post_title.'</td>
							<td class="aligncenter">'.$payment['purchases'].'</td>
							<td class="aligncenter">'.$payment['starting_price'].'</td>
							<td class="aligncenter">'.$payment['commission'].'</td>
							<td class="aligncenter">'.$payment['topay'].'</td>
						</tr>';
				
				$total = $total+$payment['topay'];
				
				$i++;
			}
			
			if($total > 0) {
			
				$out .= '<tr>
							<td colspan="6">&nbsp;</td>
						</tr>';
				$out .= '<tr>';
							if($payment_method == 'woocommerce') {
								$out .= '<td colspan="5" class="alignright"><strong>'.__('Total ('.get_woocommerce_currency_symbol().')', 'dt_themes').'</strong></td>';
							} else {
								$out .= '<td colspan="5" class="alignright"><strong>'.__('Total ('.dttheme_wp_kses(dttheme_option('dt_course','currency')).')', 'dt_themes').'</strong></td>';
							}
					$out .= '<td>'.$total.'</td>
						</tr>';
				
			}
			
			$out .= '</table>';
			
			echo $out;
			
		}
		
		?>
        
    </div>
    
</div>