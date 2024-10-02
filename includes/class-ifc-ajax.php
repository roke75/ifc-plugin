<?php
// includes/class-ifc-ajax.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IFC_AJAX {
    public function run() {
        add_action( 'wp_ajax_ifc_update_word_cloud', array( $this, 'update_word_cloud' ) );
        add_action( 'wp_ajax_nopriv_ifc_update_word_cloud', array( $this, 'update_word_cloud' ) );

        add_action( 'wp_ajax_ifc_update_answers', array( $this, 'update_answers' ) );
        add_action( 'wp_ajax_nopriv_ifc_update_answers', array( $this, 'update_answers' ) );
    }

    public function update_word_cloud() {
        check_ajax_referer( 'ifc_ajax_nonce', 'nonce' );

        $question_id = isset( $_POST['question_id'] ) ? intval( $_POST['question_id'] ) : 0;

        if ( $question_id <= 0 ) {
            wp_send_json_error( array( 'message' => __( 'Invalid question ID.', 'ifc-plugin' ) ) );
        }

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';
        $question = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

        if ( ! $question ) {
            wp_send_json_error( array( 'message' => __( 'Question not found.', 'ifc-plugin' ) ) );
        }

        $table_answers = $wpdb->prefix . 'ifc_answers';
        $answers = $wpdb->get_col( $wpdb->prepare( "SELECT answer FROM $table_answers WHERE question_id = %d", $question_id ) );

        $word_counts = array();
        $stop_words  = array(   // english stop words
                                'and', 'or', 'the', 'a', 'an', 'is', 'was', 'as', 'in', 'of', 'to', 'for', 'on', 'at', 'by', 'with', 'from',
                                // finnish stop words
                                'ja', 'on', 'että', 'tämä', 'se', 'mutta', 'niin', 'tai', 'jos', 'kuten', 'kuitenkin', 'koska', 'jotta', 'vaan', 'kun', 'mikä', 'missä', 'mitä', 'milloin', 'jopa', 'sillä' ); // Lisää tarpeen mukaan

        foreach ( $answers as $answer ) {
            $words = preg_split( '/\W+/u', mb_strtolower( $answer, 'UTF-8' ), -1, PREG_SPLIT_NO_EMPTY );
            foreach ( $words as $word ) {
                if ( mb_strlen( $word, 'UTF-8' ) > 2 && ! in_array( $word, $stop_words ) ) {
                    if ( isset( $word_counts[ $word ] ) ) {
                        $word_counts[ $word ]++;
                    } else {
                        $word_counts[ $word ] = 1;
                    }
                }
            }
        }

        $word_cloud_data = array();
        foreach ( $word_counts as $word => $count ) {
            $word_cloud_data[] = array(
                'text'   => $word,
                'weight' => $count,
            );
        }

        wp_send_json_success( array(
            'word_cloud_data' => $word_cloud_data,
        ) );
    }

    public function update_answers() {
        check_ajax_referer( 'ifc_ajax_nonce', 'nonce' );

        $last_id      = isset( $_POST['last_id'] ) ? intval( $_POST['last_id'] ) : 0;
        $question_id  = isset( $_POST['question_id'] ) ? intval( $_POST['question_id'] ) : 0;

        if ( $question_id <= 0 ) {
            wp_send_json_error( array( 'message' => __( 'Invalid question ID.', 'ifc-plugin' ) ) );
        }

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';
        $question        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

        if ( ! $question ) {
            wp_send_json_error( array( 'message' => __( 'Question not found.', 'ifc-plugin' ) ) );
        }

        $table_answers = $wpdb->prefix . 'ifc_answers';
        $answers       = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_answers WHERE question_id = %d AND id > %d ORDER BY id ASC",
                $question_id,
                $last_id
            )
        );

        $response = array(
            'status'    => 'success',
            'answers'   => array(),
            'latest_id' => $last_id,
        );

        if ( $answers ) {
            foreach ( $answers as $answer ) {
                ob_start();
                ?>
                <div class="col-md-4 col-sm-6 ifc-answer" data-id="<?php echo esc_attr( $answer->id ); ?>">
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="card-text"><?php echo esc_html( $answer->answer ); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                $answer_html = ob_get_clean();

                $response['answers'][]    = $answer_html;
                $response['latest_id']    = max( $response['latest_id'], $answer->id );
            }
        }

        wp_send_json( $response );
    }
}
