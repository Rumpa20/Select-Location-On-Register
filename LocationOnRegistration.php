<?php
// LocationOnRegistration.php

if (!defined('SMF')) {
    die('Hacking attempt...');
}

// Hook into the registration process
function location_on_registration()
{
    global $context, $txt;

    // Define the custom field
    $context['custom_fields'][] = array(
        'name' => 'Location',
        'desc' => 'Please enter your location.',
        'input_html' => '<input type="text" name="location" value="' . (isset($_POST['location']) ? htmlspecialchars($_POST['location'], ENT_QUOTES) : '') . '" />',
        'show_reg' => 2,
        'is_error' => !empty($context['registration_errors']['location']),
    );
}

// Hook into the registration display
function location_on_registration_display()
{
    global $context;

    foreach ($context['custom_fields'] as $field) {
        if ($field['show_reg'] > 1) {
            echo '
                <dt>
                    <strong', !empty($field['is_error']) ? ' class="red"' : '', '>', $field['name'], ':</strong>
                    <span class="smalltext">', $field['desc'], '</span>
                </dt>
                <dd>', str_replace('name="', 'tabindex="' . $context['tabindex']++ . '" name="', $field['input_html']), '</dd>';
        }
    }
}

// Hook into the registration form submission
function location_on_registration_save($member_id)
{
    global $db, $smcFunc;

    // Check if the location field is set
    if (isset($_POST['location'])) {
        $location = $_POST['location'];

        // Insert or update the custom field value in the database
        $smcFunc['db_insert']('replace',
            '{db_prefix}custom_fields_data',
            array(
                'id_member' => 'int',
                'field_id' => 'int',
                'field_value' => 'string',
            ),
            array(
                $member_id,
                /* Replace with the actual field ID of your custom field */
                1, // Change this to your actual field ID
                $location,
            ),
            array('id_member', 'field_id')
        );
    }
}

// Register the hooks
add_integration_function('register', 'location_on_registration');
add_integration_function('register', 'location_on_registration_display');
add_integration_function('register', 'location_on_registration_save');
