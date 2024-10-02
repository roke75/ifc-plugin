<?php
// includes/class-ifc-shortcodes.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IFC_Shortcodes {
    public function run() {
        add_shortcode( 'ifc', array( $this, 'ifc_shortcode' ) );
        add_shortcode( 'ifc_results', array( $this, 'ifc_results_shortcode' ) );
    }

    public function ifc_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'id' => 0,
        ), $atts, 'ifc' );

        $question_id = intval( $atts['id'] );

        if ( $question_id <= 0 ) {
            return __( 'Invalid question ID.', 'ifc-plugin' );
        }

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';
        $question        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

        if ( ! $question ) {
            return __( 'Question not found.', 'ifc-plugin' );
        }

        ob_start();
        // Handle form submission
        if ( isset( $_POST['ifc_submit'] ) && isset( $_POST['ifc_answer'] ) && wp_verify_nonce( $_POST['ifc_nonce'], 'ifc_nonce_action' ) ) {
            global $wpdb;
            $table_answers = $wpdb->prefix . 'ifc_answers';
            $answer        = sanitize_text_field( $_POST['ifc_answer'] );
            $wpdb->insert(
                $table_answers,
                array(
                    'question_id' => $question_id,
                    'answer'      => $answer,
                )
            );
            // setcookie('ifc_answered_' . $question_id, '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
            echo '<div class="alert alert-success" role="alert">' . __( 'Thank you for your answer.', 'ifc-plugin' ) . '</div>';
        } else {
            // if ( isset($_COOKIE['ifc_answered_' . $question_id]) ) {
            //     echo '<p>You have already answered this question.</p>';
            // } else {
            ?>
            <form method="post" class="ifc-form">
                <div class="form-group">
                    <label for="ifcTextarea"><?php echo esc_html( $question->question ); ?></label>
                    <textarea class="form-control" id="ifcTextarea" name="ifc_answer" required></textarea>
                </div>
                <?php wp_nonce_field( 'ifc_nonce_action', 'ifc_nonce' ); ?>
                <button type="submit" name="ifc_submit" class="btn btn-primary"><?php _e( 'Send', 'ifc-plugin' ); ?></button>
            </form>
            <?php
            // }
        }
        return ob_get_clean();
    }

    public function ifc_results_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'id'   => 0,
            'view' => 'default',
        ), $atts, 'ifc_results' );

        $question_id = intval( $atts['id'] );

        if ( $question_id <= 0 ) {
            return __( 'Invalid question ID.', 'ifc-plugin' );
        }

        $view = sanitize_text_field( $atts['view'] );

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';
        $question        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

        if ( ! $question ) {
            return __( 'Question not found.', 'ifc-plugin' );
        }

        // Display answers
        ob_start();
        if ( $view == 'word_cloud' ) {
            ?>
            <div class="ifc-word-cloud-container">
                <h3><?php echo esc_html( $question->question ); ?></h3>
                <div id="ifc-word-cloud-<?php echo esc_attr( $question_id ); ?>" style="width:600px; height:400px;"></div>
            </div>
            <?php
        } else {
            ?>
            <div class="ifc-results-container">
                <h3><?php echo esc_html( $question->question ); ?></h3>
                <div id="ifc-answers" data-question-id="<?php echo esc_attr( $question_id ); ?>" class="container">
                    <div class="row" id="answers-row">
                    </div>
                </div>
            </div>
            <?php
        }

        return ob_get_clean();
    }
}
