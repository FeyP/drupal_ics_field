<?php

namespace Drupal\px_calendar_download\Plugin\Field\FieldFormatter;

use Drupal\file\Entity\File;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'calendar_download_default_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "calendar_download_default_formatter",
 *   label = @Translation("Calendar download default formatter"),
 *   field_types = {
 *     "calendar_download_type"
 *   }
 * )
 */
class CalendarDownloadDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $formState) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $formState);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return mixed[]|null
   *   A render array for a link element.
   */
  protected function viewValue(FieldItemInterface $item) {
    $fileref = $item->get('fileref')->getValue();
    $file = File::load($fileref);
    if ($file) {
      $fileUrlObj = Url::fromUri(file_create_url($file->getFileUri()));
      $build = [
        '#type' => 'link',
        '#title' => $this->t('iCal Download'),
        '#url' => $fileUrlObj,
      ];
      return $build;
    }
    return NULL;
  }

}