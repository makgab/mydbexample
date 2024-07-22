<?php

/**
 * @file
 * A form to Product delete.
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;


class ProductDeleteForm extends ConfirmFormBase {

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
    return 'mydbexample_product_delete_form';
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
    return new Url('mydbexample.product');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete Product: %id?', ['%id' => $this->id]);
  }



  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      // delete query execute
      $query_del_num = \Drupal::database()->delete('mydbexample_product')
        ->condition('pid', $this->id, '=')
        ->execute();

      // Provide the form submitter a nice message.
      if ( $query_del_num > 0 ) {
        \Drupal::messenger()->addMessage(
          t('Product deleted!')
        );
      } else {
        \Drupal::messenger()->addError(
          t('Unable to delete the Product. Please try again.')
        );
      }
      // redirect to route
      $form_state->setRedirect('mydbexample.product');

    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to delete the Product. Please try again.')
      );
      \Drupal::messenger()->addError( $e->getMessage() );
    }

  }

}
