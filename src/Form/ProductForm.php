<?php

/**
 * @file
 * A form to Product.
 */

namespace Drupal\mydbexample\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ProductForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'mydbexample_product_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // query for category
    $query = \Drupal::database()->select('mydbexample_category', 'c');
    $query->addField('c', 'catid');
    $query->addField('c', 'catname');
    $cats = $query->execute()->fetchAll(\PDO::FETCH_KEY_PAIR);

    // Form
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Product Name'),
      '#size' => 25,
      '#description' => t("Product Name."),
      '#required' => TRUE,
    ];
    $form['price'] = [
        '#type' => 'textfield',
        '#title' => t('Price'),
        '#size' => 25,
        '#description' => t("Price."),
        '#required' => TRUE,
      ];
    $form['catid'] = [
        '#type' => 'select',
        '#title' => t('Category'),
        '#options' => $cats,
        '#description' => t("Category."),
        '#required' => TRUE,
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
      $query = \Drupal::database()->insert('mydbexample_product');

      // values
      $name = $form_state->getValue('name');
      $price = $form_state->getValue('price');
      $catid = $form_state->getValue('catid');
    
      // Specify the fields that the query will insert into.
      $query->fields([
        'name',
        'price',
        'catid',
      ]);
      // Set the values of the fields we selected.
      $query->values([
        $name,
        $price,
        $catid,
      ]);

      // Execute the query!
      // Drupal handles the exact syntax of the query automatically!
      $query->execute();

      // Provide the form submitter a nice message.
      \Drupal::messenger()->addMessage(
        t('Product Inserted!')
      );
      // End Phase 3
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save the Product. Please try again.')
      );
      \Drupal::messenger()->addError( $e->getMEssage() );
    }

  }



}
