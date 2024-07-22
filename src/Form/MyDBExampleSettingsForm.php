<?php

/**
 * @file
 * Contains the settings for administering the MyDBExample Form
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class MyDBExampleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mydbexample_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mydbexample.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config('mydbexample.settings');
    $form['mydbexample_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable MyDBExample collection for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('On the specified node types, an MyDBExample option will be available and can be enabled while the node is being edited.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selected_allowed_types = array_filter($form_state->getValue('mydbexample_types'));
    sort($selected_allowed_types);

    $this->config('mydbexample.settings')
      ->set('allowed_types', $selected_allowed_types)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
