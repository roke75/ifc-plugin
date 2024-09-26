<?php
/*
Plugin Name: Instant Feedback Collector Plugin
Description: Ask question and get instant feedback
Version: 1.0
Author: Jarkko Roininen
Text Domain: ifc-plugin
Domain Path: /languages
*/

// Prevent direct access to this file
if (!defined('ABSPATH')) exit;

// Create database tables when plugin is activated
register_activation_hook(__FILE__, 'ifc_create_tables');
function ifc_create_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Table for questions
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $sql_questions = "CREATE TABLE $table_questions (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        question text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
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

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_questions);
    dbDelta($sql_answers);
}

// Add admin menu
add_action('admin_menu', 'ifc_add_admin_menu');
function ifc_add_admin_menu()
{
    add_menu_page(
        'Instant Feedback Collector Plugin',
        'Instant Feedback Collector',
        'manage_options',
        'ifc-plugin',
        'ifc_admin_page',
        'dashicons-editor-help',
        6
    );
}

add_action('plugins_loaded', 'ifc_plugin_load_textdomain');
function ifc_plugin_load_textdomain()
{
    load_plugin_textdomain('ifc-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// Käsittele POST-pyynnöt admin_post_* hookkien avulla
add_action('admin_post_ifc_add_question', 'ifc_handle_add_question');
add_action('admin_post_ifc_edit_question', 'ifc_handle_edit_question');
add_action('admin_post_ifc_delete_question', 'ifc_handle_delete_question');
add_action('admin_post_ifc_delete_answers', 'ifc_handle_delete_answers');

// Lisää uusi kysymys
function ifc_handle_add_question() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    check_admin_referer('ifc_admin_action', 'ifc_admin_nonce');

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';

    if (isset($_POST['ifc_action']) && $_POST['ifc_action'] == 'add_question' && !empty($_POST['question_text'])) {
        $question_text = sanitize_text_field($_POST['question_text']);
        $wpdb->insert(
            $table_questions,
            array('question' => $question_text)
        );

        // Uudelleenohjaus onnistumisen jälkeen
        wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=add'));
        exit;
    }

    // Jos jotain meni pieleen
    wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=error'));
    exit;
}

// Muokkaa olemassa olevaa kysymystä
function ifc_handle_edit_question() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    check_admin_referer('ifc_admin_action', 'ifc_admin_nonce');

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';

    if (isset($_POST['ifc_action']) && $_POST['ifc_action'] == 'edit_question' && !empty($_POST['question_text']) && isset($_POST['question_id'])) {
        $question_text = sanitize_text_field($_POST['question_text']);
        $question_id = intval($_POST['question_id']);
        $updated = $wpdb->update(
            $table_questions,
            array('question' => $question_text),
            array('id' => $question_id)
        );

        if ($updated !== false) {
            // Uudelleenohjaus onnistumisen jälkeen
            wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=edit'));
            exit;
        }
    }

    // Jos jotain meni pieleen
    wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=error'));
    exit;
}

// Poista kysymys ja siihen liittyvät vastaukset
function ifc_handle_delete_question() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    check_admin_referer('ifc_admin_action', 'ifc_admin_nonce');

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';

    if (isset($_POST['ifc_action']) && $_POST['ifc_action'] == 'delete_question' && isset($_POST['question_id'])) {
        $question_id = intval($_POST['question_id']);

        // Poista vastaukset
        $wpdb->delete(
            $wpdb->prefix . 'ifc_answers',
            array('question_id' => $question_id)
        );

        // Poista kysymys
        $wpdb->delete(
            $table_questions,
            array('id' => $question_id)
        );

        // Uudelleenohjaus
        wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=delete'));
        exit;
    }

    // Jos jotain meni pieleen
    wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=error'));
    exit;
}

// Poista kaikki vastaukset kysymykselle
function ifc_handle_delete_answers() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    check_admin_referer('ifc_admin_action', 'ifc_admin_nonce');

    global $wpdb;
    $table_answers = $wpdb->prefix . 'ifc_answers';

    if (isset($_POST['ifc_action']) && $_POST['ifc_action'] == 'delete_answers' && isset($_POST['question_id'])) {
        $question_id = intval($_POST['question_id']);

        // Poista vastaukset
        $wpdb->delete(
            $table_answers,
            array('question_id' => $question_id)
        );

        // Uudelleenohjaus
        wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=delete_answers'));
        exit;
    }

    // Jos jotain meni pieleen
    wp_redirect(admin_url('admin.php?page=ifc-plugin&updated=error'));
    exit;
}

