<?php

/**
 * @file
 * Definition of Drupal\flexslider\Plugin\views\style\FlexSlider.
 */

namespace Drupal\flickity_views\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each item as a Flickity cell.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "flickity",
 *   title = "Flickity",
 *   help = @Translation("Displays rows as Flickity cells."),
 *   theme = "views_view_flickity",
 *   display_types = {"normal"}
 * )
 */
class Flickity extends StylePluginBase {

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;

  /**
   * Set default options
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    // Setup
    $options['flickity_cellSelector']['default'] = '';
    $options['flickity_initialIndex']['default'] = 2;
    $options['flickity_accessibility']['default'] = true;
    $options['flickity_setGallerySize']['default'] = true;
    $options['flickity_resize']['default'] = true;

    // Cell position
    $options['flickity_cellAlign']['default'] = 'center';
    $options['flickity_contain']['default'] = true;
    $options['flickity_imagesLoaded']['default'] = true;
    $options['flickity_percentPosition']['default'] = true;
    $options['flickity_rightToLeft']['default'] = false;

    // Behavior
    $options['flickity_draggable']['default'] = true;
    $options['flickity_freeScroll']['default'] = false;
    $options['flickity_wrapAround']['default'] = false;
    $options['flickity_lazyLoad']['default'] = false;
    $options['flickity_autoPlay']['default'] = '';
    $options['flickity_pauseAutoPlayOnHover']['default'] = true;
    $options['flickity_watchCSS']['default'] = false;
    $options['flickity_asNavFor']['default'] = '';
    $options['flickity_selectedAttraction']['default'] = 0.025;
    $options['flickity_friction']['default'] = 0.28;
    $options['flickity_freeScrollFriction']['default'] = 0.075;

    // UI
    $options['flickity_prevNextButtons']['default'] = true;
    $options['flickity_pageDots']['default'] = true;
    $options['flickity_arrowShape']['default'] = '';

    return $options;
  }

  /**
   * Return only non-empty, non-default option values.
   *
   * @return array
   */
  public function filterOptions() {
    $options = array();
    $defaults = $this->defineOptions();

    foreach ($this->options as $key => $value) {
      if (strpos($key, 'flickity_') === 0) {
        $value = $this->getFilteredOptionValue($value, $defaults[$key]['default']);
        if (!is_null($value)) {
          $options[substr($key, 9)] = $value;
        }
      }
    }

    return $options;
  }

  /**
   * Returns an appropriately typed option value, or NULL if it matches the default.
   *
   * @param mixed $value
   * @param mixed $default
   *
   * @return bool|float|int|null|string
   */
  protected function getFilteredOptionValue($value, $default) {
    if (is_bool($default)) {
      if ((bool) $value !== $default) {
        return (bool) $value;
      }
    }
    else if (is_int($default)) {
      if ((int) $value !== $default) {
        return (int) $value;
      }
    }
    else if (is_float($default)) {
      if ((float) $value !== $default) {
        return (float) $value;
      }
    }
    else if ($value !== $default) {
      return /*string*/ $value;
    }
    return NULL;
  }

