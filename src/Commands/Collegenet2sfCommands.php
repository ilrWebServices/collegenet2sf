<?php

namespace Drupal\collegenet2sf\Commands;

use Consolidation\AnnotatedCommand\Input\StdinAwareInterface;
use Consolidation\AnnotatedCommand\Input\StdinAwareTrait;
use Drush\Commands\DrushCommands;
use Drupal\collegenet2sf\CollegeNetToSalesforceProcessor;

/**
 * Drush commandfile for collegenet2sf.
 */
class Collegenet2sfCommands extends DrushCommands implements StdinAwareInterface {

  use StdinAwareTrait;


  /**
   * {@inheritdoc}
   */
  public function __construct(CollegeNetToSalesforceProcessor $collegenet2sf_processor) {
    parent::__construct();
    $this->collegenet2sfProcessor = $collegenet2sf_processor;
  }

  /**
   * Run the CollegeNET to Salesforce Lead processor.
   *
   * @command collegenet2sf:run
   * @aliases cnet2sf
   */
  public function run(array $extra) {
    $command = array_shift($extra);
    $data = ($command === '-') ? $this->stdin()->contents() : NULL;
    $result = $this->collegenet2sfProcessor->run($data);
    $this->logger()->success(dt('CollegeNET to Salesforce Lead processor was run @result. Check logs for results.', [
      '@result' => $result ? 'successfully' : 'with issues',
    ]));
  }

}
