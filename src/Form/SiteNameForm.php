<?php
/**
 * @file
 * Contains \Drupal\site_name_example\Form\SiteNameForm.
 */
namespace Drupal\site_name_example\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
class SiteNameForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['system.site'];
  }

  /**
   * {@inheritdoc}
   * This sets the form machine name.
   * The form name that users see is set in the routing.yml file.
   */
  public function getFormId() {
    return 'site_name_form';
  }

  /**
   * {@inheritdoc}
   * Use this function to add your field values and your form submit button.
   * You can customize your field names and form submit button text here, too.
   * At the end, return everything in the form or else you'll end up with a blank page.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Site Name:'),
      '#required' => TRUE,
      '#default_value' => $site_config->get('name'),
    );

    $site_slogan = \Drupal::config('system.site')->get('slogan');
    $form['slogan'] = array(
      '#type' => 'textfield',
      '#title' => t('Site Slogan:'),
      '#required' => FALSE,
      '#default_value' => $site_config->get('slogan'),
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   * You can add any field value validation in this function.
   * For this example, we want to restrict site name and slogan length.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('name')) > 36) {
      $form_state->setErrorByName('name', $this->t('Site name is too long.'));
    }

    if (strlen($form_state->getValue('slogan')) > 72) {
      $form_state->setErrorByName('slogan', $this->t('Site slogan is too long.'));
    }
  }

  /**
   * {@inheritdoc}
   * You want to save your form's values with this function.
   * For this example, we're updating config with our form's values.
   * After the value is saved, we send the user back to the front page and give them a message to know the form saved successfully.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('system.site')
      ->set('name', $form_state->getValue('name'))
      ->set('slogan', $form_state->getValue('slogan'))
      ->save();
    $form_state->setRedirect('<front>');
    drupal_set_message('Updated site name information');
    parent::submitForm($form, $form_state);
  }
}