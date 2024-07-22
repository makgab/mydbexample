<?php

/**
 * @file
 * Provide report category
 */

namespace Drupal\mydbexample\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportCategoryController extends ControllerBase {

  /**
   * Gets Category
   *
   * @return array|null
   */
  protected function load() {
    try {

      // https://www.drupal.org/docs/8/api/database-api/dynamic-
      //         queries/introduction-to-dynamic-queries
      $database = \Drupal::database();
      $select_query = $database->select('mydbexample_category', 'c');

      // fields
      $select_query->addField('c', 'catid');
      $select_query->addField('c', 'catname');

      $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);

      // Return the associative array of entries.
      return $entries;
    }
    catch (\Exception $e) {
      // Display a user-friendly error.
      \Drupal::messenger()->addStatus(
        t('Unable to access the database at this time. Please try again later.')
      );
      return NULL;
    }
  }

  /**
   * Creates the report page.
   *
   * @return array
   *  Render array for the report output.
   */
  public function report() {
    $content = [];

    $content['message'] = [
      '#markup' => t('Below is a list of Categories.'),
    ];

    $headers = [
      t('Category ID'),
      t('Category Name'),
    ];

    // Because load() returns an associative array with each table row
    // as its own array, we can simply define the HTML table rows like this:
    $table_rows = $this->load();

    // Create the render array for rendering an HTML table.
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => t('No entries available.'),
    ];

    // Do not cache this page by setting the max-age to 0.
    $content['#cache']['max-age'] = 0;

    // Return the populated render array.
    return $content;
  }
}
