<?php

/**
 * @file
 * A form to Category.
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CategoryForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'mydbexample_category_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // Form
    $form['catname'] = [
      '#type' => 'textfield',
      '#title' => t('Category Name'),
      '#size' => 25,
      '#description' => t("Category Name."),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Category Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $catname = $form_state->getValue('catname');
    if ( empty($catname) ) {
      $form_state->setErrorByName('catname', $this->t('It appears the %name field is empty. Please try again',
                                                    ['%name' => $catname]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    try {
      $query = \Drupal::database()->insert('mydbexample_category');

      // values
      $catname = $form_state->getValue('catname');
    
      // Specify the fields that the query will insert into.
      $query->fields([
        'catname',
      ]);
      // Set the values of the fields we selected.
      $query->values([
        $catname,
      ]);

      // Execute the query!
      // Drupal handles the exact syntax of the query automatically!
      $query->execute();

      // Provide the form submitter a nice message.
      \Drupal::messenger()->addMessage(
        t('Category Inserted!')
      );
      // End Phase 3
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save the Category. Please try again.')
      );
      \Drupal::messenger()->addError( $e->getMessage() );
    }

  }

}