function ifc_admin_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';

    // Näytä onnistumis- tai virheilmoitukset `updated`-parametrin perusteella
    if (isset($_GET['updated'])) {
        $updated = sanitize_text_field($_GET['updated']);
        switch ($updated) {
            case 'add':
                echo '<div class="updated"><p>' . __('Question added successfully.', 'ifc-plugin') . '</p></div>';
                break;
            case 'edit':
                echo '<div class="updated"><p>' . __('Question updated successfully.', 'ifc-plugin') . '</p></div>';
                break;
            case 'delete':
                echo '<div class="updated"><p>' . __('Question and its answers deleted successfully.', 'ifc-plugin') . '</p></div>';
                break;
            case 'delete_answers':
                echo '<div class="updated"><p>' . __('All answers for this question have been deleted.', 'ifc-plugin') . '</p></div>';
                break;
            case 'error':
                echo '<div class="error"><p>' . __('An error occurred. Please try again.', 'ifc-plugin') . '</p></div>';
                break;
            default:
                // Do nothing
                break;
        }
    }

    // 1. Käsittele POST-pyynnöt ensin (siirretty admin_post_* hookkeihin)

    // 2. Käsittele GET-parametrit, kuten `action=edit`
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['question_id'])) {
        $question_id = intval($_GET['question_id']);
        $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_questions WHERE id = %d", $question_id));

        if ($question) {
            // Näytä muokkauslomake
            ?>
            <div class="wrap">
                <h1><?php _e('Edit Question', 'ifc-plugin'); ?></h1>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php
                    // Nonce ja action
                    wp_nonce_field('ifc_admin_action', 'ifc_admin_nonce');
                    ?>
                    <input type="hidden" name="action" value="ifc_edit_question">
                    <input type="hidden" name="ifc_action" value="edit_question">
                    <input type="hidden" name="question_id" value="<?php echo esc_attr($question->id); ?>">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('Question', 'ifc-plugin'); ?></th>
                            <td><input type="text" name="question_text" value="<?php echo esc_attr($question->question); ?>" required style="width: 100%;"></td>
                        </tr>
                    </table>
                    <?php submit_button(__('Update Question', 'ifc-plugin')); ?>
                </form>
            </div>
            <?php
            // Lopeta funktion suoritus, jotta kysymysten listaus ei näy
            return;
        } else {
            echo '<div class="error"><p>' . __('Question not found.', 'ifc-plugin') . '</p></div>';
        }
    }

    // 3. Näytä kysymysten hallintataulukko
    ?>
    <div class="wrap">
        <h1>Instant Feedback Collector Plugin - <?php _e('Manage Questions', 'ifc-plugin'); ?></h1>
        <h2><?php _e('Add Question', 'ifc-plugin'); ?></h2>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php
            // Nonce ja action
            wp_nonce_field('ifc_admin_action', 'ifc_admin_nonce');
            ?>
            <input type="hidden" name="action" value="ifc_add_question">
            <input type="hidden" name="ifc_action" value="add_question">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Question', 'ifc-plugin'); ?></th>
                    <td><input type="text" name="question_text" required style="width: 100%;"></td>
                </tr>
            </table>
            <?php submit_button(__('Add Question', 'ifc-plugin')); ?>
        </form>

        <h2><?php _e('All Questions', 'ifc-plugin'); ?></h2>
        <?php
        $questions = $wpdb->get_results("SELECT * FROM $table_questions ORDER BY id DESC");

        if ($questions) {
            echo '<table class="widefat fixed striped">';
            echo '<thead><tr><th>' . __('Question', 'ifc-plugin') . '</th><th>' . __('Entries', 'ifc-plugin') . '</th><th>' . __('Date', 'ifc-plugin') . '</th></tr></thead>';
            echo '<tbody>';
            foreach ($questions as $question) {
                // Hae vastausten määrä
                $entries = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}ifc_answers WHERE question_id = %d", $question->id));

                // Hae luomisajankohta
                $date = date_i18n(get_option('date_format'), strtotime($question->created_at));

                echo '<tr>';
                echo '<td class="ifc-question-cell">';
                echo '<span class="question-text">' . esc_html($question->question) . '</span>';
                ?>
                <div class="row-actions">
                    <!-- Edit link -->
                    <a href="<?php echo admin_url('admin.php?page=ifc-plugin&action=edit&question_id=' . $question->id); ?>"><?php _e('Edit', 'ifc-plugin'); ?></a> |

                    <!-- Delete question -->
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display:inline;">
                        <?php
                        wp_nonce_field('ifc_admin_action', 'ifc_admin_nonce');
                        ?>
                        <input type="hidden" name="action" value="ifc_delete_question">
                        <input type="hidden" name="ifc_action" value="delete_question">
                        <input type="hidden" name="question_id" value="<?php echo esc_attr($question->id); ?>">
                        <button type="submit" onclick="return confirm('<?php _e('Do you really want to delete this question?', 'ifc-plugin'); ?>');" style="background:none;border:none;padding:0;color:#0073aa;cursor:pointer;"><?php _e('Delete', 'ifc-plugin'); ?></button> |
                    </form>

                    <!-- Delete all answers -->
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display:inline;">
                        <?php
                        wp_nonce_field('ifc_admin_action', 'ifc_admin_nonce');
                        ?>
                        <input type="hidden" name="action" value="ifc_delete_answers">
                        <input type="hidden" name="ifc_action" value="delete_answers">
                        <input type="hidden" name="question_id" value="<?php echo esc_attr($question->id); ?>">
                        <button type="submit" onclick="return confirm('<?php _e('Do you really want to delete all answers for this question?', 'ifc-plugin'); ?>');" style="background:none;border:none;padding:0;color:#0073aa;cursor:pointer;"><?php _e('Delete All Answers', 'ifc-plugin'); ?></button>
                    </form>
                </div>
                <?php
                echo '</td>';
                echo '<td>' . esc_html($entries) . '</td>';
                echo '<td>' . esc_html($date) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>' . __('No questions found.', 'ifc-plugin') . '</p>';
        }
        ?>
    </div>
    <?php
}

