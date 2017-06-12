<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTestCase extends WebTestCase
{
    /**
     * @var Application
     */
    protected $_application;

    public function getContainer()
    {
        return $this->_application->getKernel()->getContainer();
    }

    public function setUp()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new Application($kernel);
        $this->_application->setAutoExit(false);

        $this->runConsole('doctrine:schema:drop', array("--force" => true));
        $this->runConsole('doctrine:schema:create');
        $this->runConsole('doctrine:schema:update', array("--force" => true));
        $this->runConsole('doctrine:fixtures:load', array("--no-interaction" => true));

        parent::setUp();
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new ArrayInput($options));
    }
}