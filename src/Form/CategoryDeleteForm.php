<?php

/**
 * @file
 * A form to Category delete.
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;


class CategoryDeleteForm extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected $id;


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'mydbexample_category_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('mydbexample.category');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete Category: %id?', ['%id' => $this->id]);
  }




  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      // delete query execute
      $query_del_num = \Drupal::database()->delete('mydbexample_category')
        ->condition('catid', $this->id, '=')
        ->execute();

      // Provide the form submitter a nice message.
      if ( $query_del_num > 0 ) {
        \Drupal::messenger()->addMessage(
          t('Category deleted!')
        );
      } else {
        \Drupal::messenger()->addError(
          t('Unable to delete the Category. Please try again.')
        );
      }
      // redirect to route
      $form_state->setRedirect('mydbexample.category');

    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to delete the Category. Please try again.')
      );
      \Drupal::messenger()->addError( $e->getMessage() );
    }

  }

}
