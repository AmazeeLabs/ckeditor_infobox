<?php

/**
 * @file
 * Contains \Drupal\ckeditor_infobox\Plugin\CKEditorPlugin\Infobox.
 */

namespace Drupal\ckeditor_infobox\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "Infobox" plugin.
 *
 * @CKEditorPlugin(
 *   id = "infobox",
 *   label = @Translation("Infobox")
 * )
 */
class Infobox extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface, CKEditorPluginConfigurableInterface {

  /**
   * {@inheritdoc}
   */
  function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  function getLibraries(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  function getFile() {
    return drupal_get_path('module', 'ckeditor_infobox') . '/js/plugins/infobox/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  function getButtons() {
    return [
      'infobox' => [
        'label' => t('Infobox'),
        'image' => drupal_get_path('module', 'ckeditor_infobox') . '/js/plugins/infobox/icons/infobox.png',
      ]
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [
      'infobox' => [
        'classes' => $this->parseClasses($this->getClasses($editor)),
        'strings' => [
          'dialogTitle' => t('Infobox'),
          'buttonLabel' => t('Insert an Infobox'),
          'classSelectorLabel' => t('Class'),
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    $form['classes'] = [
      '#type' => 'textarea',
      '#title' => t('Classes'),
      '#description' => t('Classes available for infoboxes in "css-class-name|Label" format. Example:
<br /><code>tip|Tip
<br />info|Info
<br />warning|Warning</code>
<br />The actual CSS class will have the "infobox--" prefix.
'),
      '#required' => TRUE,
      '#element_validate' => [[$this, 'validateClasses']],
      '#default_value' => $this->getClasses($editor),
    ];
    return $form;
  }

  /**
   * Returns configured or default classes string.
   *
   * @param \Drupal\editor\Entity\Editor $editor
   *
   * @return string
   */
  protected function getClasses(Editor $editor) {
    $settings = $editor->getSettings();
    return isset($settings['plugins']['infobox']['classes']) ? $settings['plugins']['infobox']['classes'] : 'tip|Tip
info|Info
warning|Warning';
  }

  /**
   * Validation handler for the "classes" form element.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateClasses(array $element, FormStateInterface $form_state) {
    $classes =& $form_state->getValue(['editor', 'settings', 'plugins', 'infobox', 'classes']);
    $classes = $this->composeClasses($this->parseClasses($classes));
  }

  /**
   * Parses classes string to format required by CKEditor select items.
   *
   * Additionally, cleans up data and removes invalid one.
   *
   * @see http://docs.ckeditor.com/#!/api/CKEDITOR.dialog.definition.select-property-items
   *
   * @param string $classes_string
   *
   * @return array
   */
  protected function parseClasses($classes_string) {
    $classes_array = [];
    foreach (explode("\n", $classes_string) as $row) {
      @list($class, $label) = array_map('trim', explode('|', $row));
      $class = Html::cleanCssIdentifier($class);
      if (!empty($class) && !empty($label)) {
        $classes_array[] = [$label, $class];
      }
    }
    return $classes_array;
  }

  /**
   * Combines classes string from classes array.
   *
   * @see parseClasses()
   *
   * @param array $classes_array
   *
   * @return string
   */
  protected function composeClasses($classes_array) {
    foreach ($classes_array as &$row) {
      $row = $row[1] . '|' . $row[0];
    }
    return implode("\n", $classes_array);
  }

}
