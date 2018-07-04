<?php
/**
 * Created by PhpStorm.
 * User: sonrac
 * Date: 1/25/18
 * Time: 8:33 PM.
 */

namespace Tests\Units;

use PHPUnit\Framework\TestCase;
use sonrac\SimpleSeed\InvalidSeedClassException;
use sonrac\SimpleSeed\SeedClassNotFoundException;
use sonrac\SimpleSeed\SeedCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

/**
 * Class SeedCommandTest.
 */
class SeedCommandTest extends TestCase
{
    /**
     * @var \sonrac\SimpleSeed\SeedCommand
     */
    private $seedCommand;

    /**
     * Test empty connection exception.
     *
     * @throws \Exception
     *
     * @author Sergii Donii <doniysa@gmail.com>
     */
    public function testEmptyConnectionException()
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(\Exception::class);
        } else {
            $this->expectException(\Exception::class);
        }

        new SeedCommand(null, null);
    }

    /**
     * Test error class exists.
     *
     * @throws \Exception
     */
    public function testErrorClass()
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(SeedClassNotFoundException::class);
        } else {
            $this->expectException(SeedClassNotFoundException::class);
        }

        $this->seedCommand->run($this->getInputMock(false, 'SimpleSeedNotFound'), $this->getOutputMock());
    }

    /**
     * Test error class exists.
     *
     * @throws \Exception
     */
    public function testErrorRollBackClass()
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(InvalidSeedClassException::class);
        } else {
            $this->expectException(InvalidSeedClassException::class);
        }

        $this->seedCommand->run($this->getInputMock(true, 'Tests\\Data\\TestSeed'), $this->getOutputMock());
    }

    /**
     * Get mock input
     *
     * @param string $retClass
     * @param bool   $retRollback
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder|\PHPUnit_Framework_MockObject_MockObject|SeedCommand
     */
    private function getInputMock($retRollback = false, $retClass = 'Tests\\Data\\TestRollbackSeed')
    {
        $input = $this->getMockBuilder(Input::class);
        $input = $input->setMethods(
            ['getOption', 'parse', 'hasParameterOption', 'getFirstArgument', 'getParameterOption']
        )->getMock();
        $input->expects($this->at(1))
            ->method('getOption')
            ->willReturn($retClass);
        $input->expects($this->at(2))
            ->method('getOption')
            ->willReturn($retRollback);

        return $input;
    }

    /**
     * Test error class exists.
     *
     * @throws \Exception
     */
    public function testErrorClassDoesNotImplementInterface()
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(InvalidSeedClassException::class);
        } else {
            $this->expectException(InvalidSeedClassException::class);
        }

        $this->seedCommand->run($this->getInputMock(false, 'sonrac\SimpleSeed\SeedCommand'), $this->getOutputMock());
    }

    /**
     * Get mock for output.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Console\Output\Output
     */
    private function getOutputMock()
    {
        return $this->getMockBuilder(Output::class)->getMock();
    }

    /**
     * Test seed run success.
     *
     * @throws \Exception
     */
    public function testSeedSuccess()
    {
        $this->assertEquals(0, $this->seedCommand->run($this->getInputMock(), $this->getOutputMock()));
    }

    /**
     * Test seed run success.
     *
     * @throws \Exception
     */
    public function testRollbackSeedSuccess()
    {
        $this->assertEquals(0, $this->seedCommand->run($this->getInputMock(true), $this->getOutputMock()));
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->seedCommand = new SeedCommand(null, sonrac_getDoctrineConnection());
    }
}
