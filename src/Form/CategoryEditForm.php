<?php

/**
 * @file
 * A form to Category edit.
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CategoryEditForm extends FormBase {

  /**
   * ID of the item to edit.
   *
   * @var int
   */
  protected $id;


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'mydbexample_category_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;
    // selected item
    $database = \Drupal::database();
    $select_query = $database->select('mydbexample_category', 'c');
    // fields
    $select_query->addField('c', 'catid');
    $select_query->addField('c', 'catname');
    $select_query->condition('c.catid', $this->id, '=');
    $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
    foreach ( $entries as $r ) {
      $catname = $r['catname'];
    }

    // Form
    $form['catname'] = [
      '#type' => 'textfield',
      '#title' => t('Category Name'),
      '#size' => 25,
      '#description' => t("Category Name."),
      '#required' => TRUE,
      '#default_value' => $catname,
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
      $query = \Drupal::database()->update('mydbexample_category');

      // values
      $catname = $form_state->getValue('catname');
    
      // Specify the fields that the query will updated.
      $query->fields([
        'catname' => $catname,
        ])
        ->condition('catid', $this->id, '=');
      // Execute the query!
      $query->execute();

      // Provide the form submitter a nice message.
      \Drupal::messenger()->addMessage(
        t('Category Updated!')
      );
      // redirect to route
      $form_state->setRedirect('mydbexample.category');
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save the Category. Please try again.')
      );
      \Drupal::messenger()->addError( $e->getMessage() );
    }

  }

}