  /**
   * Render the given style.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['setup'] = array(
      '#type' => 'item',
      '#markup' => '<h3>' . t('Setup') . '</h3>',
    );
    $form['flickity_cellSelector'] = array(
      '#type' => 'textfield',
      '#title' => t('Cell selector'),
      '#default_value' => $this->options['flickity_cellSelector'],
      '#description' => t('Specify selector for cell elements. Only useful if you have other elements in your carousel elements that are not cells.'),
    );
    $form['flickity_initialIndex'] = array(
      '#type' => 'textfield',
      '#title' => t('Initial index'),
      '#default_value' => $this->options['flickity_initialIndex'],
      '#size' => 5,
      '#description' => t('Zero-based index of the initial selected cell.'),
    );
    $form['flickity_accessibility'] = array(
      '#type' => 'checkbox',
      '#title' => t('Accessibility'),
      '#default_value' => $this->options['flickity_accessibility'],
      '#description' => t('Enable keyboard navigation. Users can tab to a Flickity gallery, and pressing left & right keys to change cells.'),
    );
    $form['flickity_setGallerySize'] = array(
      '#type' => 'checkbox',
      '#title' => t('Set gallery size'),
      '#default_value' => $this->options['flickity_setGallerySize'],
      '#description' => t('Sets the height of the gallery to the height of the tallest cell.'),
    );
    $form['flickity_resize'] = array(
      '#type' => 'checkbox',
      '#title' => t('Resize'),
      '#default_value' => $this->options['flickity_resize'],
      '#description' => t('Adjusts sizes and positions when window is resized.'),
    );

    $form['cell_position'] = array(
      '#type' => 'item',
      '#markup' => '<h3>' . t('Cell position') . '</h3>',
    );
    $form['flickity_cellAlign'] = array(
      '#type' => 'select',
      '#title' => t('Cell align'),
      '#options'=> array(
        'left' => 'left',
        'center' => 'center',
        'right' => 'right',
      ),
      '#default_value' => $this->options['flickity_cellAlign'],
      '#description' => t('Align cells within the gallery element.'),
    );
    $form['flickity_contain'] = array(
      '#type' => 'checkbox',
      '#title' => t('Contain'),
      '#default_value' => $this->options['flickity_contain'],
      '#description' => t('Contains cells to gallery element to prevent excess scroll at beginning or end.'),
    );
    $form['flickity_imagesLoaded'] = array(
      '#type' => 'checkbox',
      '#title' => t('imagesLoaded'),
      '#default_value' => $this->options['flickity_imagesLoaded'],
      '#description' => t('Provides support for imagesLoaded plugin.'),
    );
    $form['flickity_percentPosition'] = array(
      '#type' => 'checkbox',
      '#title' => t('Percent position'),
      '#default_value' => $this->options['flickity_percentPosition'],
      '#description' => t('Sets positioning in percent values, rather than pixel values.'),
    );
    $form['flickity_rightToLeft'] = array(
      '#type' => 'checkbox',
      '#title' => t('Right to left'),
      '#default_value' => $this->options['flickity_rightToLeft'],
      '#description' => t('Enables right-to-left layout.'),
    );

    $form['behavior'] = array(
      '#type' => 'item',
      '#markup' => '<h3>' . t('Behavior') . '</h3>',
    );
    $form['flickity_draggable'] = array(
      '#type' => 'checkbox',
      '#title' => t('Draggable'),
      '#default_value' => $this->options['flickity_draggable'],
      '#description' => t('Enables dragging and flicking.'),
    );
    $form['flickity_freeScroll'] = array(
      '#type' => 'checkbox',
      '#title' => t('Free scroll'),
      '#default_value' => $this->options['flickity_freeScroll'],
      '#description' => t('Enables content to be freely scrolled and flicked without aligning cells to an end position.'),
    );
    $form['flickity_wrapAround'] = array(
      '#type' => 'checkbox',
      '#title' => t('Wrap around'),
      '#default_value' => $this->options['flickity_wrapAround'],
      '#description' => t('At the end of cells, wrap-around to the other end for infinite scrolling.'),
    );
    $form['flickity_lazyLoad'] = array(
      '#type' => 'select',
      '#title' => t('Lazy load'),
      '#options' => array(
        0 => t('Off'),
        1 => t('1 adjancent = 3 total'),
        2 => t('2 adjancent = 5 total'),
        3 => t('3 adjancent = 7 total'),
      ),
      '#default_value' => $this->options['flickity_lazyLoad'],
      '#description' => t('Loads cell images when a cell is selected. Set the image\'s URL to load with <code>data-flickity-lazyload</code>.'),
    );
    $form['flickity_autoPlay'] = array(
      '#type' => 'textfield',
      '#title' => t('Auto play'),
      '#default_value' => $this->options['flickity_autoPlay'],
      '#size' => 10,
      '#description' => t('Automatically advances to the next cell.'),
    );
    $form['flickity_pauseAutoPlayOnHover'] = array(
      '#type' => 'checkbox',
      '#title' => t('Pause auto play on hover'),
      '#default_value' => $this->options['flickity_pauseAutoPlayOnHover'],
      '#description' => t('Auto-playing will pause when the user hovers over the carousel.'),
    );
    $form['flickity_watchCSS'] = array(
      '#type' => 'checkbox',
      '#title' => t('Watch CSS'),
      '#default_value' => $this->options['flickity_watchCSS'],
      '#description' => t('You can enable and disable Flickity with CSS.'),
    );
    $form['flickity_asNavFor'] = array(
      '#type' => 'textfield',
      '#title' => t('As nav for'),
      '#default_value' => $this->options['flickity_asNavFor'],
      '#description' => t('Use one Flickity gallery as navigation for another.'),
    );
    $form['flickity_selectedAttraction'] = array(
      '#type' => 'textfield',
      '#title' => t('Selected attraction'),
      '#default_value' => $this->options['flickity_selectedAttraction'],
      '#size' => 10,
      '#description' => t('Attracts the position of the slider to the selected cell. Higher attraction makes the slider move faster.'),
    );
    $form['flickity_friction'] = array(
      '#type' => 'textfield',
      '#title' => t('Friction'),
      '#default_value' => $this->options['flickity_friction'],
      '#size' => 10,
      '#description' => t('Slows the movement of slider. Higher friction makes the slider feel stickier and less bouncy.'),
    );
    $form['flickity_freeScrollFriction'] = array(
      '#type' => 'textfield',
      '#title' => t('Free scroll friction'),
      '#default_value' => $this->options['flickity_freeScrollFriction'],
      '#size' => 10,
      '#description' => t('Slows movement of slider when freeScroll: true. Higher friction makes the slider feel stickier.'),
    );

    $form['ui'] = array(
      '#type' => 'item',
      '#markup' => '<h3>' . t('UI') . '</h3>',
    );
    $form['flickity_prevNextButtons'] = array(
      '#type' => 'checkbox',
      '#title' => t('Previous next buttons'),
      '#default_value' => $this->options['flickity_prevNextButtons'],
      '#description' => t('Creates and enables previous & next buttons.'),
    );
    $form['flickity_pageDots'] = array(
      '#type' => 'checkbox',
      '#title' => t('Page dots'),
      '#default_value' => $this->options['flickity_pageDots'],
      '#description' => t('Creates and enables page dots (pagination).'),
    );
    $form['flickity_arrowShape'] = array(
      '#type' => 'textfield',
      '#title' => t('Arrow shape'),
      '#default_value' => $this->options['flickity_arrowShape'],
      '#description' => t('Set a custom shape by setting arrowShape to a SVG path string.'),
    );
  }
}
