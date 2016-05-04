<?php

/**
 * @file
 * Contains \Drupal\uuid_condition\Plugin\Condition\UuidCondition
 *
 * This code is released under the GNU General Public License.
 * This work is Copyright 2016 by Department of Justice and Regulation, Victoria, Australia.
 */

namespace Drupal\uuid_condition\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Content has UUID' condition.
 *
 * @Condition(
 *   id = "uuid",
 *   label = @Translation("Content has UUID"),
 *   context = {
 *     "node" = @ContextDefinition("entity:node", label = @Translation("Node"))
 *   },
 * )
 */
class UuidCondition extends ConditionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array('uuid' => '') + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['uuid'] = array(
      '#title' => $this->t('Entity UUIDs, one per line'),
      '#type' => 'textarea',
      '#default_value' => $this->configuration['uuid'],
    );
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['uuid'] = $form_state->getValue('uuid');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $uuid = $this->configuration['uuid'];
    return $this->t('The entity id configuration is @uuid', array('@uuid' => $uuid));
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if (empty($this->configuration['uuid'])) {
      // Pass through if no configuration found.
      return TRUE;
    }

    // @todo load any type of entity, not just a node.
    $entity = $this->getContextValue('node');
    if ($entity && $uuid = $entity->uuid()) {
      // Return TRUE if the entity uuid matches any uuid in the block configuration.
      if ($uuid_config = $this->configuration['uuid']) {
        if (strpos($uuid_config, $uuid) !== FALSE) {
          return TRUE;
        }
      }

      // Context UUID doesn't match configuration.
      return FALSE;
    }
    else {
      // Context doesn't appear to have a uuid, so let it pass through.
      return TRUE;
    }
  }

}
