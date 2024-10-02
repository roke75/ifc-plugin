<?php
// includes/admin-page.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wpdb;
$table_questions = $wpdb->prefix . 'ifc_questions';

// Show updated and error messages
if ( isset( $_GET['updated'] ) ) {
    $updated = sanitize_text_field( $_GET['updated'] );
    switch ( $updated ) {
        case 'add':
            echo '<div class="updated notice"><p>' . __( 'Question added successfully.', 'ifc-plugin' ) . '</p></div>';
            break;
        case 'edit':
            echo '<div class="updated notice"><p>' . __( 'Question updated successfully.', 'ifc-plugin' ) . '</p></div>';
            break;
        case 'delete':
            echo '<div class="updated notice"><p>' . __( 'Question and its answers deleted successfully.', 'ifc-plugin' ) . '</p></div>';
            break;
        case 'delete_answers':
            echo '<div class="updated notice"><p>' . __( 'All answers for this question have been deleted.', 'ifc-plugin' ) . '</p></div>';
            break;
        case 'error':
            echo '<div class="error notice"><p>' . __( 'An error occurred. Please try again.', 'ifc-plugin' ) . '</p></div>';
            break;
        default:
            break;
    }
}

// Edit question form
if ( isset( $_GET['action'] ) && $_GET['action'] === 'edit' && isset( $_GET['question_id'] ) ) {
    $question_id = intval( $_GET['question_id'] );
    $question    = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $question_id ) );

    if ( $question ) {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Edit Question', 'ifc-plugin' ); ?></h1>
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <?php
                wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' );
                ?>
                <input type="hidden" name="action" value="ifc_edit_question">
                <input type="hidden" name="ifc_action" value="edit_question">
                <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Question', 'ifc-plugin' ); ?></th>
                        <td><input type="text" name="question_text" value="<?php echo esc_attr( $question->question ); ?>" required style="width: 100%;"></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Update Question', 'ifc-plugin' ) ); ?>
            </form>
        </div>
        <?php
        return;
    } else {
        echo '<div class="error notice"><p>' . __( 'Question not found.', 'ifc-plugin' ) . '</p></div>';
    }
}

// Show add question form and list of questions
?>
<div class="wrap">
    <h1><?php _e( 'Instant Feedback Collector Plugin - Manage Questions', 'ifc-plugin' ); ?></h1>
    <h2><?php _e( 'Add Question', 'ifc-plugin' ); ?></h2>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
        <?php
        wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' );
        ?>
        <input type="hidden" name="action" value="ifc_add_question">
        <input type="hidden" name="ifc_action" value="add_question">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e( 'Question', 'ifc-plugin' ); ?></th>
                <td><input type="text" name="question_text" required style="width: 100%;"></td>
            </tr>
        </table>
        <?php submit_button( __( 'Add Question', 'ifc-plugin' ) ); ?>
    </form>

    <h2><?php _e( 'All Questions', 'ifc-plugin' ); ?></h2>
    <?php
    $questions = $wpdb->get_results( "SELECT * FROM $table_questions ORDER BY id DESC" );

    if ( $questions ) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr><th>' . __( 'Question', 'ifc-plugin' ) . '</th><th>' . __( 'Entries', 'ifc-plugin' ) . '</th><th>' . __( 'Date', 'ifc-plugin' ) . '</th></tr></thead>';
        echo '<tbody>';
        foreach ( $questions as $question ) {

            // Get answers count
            $entries = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}ifc_answers WHERE question_id = %d", $question->id ) );

            $date = date_i18n( get_option( 'date_format' ), strtotime( $question->created_at ) );

            echo '<tr>';
            echo '<td class="ifc-question-cell">';
            echo '<span class="question-text">' . esc_html( $question->question ) . '</span>';
            ?>
            <div class="row-actions">
                <a href="<?php echo admin_url( 'admin.php?page=ifc-plugin&action=edit&question_id=' . $question->id ); ?>"><?php _e( 'Edit', 'ifc-plugin' ); ?></a> |

                <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" style="display:inline;">
                    <?php
                    wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' );
                    ?>
                    <input type="hidden" name="action" value="ifc_delete_question">
                    <input type="hidden" name="ifc_action" value="delete_question">
                    <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                    <button type="submit" onclick="return confirm('<?php _e( 'Do you really want to delete this question?', 'ifc-plugin' ); ?>');" style="background:none;border:none;padding:0;color:#0073aa;cursor:pointer;"><?php _e( 'Delete', 'ifc-plugin' ); ?></button> |
                </form>

                <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" style="display:inline;">
                    <?php
                    wp_nonce_field( 'ifc_admin_action', 'ifc_admin_nonce' );
                    ?>
                    <input type="hidden" name="action" value="ifc_delete_answers">
                    <input type="hidden" name="ifc_action" value="delete_answers">
                    <input type="hidden" name="question_id" value="<?php echo esc_attr( $question->id ); ?>">
                    <button type="submit" onclick="return confirm('<?php _e( 'Do you really want to delete all answers for this question?', 'ifc-plugin' ); ?>');" style="background:none;border:none;padding:0;color:#0073aa;cursor:pointer;"><?php _e( 'Delete All Answers', 'ifc-plugin' ); ?></button>
                </form>
            </div>
            <?php
            echo '</td>';
            echo '<td>' . esc_html( $entries ) . '</td>';
            echo '<td>' . esc_html( $date ) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>' . __( 'No questions found.', 'ifc-plugin' ) . '</p>';
    }
    ?>
</div>
