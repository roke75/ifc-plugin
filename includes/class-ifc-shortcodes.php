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

    /**
     * Check if a question is currently active
     *
     * @param object $question The question object from database
     * @return array Array with 'active' boolean and 'message' string
     */
    private function is_question_active( $question ) {
        $now = current_time( 'mysql' );

        // Check status
        if ( $question->status !== 'active' ) {
            $status_messages = array(
                'draft'    => __( 'This question is not yet available.', 'ifc-plugin' ),
                'closed'   => __( 'This question is now closed.', 'ifc-plugin' ),
                'archived' => __( 'This question has been archived.', 'ifc-plugin' ),
            );
            return array(
                'active'  => false,
                'message' => isset( $status_messages[ $question->status ] ) ? $status_messages[ $question->status ] : __( 'This question is not available.', 'ifc-plugin' ),
            );
        }

        // Check start date
        if ( ! empty( $question->start_date ) && $question->start_date > $now ) {
            $start_time = strtotime( $question->start_date );
            return array(
                'active'  => false,
                'message' => sprintf(
                    __( 'This question will open on %s.', 'ifc-plugin' ),
                    date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $start_time )
                ),
            );
        }

        // Check end date
        if ( ! empty( $question->end_date ) && $question->end_date < $now ) {
            return array(
                'active'  => false,
                'message' => __( 'This question has closed.', 'ifc-plugin' ),
            );
        }

        // Check max answers
        if ( ! empty( $question->max_answers ) && $question->max_answers > 0 ) {
            global $wpdb;
            $table_answers = $wpdb->prefix . 'ifc_answers';
            $answer_count = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_answers WHERE question_id = %d",
                $question->id
            ) );

            if ( $answer_count >= $question->max_answers ) {
                return array(
                    'active'  => false,
                    'message' => __( 'This question has reached its maximum number of answers.', 'ifc-plugin' ),
                );
            }
        }

        return array( 'active' => true, 'message' => '' );
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

        // Check if question is active
        $active_check = $this->is_question_active( $question );
        if ( ! $active_check['active'] ) {
            return '<div class="alert alert-info" role="alert">' . esc_html( $active_check['message'] ) . '</div>';
        }

        ob_start();
        // Handle form submission
        if ( isset( $_POST['ifc_submit'] ) && isset( $_POST['ifc_answer'] ) && wp_verify_nonce( $_POST['ifc_nonce'], 'ifc_nonce_action' ) ) {
            // Double-check that question is still active before saving
            $active_recheck = $this->is_question_active( $question );
            if ( ! $active_recheck['active'] ) {
                echo '<div class="alert alert-warning" role="alert">' . esc_html( $active_recheck['message'] ) . '</div>';
                return ob_get_clean();
            }
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

            // Invalidate word cloud cache when new answer is added
            delete_transient( 'ifc_word_cloud_' . $question_id );

            // Check if we've reached max_answers and auto-close if needed
            if ( ! empty( $question->max_answers ) && $question->max_answers > 0 ) {
                $answer_count = $wpdb->get_var( $wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_answers WHERE question_id = %d",
                    $question_id
                ) );

                if ( $answer_count >= $question->max_answers ) {
                    $wpdb->update(
                        $table_questions,
                        array( 'status' => 'closed' ),
                        array( 'id' => $question_id ),
                        array( '%s' ),
                        array( '%d' )
                    );
                }
            }

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
            $cloud_width  = get_option( 'ifc_word_cloud_width', 600 );
            $cloud_height = get_option( 'ifc_word_cloud_height', 400 );
            ?>
            <div class="ifc-word-cloud-container">
                <h3><?php echo esc_html( $question->question ); ?></h3>
                <div id="ifc-word-cloud-<?php echo esc_attr( $question_id ); ?>" style="width:<?php echo esc_attr( $cloud_width ); ?>px; height:<?php echo esc_attr( $cloud_height ); ?>px;"></div>
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
