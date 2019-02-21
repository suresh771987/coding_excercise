<?php

namespace Drupal\coding_excercise\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TextManipulationConfigForm.
 */
class TextManipulationConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'coding_excercise.textmanipulationconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'text_manipulation_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('coding_excercise.textmanipulationconfig');
    $form['type_of_text_manipulation'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Type of Text Manipulation'),
      '#description' => $this->t('Select the type of manipulation required.'),
      '#options' => ['random' => $this->t('Random'), 'reverse' => $this->t('Reverse'), 'Captialise second word' => $this->t('captialise second word')],
      '#default_value' => $config->get('type_of_text_manipulation'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('coding_excercise.textmanipulationconfig')
      ->set('type_of_text_manipulation', $form_state->getValue('type_of_text_manipulation'))
      ->save();
  }

}