// Register shortcode
add_shortcode('ifc', 'ifc_shortcode');
function ifc_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'ifc');

    $question_id = intval($atts['id']);

    if ($question_id <= 0) {
        return __('Invalid question ID.', 'ifc-plugin');
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_questions WHERE id = %d", $question_id));

    if (! $question) {
        return 'Question not found.';
    }

    ob_start();
    // Handle form submission
    if (isset($_POST['ifc_submit']) && isset($_POST['ifc_answer']) && wp_verify_nonce($_POST['ifc_nonce'], 'ifc_nonce_action')) {
        global $wpdb;
        $table_answers = $wpdb->prefix . 'ifc_answers';
        $answer = sanitize_text_field($_POST['ifc_answer']);
        $wpdb->insert(
            $table_answers,
            array(
                'question_id' => $question_id,
                'answer' => $answer,
            )
        );
        // setcookie('ifc_answered_' . $question_id, '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
        echo '<div class="alert alert-success" role="alert">' . __('Thank you for your answer.', 'ifc-plugin') . '</div>';
    } else {
        // if (isset($_COOKIE['ifc_answered_' . $question_id])) {
        //     echo '<p>You have already answered this question.</p>';
        // } else {
    ?>

        <form method="post" class="ifc-form">
            <div class="form-group">
                <label for="ifcTextarea"><?php echo esc_html($question->question); ?></label>
                <textarea class="form-control" id="ifcTextarea" name="ifc_answer" required></textarea>
            </div>
            <?php wp_nonce_field('ifc_nonce_action', 'ifc_nonce'); ?>
            <button type="submit" name="ifc_submit" class="btn btn-primary">Send</button>
        </form>
    <?php
        // }
    }
    return ob_get_clean();
}

