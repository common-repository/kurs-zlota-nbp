<?php
/**
* NBP
*/
class NBP_Gold_Widget extends WP_Widget {

	private static $actual_gold_rates_uri = 'http://api.nbp.pl/api/cenyzlota/last/2/?format=json';
	private $actual_gold_rates = array();

	function __construct()
	{
		$temp_actual_gold_rates_json = file_get_contents(static::$actual_gold_rates_uri);
		$this->actual_gold_rates = json_decode($temp_actual_gold_rates_json);

		parent::__construct(
			'nbp-gold-widget',
			__('Aktualny kurs złota - NBP', 'nbp-gold-widget'),
			array(
				'descriptions' => __('Kurs złota Narodowego Banku Polskiego', 'nbp-gold-widget')
			)
		);
	}


	public function widget($args, $instance)
	{
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$dates['yestarday_price'] = $this->actual_gold_rates[0]->cena;
		$dates['today_price'] = $this->actual_gold_rates[1]->cena;

		$yestarday_price = number_format($dates['yestarday_price'], 2, '', '');
		$today_price = number_format($dates['today_price'], 2, '', '');
		?>

		<style type="text/css">
			#nbp-gold-widget tr td{text-align: center;}
			#nbp-gold-widget tr th{text-align: center;}
		</style>
		<time><small><?php _e( 'Kurs z dnia:', 'nbp-gold-widget' ); ?> <?php echo mysql2date(get_option('date_format'), $this->actual_gold_rates[1]->data) ; ?></small></time>
		<table id="nbp-gold-widget">
			<tr>
				<th><?php _e( 'Kurs', 'nbp-gold-widget' ); ?></th>
				<th><?php _e( 'Zmiana', 'nbp-gold-widget' ); ?></th>
			</tr>
			<tr>
				<td><?php echo $dates['today_price']; ?> PLN</td>
				<?php
				switch (version_compare($yestarday_price, $today_price) ){
					case 0:
						echo '<td>';
						$today_yestarday = $dates['today_price'] - $dates['yestarday_price'];
						echo number_format($today_yestarday, 2, '.', '');
						break;
					case -1:
						echo '<td style="color: green;">';
						$today_yestarday = $dates['today_price'] - $dates['yestarday_price'];
						echo '&uarr; ' . (number_format($today_yestarday, 2, '.', ''));
						break;
					case 1:
						echo '<td style="color: red;">';
						$yestarday_today = $dates['yestarday_price'] - $dates['today_price'];
						echo '&darr; ' .  abs(number_format($yestarday_today, 2, '.', ''));
						break;
				}
				?>
				%
				</td>
			</tr>
		</table>
	
		<?php 

		echo $args['after_widget'];
	}


	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

	public function form($instance)
	{

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Kurs Złota NBP', 'nbp-gold-widget' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}
	
}