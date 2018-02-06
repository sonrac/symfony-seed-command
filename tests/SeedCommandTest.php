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
use sonrac\SimpleSeed\SeedClassNotFound;
use sonrac\SimpleSeed\SeedCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

/**
 * Class SeedCommandTest
 */
class SeedCommandTest extends TestCase
{
    /**
     * @var \sonrac\SimpleSeed\SeedCommand
     */
    private $seedCommand = null;

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

    /**
     * Test empty connection exception.
     *
     * @throws \Exception
     * @author Sergii Donii <s.donii@infomir.com>
     */
    public function testEmptyConnectionException() {
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
        $input = $this->getMockBuilder(Input::class);
        $input = $input->setMethods(['getOption', 'parse', 'hasParameterOption', 'getFirstArgument', 'getParameterOption'])->getMock();
        $input->expects($this->any())
            ->method('getOption')
            ->willReturn('testClassNotExists');

        $output = $this->getMockBuilder(Output::class)->getMock();

        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(SeedClassNotFound::class);
        } else {
            $this->expectException(SeedClassNotFound::class);
        }

        $this->seedCommand->run($input, $output);
    }

    /**
     * Test error class exists.
     *
     * @throws \Exception
     */
    public function testErrorClassDoesNotImplementInterface()
    {
        $input = $this->getMockBuilder(Input::class);
        $input = $input->setMethods(['getOption', 'parse', 'hasParameterOption', 'getFirstArgument', 'getParameterOption'])->getMock();
        $input->expects($this->any())
            ->method('getOption')
            ->willReturn('sonrac\\SimpleSeed\\SeedCommand');

        $output = $this->getMockBuilder(Output::class)->getMock();

        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(InvalidSeedClassException::class);
        } else {
            $this->expectException(InvalidSeedClassException::class);
        }

        $this->seedCommand->run($input, $output);
    }

    /**
     * Test seed run success.
     *
     * @throws \Exception
     */
    public function testSeedSuccess()
    {
        $input = $this->getMockBuilder(Input::class);
        $input = $input->setMethods(['getOption', 'parse', 'hasParameterOption', 'getFirstArgument', 'getParameterOption'])->getMock();
        $input->expects($this->any())
            ->method('getOption')
            ->willReturn('Tests\\Data\\TestSeed');

        $output = $this->getMockBuilder(Output::class)->getMock();

        $this->assertEquals(0, $this->seedCommand->run($input, $output));
    }
}