// Register shortcode
add_shortcode('ifc_results', 'ifc_results_shortcode');
function ifc_results_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'id' => 0,
        'view' => 'default', // 'default' tai 'word_cloud'
    ), $atts, 'ifc_results');

    $question_id = intval($atts['id']);

    if ($question_id <= 0) {
        return __('Invalid question ID.', 'ifc-plugin');
    }

    $view = sanitize_text_field($atts['view']);

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_questions WHERE id = %d", $question_id));

    if (! $question) {
        return __('Question not found.', 'ifc-plugin');
    }

    // Display answers
    ob_start();
    if ($view == 'word_cloud') {
        ?>
        <div class="ifc-word-cloud-container">
            <h3><?php echo esc_html($question->question); ?></h3>
            <div id="ifc-word-cloud-<?php echo esc_attr($question_id); ?>" width="600" height="400"></div>
        </div>
        <?php
    } else {
    ?>
        <div class="ifc-results-container">
            <h3><?php echo esc_html($question->question); ?></h3>
            <div id="ifc-answers" data-question-id="<?php echo esc_attr($question_id); ?>" class="container">
                <div class="row" id="answers-row">
                    <!-- Answers will be displayed here -->
                </div>
            </div>
        </div>
    <?php
    }

    return ob_get_clean();
}

// AJAX request handlers
add_action('wp_ajax_ifc_update_answers', 'ifc_update_answers');
add_action('wp_ajax_nopriv_ifc_update_answers', 'ifc_update_answers');
function ifc_update_answers()
{
    // Check AJAX nonce
    check_ajax_referer('ifc_ajax_nonce', 'nonce');

    // Get question ID and last answer ID
    $last_id = isset($_POST['last_id']) ? intval($_POST['last_id']) : 0;
    $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;

    if ($question_id <= 0) {
        wp_send_json_error(array('message' => __('Invalid question ID.', 'ifc-plugin')));
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_questions WHERE id = %d", $question_id));

    if (! $question) {
        wp_send_json_error(array('message' => __('Question not found.', 'ifc-plugin')));
    }

    // Get new answers
    $table_answers = $wpdb->prefix . 'ifc_answers';
    $answers = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_answers WHERE question_id = %d AND id > %d ORDER BY id ASC",
        $question_id,
        $last_id
    ));

    $response = array(
        'status' => 'success',
        'answers' => array(),
        'latest_id' => $last_id,
    );

    if ($answers) {
        foreach ($answers as $answer) {
            // Create answer HTML
            ob_start();
    ?>
            <div class="col-md-4 col-sm-6 ifc-answer<?php echo $new_class; ?>" data-id="<?php echo esc_attr($answer->id); ?>">
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text"><?php echo esc_html($answer->answer); ?></p>
                    </div>
                </div>
            </div>
<?php
            $answer_html = ob_get_clean();

            $response['answers'][] = $answer_html;
            $response['latest_id'] = max($response['latest_id'], $answer->id);
        }
    }

    wp_send_json($response);
}

// AJAX-käsittelijä sanapilvelle
add_action('wp_ajax_ifc_get_word_cloud_data', 'ifc_get_word_cloud_data');
add_action('wp_ajax_nopriv_ifc_get_word_cloud_data', 'ifc_get_word_cloud_data');

