<?php
/*
Plugin Name: Instant Feedback Collector Plugin
Description: Ask question and get instant feedback
Version: 1.0
Author: Jarkko Roininen
*/

// Prevent direct access to this file
if ( !defined( 'ABSPATH' ) ) exit;

// Create database tables when plugin is activated
register_activation_hook( __FILE__, 'ifc_create_tables' );
function ifc_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Table for questions
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $sql_questions = "CREATE TABLE $table_questions (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        question text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Table for answers
    $table_answers = $wpdb->prefix . 'ifc_answers';
    $sql_answers = "CREATE TABLE $table_answers (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        question_id mediumint(9) NOT NULL,
        answer text NOT NULL,
        time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        FOREIGN KEY (question_id) REFERENCES $table_questions(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_questions );
    dbDelta( $sql_answers );
}

// Add admin menu
add_action( 'admin_menu', 'ifc_add_admin_menu' );
function ifc_add_admin_menu() {
    add_menu_page(
        'Instant Feedback Collector Plugin',
        'Instant Feedback Collector Plugin',
        'manage_options',
        'ifc-plugin',
        'ifc_admin_page',
        'dashicons-editor-help',
        6
    );
}

function ifc_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';

    // Handle form submissions
    if ( isset( $_POST['ifc_action'] ) && check_admin_referer( 'ifc_admin_action', 'ifc_admin_nonce' ) ) {
        // Add new question
        if ( $_POST['ifc_action'] == 'add_question' && ! empty( $_POST['question_text'] ) ) {
            $question_text = sanitize_text_field( $_POST['question_text'] );
            $wpdb->insert(
                $table_questions,
                array( 'question' => $question_text )
            );
            echo '<div class="updated"><p>Question added.</p></div>';
        }

        // Update question
        if ( $_POST['ifc_action'] == 'edit_question' && ! empty( $_POST['question_text'] ) && isset( $_POST['question_id'] ) ) {
            $question_text = sanitize_text_field( $_POST['question_text'] );
            $question_id = intval( $_POST['question_id'] );
            $wpdb->update(
                $table_questions,
                array( 'question' => $question_text ),
                array( 'id' => $question_id )
            );
            echo '<div class="updated"><p>Question updated.</p></div>';
        }

        // Delete question and answers
        if ( $_POST['ifc_action'] == 'delete_question' && isset( $_POST['question_id'] ) ) {
            $question_id = intval( $_POST['question_id'] );

            // Delete answers
            $wpdb->delete(
                $wpdb->prefix . 'ifc_answers',
                array( 'question_id' => $question_id )
            );

            // Delete question
            $wpdb->delete(
                $table_questions,
                array( 'id' => $question_id )
            );

            echo '<div class="updated"><p>Question and answers deleted.</p></div>';
        }

        // Delete just answers
        if ( $_POST['ifc_action'] == 'delete_answers' && isset( $_POST['question_id'] ) ) {
            $question_id = intval( $_POST['question_id'] );

            // Delete answers
            $wpdb->delete(
                $wpdb->prefix . 'ifc_answers',
                array( 'question_id' => $question_id )
            );

            echo '<div class="updated"><p>Answers deleted.</p></div>';
        }
    }

    // Show management form
    ?>
    <div class="wrap">
        <h1>Instant Feedback Collector Plugin - Manage Questions</h1>
        <h2>Add Question</h2>
        <form method="post">
            <?php wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' ); ?>
            <input type="hidden" name="ifc_action" value="add_question">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Question</th>
                    <td><input type="text" name="question_text" required style="width: 100%;"></td>
                </tr>
            </table>
            <?php submit_button( 'Add Question' ); ?>
        </form>

        <h2>All Questions</h2>
        <?php
        $questions = $wpdb->get_results( "SELECT * FROM $table_questions ORDER BY id DESC" );

        if ( $questions ) {
            echo '<table class="widefat fixed">';
            echo '<thead><tr><th>ID</th><th>Kysymys</th><th>Toiminnot</th></tr></thead>';
            echo '<tbody>';
            foreach ( $questions as $question ) {
                echo '<tr>';
                echo '<td>' . esc_html( $question->id ) . '</td>';
                echo '<td>' . esc_html( $question->question ) . '</td>';
                echo '<td>';
                // Edit and delete buttons
                ?>
                <form method="post" style="display:inline-block; margin-right:10px;">
                    <?php wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' ); ?>
                    <input type="hidden" name="ifc_action" value="edit_question_form">
                    <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                    <?php submit_button( 'Edit', 'secondary', '', false ); ?>
                </form>
                <form method="post" style="display:inline-block; margin-right:10px;">
                    <?php wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' ); ?>
                    <input type="hidden" name="ifc_action" value="delete_question">
                    <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                    <?php submit_button( 'Delete', 'delete', '', false, array( 'onclick' => "return confirm('Do you really want to delete this question?');" ) ); ?>
                </form>
                <form method="post" style="display:inline-block;">
                    <?php wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' ); ?>
                    <input type="hidden" name="ifc_action" value="delete_answers">
                    <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                    <?php submit_button( 'Delete all answers', 'default', 'delete', '', false, array( 'onclick' => "return confirm('Do you really want to delete all answers for this question?');" ) ); ?>
                </form>
                <?php
                echo '</td>';
                echo '</tr>';

                // Edit question form
                if ( isset( $_POST['ifc_action'] ) && $_POST['ifc_action'] == 'edit_question_form' && intval( $_POST['question_id'] ) == $question->id ) {
                    ?>
                    <tr>
                        <td colspan="3">
                            <form method="post">
                                <?php wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' ); ?>
                                <input type="hidden" name="ifc_action" value="edit_question">
                                <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">Edit Question</th>
                                        <td><input type="text" name="question_text" value="<?php echo esc_attr( $question->question ); ?>" required style="width: 100%;"></td>
                                    </tr>
                                </table>
                                <?php submit_button( 'Update Question' ); ?>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No questions found.</p>';
        }
        ?>
    </div>
    <?php
}

// Register shortcode
add_shortcode( 'ifc', 'ifc_shortcode' );
function ifc_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0,
    ), $atts, 'ifc' );

    $question_id = intval( $atts['id'] );

    if ( $question_id <= 0 ) {
        return 'Invalid question ID.';
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

    if ( ! $question ) {
        return 'Question not found.';
    }

    // Handle form submission
    if ( isset( $_POST['ifc_submit'] ) && isset( $_POST['ifc_answer'] ) && wp_verify_nonce( $_POST['ifc_nonce'], 'ifc_nonce_action' ) ) {
        global $wpdb;
        $table_answers = $wpdb->prefix . 'ifc_answers';
        $answer = sanitize_text_field( $_POST['ifc_answer'] );
        $wpdb->insert(
            $table_answers,
            array(
                'question_id' => $question_id,
                'answer' => $answer,
            )
        );
        echo '<p>Thank you for your answer.</p>';
    }

    // Display question and form
    ob_start();
    ?>
    <form method="post">
        <p><strong>Question:</strong> <?php echo esc_html( $question->question ); ?></p>
        <textarea name="ifc_answer" required></textarea><br>
        <?php wp_nonce_field( 'ifc_nonce_action', 'ifc_nonce' ); ?>
        <input type="submit" name="ifc_submit" value="Send">
    </form>
    <?php
    return ob_get_clean();
}

