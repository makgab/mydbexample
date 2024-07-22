<?php

/**
 * @file
 * A form to Product edit.
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ProductEditForm extends FormBase {

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
    return 'mydbexample_product_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;
    // query for category
    $query = \Drupal::database()->select('mydbexample_category', 'c');
    $query->addField('c', 'catid');
    $query->addField('c', 'catname');
    $cats = $query->execute()->fetchAll(\PDO::FETCH_KEY_PAIR);

    // selected item
    $database = \Drupal::database();
    $select_query = $database->select('mydbexample_product', 'p');
    // fields
    $select_query->addField('p', 'pid');
    $select_query->addField('p', 'name');
    $select_query->addField('p', 'price');
    $select_query->addField('p', 'catid');
    $select_query->condition('p.pid', $this->id, '=');
    $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
    foreach ( $entries as $r ) {
      $name = $r['name'];
      $price = $r['price'];
      $catid = $r['catid'];
    }

    // Form
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Product Name'),
      '#size' => 25,
      '#description' => t("Product Name."),
      '#required' => TRUE,
      '#default_value' => $name,
      ];
    $form['price'] = [
        '#type' => 'textfield',
        '#title' => t('Price'),
        '#size' => 25,
        '#description' => t("Price."),
        '#required' => TRUE,
        '#default_value' => $price,
      ];
    $form['catid'] = [
        '#type' => 'select',
        '#title' => t('Category'),
        '#options' => $cats,
        '#description' => t("Category."),
        '#required' => TRUE,
        '#default_value' => $catid,
      ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Product Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');
    if ( empty($name) ) {
      $form_state->setErrorByName('name', $this->t('It appears the %name field is empty. Please try again',
                                                    ['%name' => $catname]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    try {
      $query = \Drupal::database()->update('mydbexample_product');

      // values
      $name = $form_state->getValue('name');
      $price = $form_state->getValue('price');
      $catid = $form_state->getValue('catid');
    
      // Specify the fields that the query will updated.
      $query->fields([
        'name' => $name,
        'price' => $price,
        'catid' => $catid,
        ])
        ->condition('pid', $this->id, '=');
      // Execute the query!
      $query->execute();

      // Provide the form submitter a nice message.
      \Drupal::messenger()->addMessage(
        t('Product Updated!')
      );
      // redirect to route
      $form_state->setRedirect('mydbexample.product');
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save the Product. Please try again.')
      );
      \Drupal::messenger()->addError( $e->getMessage() );
    }

  }

}