function ifc_get_word_cloud_data()
{
    // Tarkista nonce
    check_ajax_referer('ifc_ajax_nonce', 'nonce');

    // Hae kysymys ID
    $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;

    if ($question_id <= 0) {
        wp_send_json_error(array('message' => __('Invalid question ID.', 'ifc-plugin')));
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_questions WHERE id = %d", $question_id));

    if (!$question) {
        wp_send_json_error(array('message' => __('Question not found.', 'ifc-plugin')));
    }

    // Hae kaikki vastaukset kyseiselle kysymykselle
    $table_answers = $wpdb->prefix . 'ifc_answers';
    $answers = $wpdb->get_col($wpdb->prepare("SELECT answer FROM $table_answers WHERE question_id = %d", $question_id));

    // Prosessoi sanat
    $word_counts = array();
    $stop_words = array('ja', 'on', 'että', 'tämä', 'se', 'mutta', 'niin', 'tai', 'jos', 'kuten', 'kuitenkin', 'koska', 'jotta', 'vaan', 'kun', 'mikä', 'missä', 'mitä', 'milloin', 'jopa', 'sillä'); // Lisää tarpeen mukaan

    foreach ($answers as $answer) {
        // Jaa sanat
        $words = preg_split('/\W+/u', strtolower($answer), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($words as $word) {
            if (strlen($word) > 2 && !in_array($word, $stop_words)) { // Vältä liian lyhyet ja stop-sanat
                if (isset($word_counts[$word])) {
                    $word_counts[$word]++;
                } else {
                    $word_counts[$word] = 1;
                }
            }
        }
    }

    // Muodosta data jQCloudille
    $word_cloud_data = array();
    foreach ($word_counts as $word => $count) {
        $word_cloud_data[] = array(
            'text' => $word,
            'weight' => $count,
        );
    }
    header('Content-Type: application/json; charset=UTF-8');
    wp_send_json_success(array(
        'word_cloud_data' => $word_cloud_data,
    ));}

// AJAX-käsittelijä sanapilven päivitykselle
add_action('wp_ajax_ifc_update_word_cloud', 'ifc_update_word_cloud');
add_action('wp_ajax_nopriv_ifc_update_word_cloud', 'ifc_update_word_cloud');

function ifc_update_word_cloud()
{
    // Tarkista nonce
    check_ajax_referer('ifc_ajax_nonce', 'nonce');

    // Hae kysymys ID
    $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;

    if ($question_id <= 0) {
        wp_send_json_error(array('message' => __('Invalid question ID.', 'ifc-plugin')));
    }

    global $wpdb;
    $table_questions = $wpdb->prefix . 'ifc_questions';
    $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_questions WHERE id = %d", $question_id));

    if (!$question) {
        wp_send_json_error(array('message' => __('Question not found.', 'ifc-plugin')));
    }

    // Hae kaikki vastaukset kyseiselle kysymykselle
    $table_answers = $wpdb->prefix . 'ifc_answers';
    $answers = $wpdb->get_col($wpdb->prepare("SELECT answer FROM $table_answers WHERE question_id = %d", $question_id));

    // Prosessoi sanat
    $word_counts = array();
    $stop_words = array('ja', 'on', 'että', 'tämä', 'se', 'mutta', 'niin', 'tai', 'jos', 'kuten', 'kuitenkin', 'koska', 'jotta', 'vaan', 'kun', 'mikä', 'missä', 'mitä', 'milloin', 'jopa', 'sillä'); // Lisää tarpeen mukaan

    foreach ($answers as $answer) {
        // Jaa sanat
        $words = preg_split('/\W+/u', strtolower($answer), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($words as $word) {
            if (strlen($word) > 2 && !in_array($word, $stop_words)) { // Vältä liian lyhyet ja stop-sanat
                if (isset($word_counts[$word])) {
                    $word_counts[$word]++;
                } else {
                    $word_counts[$word] = 1;
                }
            }
        }
    }

    // Muodosta data jQCloudille
    $word_cloud_data = array();
    foreach ($word_counts as $word => $count) {
        $word_cloud_data[] = array(
            'text' => $word,
            'weight' => $count,
        );
    }
    header('Content-Type: application/json; charset=UTF-8');
    wp_send_json_success(array(
        'word_cloud_data' => $word_cloud_data,
    ));
}

// Enqueue styles and scripts
add_action('wp_enqueue_scripts', 'ifc_load_styles');
function ifc_load_styles()
{
    // Load Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    wp_enqueue_style('ifc', plugin_dir_url(__FILE__) . '/css/ifc.css');

    // Load jQuery in case it's not already loaded
    wp_enqueue_script('jquery');

    // Load Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);

    // Enqueue D3.js
    // wp_enqueue_script('d3-js', 'https://d3js.org/d3.v6.min.js', array(), '7.9.0', true);
    // wp_enqueue_script('d3-cloud', 'https://unpkg.com/d3-cloud/build/d3.layout.cloud.js', array('d3-js'), '1.2.7', true);

    // Enqueue jQCloud CSS ja JS
    wp_enqueue_style('jqcloud-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud.min.css');
    wp_enqueue_script('jqcloud-js', 'https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud.min.js', array('jquery'), '1.0.4', true);

    // Load the script
    wp_enqueue_script('ifc', plugin_dir_url(__FILE__) . '/js/ifc.js', array('jquery'), null, true);

    // Localize the script with the AJAX URL
    wp_localize_script('ifc', 'ifc_ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ifc_ajax_nonce'),
    ));
}