// Register shortcode
add_shortcode( 'ifc_results', 'ifc_results_shortcode' );
function ifc_results_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0,
    ), $atts, 'ifc_results' );

    $question_id = intval( $atts['id'] );

    if ( $question_id <= 0 ) {
        return 'Invalid question ID.';
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

    if ( ! $question ) {
        return 'Question not found.';
    }

    // Display answers
    ob_start();
    ?>
    <div id="ifc-answers" data-question-id="<?php echo esc_attr( $question_id ); ?>">
        <!-- Answers will be displayed here -->
    </div>
    <?php
    return ob_get_clean();
}

// AJAX request handlers
add_action( 'wp_ajax_ifc_update_answers', 'ifc_update_answers' );
add_action( 'wp_ajax_nopriv_ifc_update_answers', 'ifc_update_answers' );
function ifc_update_answers() {
    // Check AJAX nonce
    check_ajax_referer( 'ifc_ajax_nonce', 'nonce' );

    // Get question ID and last answer ID
    $last_id = isset( $_POST['last_id'] ) ? intval( $_POST['last_id'] ) : 0;
    $question_id = isset( $_POST['question_id'] ) ? intval( $_POST['question_id'] ) : 0;

    if ( $question_id <= 0 ) {
        wp_send_json_error( array( 'message' => 'Invalid question ID.' ) );
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

    if ( ! $question ) {
        wp_send_json_error( array( 'message' => 'Question not found.' ) );
    }

    // Get new answers
    $table_answers = $wpdb->prefix . 'ifc_answers';
    $answers = $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_answers WHERE question_id = %d AND id > %d ORDER BY id ASC",
        $question_id, $last_id
    ) );

    $response = array(
        'status' => 'success',
        'answers' => array(),
        'latest_id' => $last_id,
    );

    if ( $answers ) {
        foreach ( $answers as $answer ) {
            // Create answer HTML
            ob_start();
            echo '<div class="ifc-answer new" data-id="' . esc_attr( $answer->id ) . '">';
            echo '<p>' . esc_html( $answer->answer ) . '</p>';
            echo '<small>' . esc_html( $answer->time ) . '</small>';
            echo '</div>';
            $answer_html = ob_get_clean();

            $response['answers'][] = $answer_html;
            $response['latest_id'] = max( $response['latest_id'], $answer->id );
        }
    }

    wp_send_json( $response );
}

// Show results
function ifc_show_results( $viimeisin_id = 0, $question_id ) {
    global $wpdb;
    $table_answers = $wpdb->prefix . 'ifc_answers';
    $answers = $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_answers WHERE question_id = %d ORDER BY id DESC",
        $question_id
    ) );

    if ( $answers ) {
        foreach ( $answers as $answer ) {
            // Check if it's a new answer
            $is_new = $answer->id > $last_id;
            $new_class = $is_new ? ' new' : '';

            echo '<div class="ifc-answer' . $new_class . '" data-id="' . esc_attr( $answer->id ) . '">';
            echo '<p>' . esc_html( $answer->answer ) . '</p>';
            echo '<small>' . esc_html( $answer->time ) . '</small>';
            echo '</div>';
        }
    } else {
        echo '<p>No answers yet.</p>';
    }
}

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', 'ifc_load_styles' );
function ifc_load_styles() {
    wp_enqueue_style( 'ifc', plugin_dir_url( __FILE__ ) . 'ifc.css' );

    // Load jQuery in case it's not already loaded
    wp_enqueue_script( 'jquery' );

    // Load the script
    wp_enqueue_script( 'ifc', plugin_dir_url( __FILE__ ) . 'ifc.js', array('jquery'), null, true );

    // Localize the script with the AJAX URL
    wp_localize_script( 'ifc', 'ifc_ajax_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'ifc_ajax_nonce' ),
    ) );
}
