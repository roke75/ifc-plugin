<?php
// includes/settings-page.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get current settings or defaults
$poll_interval      = get_option( 'ifc_poll_interval', 5000 );
$word_cloud_width   = get_option( 'ifc_word_cloud_width', 600 );
$word_cloud_height  = get_option( 'ifc_word_cloud_height', 400 );
$stop_words         = get_option( 'ifc_stop_words', 'and, or, the, a, an, is, was, as, in, of, to, for, on, at, by, with, from, ja, on, että, tämä, se, mutta, niin, tai, jos, kuten, kuitenkin, koska, jotta, vaan, kun, mikä, missä, mitä, milloin, jopa, sillä' );
$min_word_length    = get_option( 'ifc_min_word_length', 2 );
?>

<div class="wrap">
    <h1><?php _e( 'Instant Feedback Collector - Settings', 'ifc-plugin' ); ?></h1>

    <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Settings saved successfully.', 'ifc-plugin' ); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'ifc_plugin_settings' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="ifc_poll_interval"><?php _e( 'Poll Interval (milliseconds)', 'ifc-plugin' ); ?></label>
                </th>
                <td>
                    <input type="number" id="ifc_poll_interval" name="ifc_poll_interval" value="<?php echo esc_attr( $poll_interval ); ?>" min="1000" max="60000" step="1000" class="regular-text">
                    <p class="description"><?php _e( 'How often to check for new answers (1000-60000ms). Default: 5000ms (5 seconds).', 'ifc-plugin' ); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="ifc_word_cloud_width"><?php _e( 'Word Cloud Width (pixels)', 'ifc-plugin' ); ?></label>
                </th>
                <td>
                    <input type="number" id="ifc_word_cloud_width" name="ifc_word_cloud_width" value="<?php echo esc_attr( $word_cloud_width ); ?>" min="200" max="2000" class="regular-text">
                    <p class="description"><?php _e( 'Width of the word cloud display. Default: 600px.', 'ifc-plugin' ); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="ifc_word_cloud_height"><?php _e( 'Word Cloud Height (pixels)', 'ifc-plugin' ); ?></label>
                </th>
                <td>
                    <input type="number" id="ifc_word_cloud_height" name="ifc_word_cloud_height" value="<?php echo esc_attr( $word_cloud_height ); ?>" min="200" max="2000" class="regular-text">
                    <p class="description"><?php _e( 'Height of the word cloud display. Default: 400px.', 'ifc-plugin' ); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="ifc_stop_words"><?php _e( 'Stop Words', 'ifc-plugin' ); ?></label>
                </th>
                <td>
                    <textarea id="ifc_stop_words" name="ifc_stop_words" rows="5" class="large-text"><?php echo esc_textarea( $stop_words ); ?></textarea>
                    <p class="description"><?php _e( 'Common words to exclude from word cloud (comma-separated). Includes English and Finnish stop words by default.', 'ifc-plugin' ); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="ifc_min_word_length"><?php _e( 'Minimum Word Length', 'ifc-plugin' ); ?></label>
                </th>
                <td>
                    <input type="number" id="ifc_min_word_length" name="ifc_min_word_length" value="<?php echo esc_attr( $min_word_length ); ?>" min="1" max="10" class="small-text">
                    <p class="description"><?php _e( 'Minimum number of characters for words to appear in word cloud. Default: 2.', 'ifc-plugin' ); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
