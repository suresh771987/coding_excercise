<?php

namespace Drupal\coding_excercise\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Url;

/**
 * Tests:
 *   - \Drupal\coding_excercise\Form\TextManipulationForm.
 *
 * @group coding_excercise
 */
class TextManipulationFormTest extends WebTestBase {

  /**
   * @var \Drupal\user\Entity\User
   */
  protected $admin_user;

  /**
   * The route that renders the form.
   *
   * @var string
   */
  protected $route = 'coding_excercise.text_manipulation_form';


  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['coding_excercise'];

  /**
   * Setup the test.
   */
  public function setUp() {
    parent::setUp();
    $this->admin_user = $this->drupalCreateUser(['access text manipulation form']);
  }

  /**
   * Tests permissions, the form controller and general form returning.
   */
  public function testAccess() {
    $this->drupalLogin($this->admin_user);
    $this->drupalGet(Url::fromRoute($this->route));
    $this->assertResponse(200);

  }

  /**
   * Tests that the close button works and that content exists.
   *
   * @see \Drupal\coding_excercise\Form\TextManipulationForm::buildForm
   */
  public function testDetailForm() {
    $this->drupalLogin($this->admin_user);
    $this->drupalGet(Url::fromRoute($this->route));
    $edit = [
      'input_the_text_to_manipulate' => 'Test random word by webtest case',
      'text_manipulation' => 'random',
    ];
    $this->drupalPostAjaxForm(Url::fromRoute($this->route)->toString(), $edit, ['op' => t('Submit')]);

  }

}
