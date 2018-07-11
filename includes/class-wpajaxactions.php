<?php

defined( 'ABSPATH' ) || exit;

class WPAA {
	protected static $_instance = null;

	private $nonce_action = 'wpaa';

	public function __construct() {
		$this->add_hooks();
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function add_hooks() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );

		$action = 'wpaa_function';
		add_action( 'wp_ajax_' . $action, [ $this, $action ] );


		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );

	}

	public function enqueue() {
		wp_enqueue_script( 'wpaa', plugin_dir_url( WPAA_PLUGIN_FILE ) . '/assets/js/wp_ajax.js', [ 'jquery' ], WPAA_VER, true );

		wp_enqueue_style( 'wpaa', plugin_dir_url( WPAA_PLUGIN_FILE ) . '/assets/css/wp_ajax.css', [], WPAA_VER );
	}

	public function add_menu_page() {
		add_management_page( 'WPAA', 'WPAA', 'manage_options', 'wpaa_page', [$this, 'menu_page'] );
	}

	public function menu_page(){
	    do_action('wpaa_menu_page');
	}

	public function wpaa_function() {
		check_ajax_referer( $this->nonce_action );

//		if ( isset( $_POST['osp_class'] ) ) {
//			$class = new $_POST['osp_class'];
//			call_user_func( array( $class, $_POST['osp_method'] ) );
//		} else {
//			if ( function_exists( $_POST['osp_function'] ) ) {
//				call_user_func( $_POST['osp_function'], $_POST );
//			} else {
//				echo 'function does not exists ' . htmlspecialchars( $_POST['osp_function'] );
//			}
//		}

		if ( function_exists( $_POST['ajax_function'] ) ) {
			call_user_func( $_POST['ajax_function'], $_POST );
		} else {
			echo 'function does not exists ' . htmlspecialchars( $_POST['ajax_function'] );
		}

		die;
	}

	public function create_form( $title, $function, $params = false ) {

		$form_id = md5( $title );

		$config = $params['config'] ?? null;
		unset( $params['config'] );

		$collapsed = $config['collapsed'] ?? '';
		?>
        <div class="wpaa_form <?= $collapsed ?>" id="<?= $form_id ?>">
            <form>
                <input type="hidden" name="_ajax_nonce" value="<?= wp_create_nonce( $this->nonce_action ) ?>">

                <div class="title"><?= $title ?> (<?= $function ?>)<span
                            class="toggle">открыть/закрыть</span></div>

                <div class="cont">

					<?php //if ($class):
					?>
                    <!--
						<input type="hidden" name="osp_class" value="<?//= $class
					?>">
						<input type="hidden" name="osp_method" value="<?//= $function
					?>">
						-->
					<?php //else:
					?>
                    <input type="hidden" name="ajax_function" value="<?= $function ?>">
					<?php ///endif
					?>

					<?php
					if ( $params ) {
						foreach ( $params as $param ) {
							$this->render_field( $param );
						}
					}
					?>

                    <div>
                        <input type="submit" class="submit_wpaa_form" value="отправить" form="<?= $form_id ?>"/>
                        <!--                    <input type="button" class="clear" value="очистить">-->
                        <span class="osp_ajax spinner"></span>
                    </div>
                    <div class="result"></div>
                </div>
            </form>
        </div>
		<?php
	}

	public function render_field( $param ) {
		extract( $param );

		if ( ! isset( $key_as_option_value ) ) {
			$key_as_option_value = false;
		}

		echo '<div>';

		switch ( $type ) {
			case 'select':
				?>
				<?= $label ?>:
                <select class="<?= $class ?>" name="<?= $name ?>" class="osp-select2">
					<?php foreach ( $values as $key => $value ): ?>
						<?php
						if ( $key_as_option_value ) {
							$option_value = $key;
						} else {
							$option_value = $value;
						}
						?>
                        <option value="<?= $option_value ?>"><?= $value ?></option>
					<?php endforeach ?>
                </select>
				<?= $legend ?>
				<?php
				break;
			case 'text':
				?>
				<?= $label . ' (' . $name . ')' ?>:
                <input
                        type="text"
                        name="<?= $name ?>"
                        value="<?= $value ?>"> <?= $legend ?>
				<?php
				break;
			case 'textarea':
				?>
				<?= $label ?>: <br>
                <textarea name="<?= $name ?>" style="width: 70%;"><?= $value ?></textarea> <br>
				<?= $legend ?>
				<?php
				break;
			case 'hidden':
				?>
                <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
				<?php
				break;
			case 'checkbox':
				?>
				<?= $label ?> (<?= $name ?>): <input type="checkbox" name="<?= $name ?>"
                                                     value="<?= $value ?>"> <?= $legend ?>
				<?php
				break;
			case 'radio':
				?>
				<?= $label ?><br>
				<?php foreach ( $values as $option => $value ): ?>
                <label>
                    <input
                            type="radio"
                            name="<?= $name ?>"
                            value="<?= $option ?>"
						<?php //checked($option, $default) ?>
                    />
					<?= $value ?>
                </label>
                <br>
			<?php
			endforeach;
				break;
			case 'file':
				?>
				<?= $label ?> (<?= $name ?>) <input type="file" name="<?= $name ?>" id="file_id_<?= $name ?>">
				<?php
				break;
			//        case '':
			//            break;
			default:
				epr( 'тип поля ' . $type . ' неизвестен' );
				break;
		}

		echo '</div>';


	}
}