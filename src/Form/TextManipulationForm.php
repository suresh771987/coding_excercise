<?php

namespace Drupal\coding_excercise\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class TextManipulationForm.
 */
class TextManipulationForm extends FormBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new ExcelDownLoadController object.
   */
  public function __construct(RendererInterface $renderer, ConfigFactoryInterface $config_factory) {
    $this->renderer = $renderer;
    $this->configFactory = $config_factory->get('coding_excercise.textmanipulationconfig');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'text_manipulation_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $options = [];
    $choosedOptions = $this->configFactory->get('type_of_text_manipulation');
    foreach ($choosedOptions as $key => $option) {
      if ($option) {
        $options[$key] = ucwords($option);
      }
    }

    $form['input_the_text_to_manipulate'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Input the text to manipulate'),
      '#description' => $this->t('Enter the text to manipulate.'),
      '#weight' => '0',
    ];
    $form['text_manipulation'] = [
      '#type' => 'select',
      '#title' => $this->t('Text Manipulation'),
      '#description' => $this->t('Select a type to manipulate the text.'),
      '#options' => $options,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::processTextManipulation',
        'wrapper' => 'text-manipulation-form',
      ],
    ];

    $form['#attached']['library'][] = 'coding_excercise/coding_excercise.textmanipulation';

    return $form;
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

  }

  /**
   * Implements the ajax submit handler for text manipulation.
   */
  public function processTextManipulation(array &$form, FormStateInterface $form_state) {
    $input = $form_state->getUserInput();

    $inputText = $input['input_the_text_to_manipulate'];
    $inputWords = explode(" ", $inputText);

    $response = new AjaxResponse();

    switch ($input['text_manipulation']) {
      case 'random':
        $output = $this->getRandomText($inputWords, $input);
        $response->addCommand(new HtmlCommand('#text-manipulation-form', $output));
        break;

      case 'reverse':
        $output = $this->getReversedText($inputWords, $input);
        $response->addCommand(new HtmlCommand('#text-manipulation-form', $output));
        break;

      case 'Captialise second word':
        $output = $this->getSecondWordCaptialised($inputWords, $input);
        $response->addCommand(new HtmlCommand('#text-manipulation-form', $output));
        break;
    }

    return $response;
  }

  /**
   * Function to return the random text.
   */
  public function getRandomText($input, $formInput) {
    shuffle($input);
    $manipulatedText = [
      '#theme' => 'coding_excercise',
      '#inputText' => $formInput['input_the_text_to_manipulate'],
      '#outputText' => implode(" ", $input),
    ];

    return $this->renderer->render($manipulatedText);

  }

  /**
   * Function to return the reversed text.
   */
  public function getReversedText(array $input, array $formInput) {
    $reversedArray = array_reverse($input);

    $manipulatedText = [
      '#theme' => 'coding_excercise',
      '#inputText' => $formInput['input_the_text_to_manipulate'],
      '#outputText' => implode(" ", $reversedArray),
    ];
    return $this->renderer->render($manipulatedText);
  }

  /**
   * Function to captialise every second word.
   */
  public function getSecondWordCaptialised(array $input, array $formInput) {
    foreach ($input as $key => $value) {
      // Even.
      if (0 !== $key % 2) {
        $input[$key] = ucwords($value);
      }
    }

    $manipulatedText = [
      '#theme' => 'coding_excercise',
      '#inputText' => $formInput['input_the_text_to_manipulate'],
      '#outputText' => implode(" ", $input),
    ];
    return $this->renderer->render($manipulatedText);
  }

}
