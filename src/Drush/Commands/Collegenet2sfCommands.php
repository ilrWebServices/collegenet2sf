<?php

namespace Drupal\collegenet2sf\Drush\Commands;

use Consolidation\AnnotatedCommand\Input\StdinAwareInterface;
use Consolidation\AnnotatedCommand\Input\StdinAwareTrait;
use Drush\Commands\DrushCommands;
use Drush\Attributes as CLI;
use Drupal\collegenet2sf\CollegeNetToSalesforceProcessor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Drush commandfile for collegenet2sf.
 */
class Collegenet2sfCommands extends DrushCommands implements StdinAwareInterface {

  use StdinAwareTrait;

  /**
   * Constructs an Collegenet2sfCommands object.
   */
  public function __construct(
    private readonly CollegeNetToSalesforceProcessor $collegenet2sfProcessor
  ) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('collegenet2sf.processor'),
    );
  }

  /**
   * Run the CollegeNET to Salesforce Lead processor.
   */
  #[CLI\Command(name: 'collegenet2sf:run', aliases: ['cnet2sf'])]
  #[CLI\Usage(name: 'collegenet2sf:run', description: 'Run the Collegenet to Salesforce Lead processor')]
  public function run(array $extra) {
    $command = array_shift($extra);
    $data = ($command === '-') ? $this->stdin()->contents() : NULL;
    $result = $this->collegenet2sfProcessor->run($data);
    $this->logger()->success(dt('CollegeNET to Salesforce Lead processor was run @result. Check logs for results.', [
      '@result' => $result ? 'successfully' : 'with issues',
    ]));
  }

}
