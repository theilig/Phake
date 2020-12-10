<?php

/*
 * Phake - Mocking Framework
 *
 * Copyright (c) 2010, Mike Lively <mike.lively@sellingsource.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  *  Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *  *  Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *  *  Neither the name of Mike Lively nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    Phake
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2010 Mike Lively <m@digitalsandwich.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.digitalsandwich.com/
 */

use PHPUnit\Framework\TestCase;
use PhakeTest\AnotherNamespacedClass;

/**
 * @ann1 Test Annotation
 * @ann2
 */
class Phake_Annotation_MockInitializerTest extends TestCase
{
    /**
     * @var Phake_Annotation_MockInitializer
     */
    private $initializer;

    /**
     * @Mock stdClass
     */
    private $mock1;

    /**
     * @Mock
     * @var stdClass
     */
    private $mock2;

    /**
     * @Mock
     * @var \PhakeTest\NamespacedClass
     */
    private $shortNameMock1;

    /**
     * @Mock AnotherNamespacedClass
     */
    private $shortNameMock2;

    protected function setUp(): void
    {
        if (version_compare(PHP_VERSION, '5.3', '<')) {
            $this->markTestSkipped('ReflectionProperty::setAccessible() is not available');
        }

        $this->initializer = new Phake_Annotation_MockInitializer();
    }

    protected function tearDown(): void
    {
        $this->initializer    = null;
        $this->mock1          = $this->mock2 = null;
        $this->shortNameMock1 = $this->shortNameMock2 = null;
    }

    public function testInitialize()
    {
        if (version_compare(PHP_VERSION, '5.3', '<')) {
            $this->markTestSkipped('ReflectionProperty::setAccessible() is not available');
        }

        $this->initializer->initialize($this);

        $this->assertInstanceOf('stdClass', $this->mock1);
        $this->assertInstanceOf('stdClass', $this->mock2);
        $this->assertInstanceOf('Phake_IMock', $this->mock1);
        $this->assertInstanceOf('Phake_IMock', $this->mock2);
    }

    /**
     * @depends testInitialize
     */
    public function testNamespaceAliasOnVar()
    {
        $this->initializer->initialize($this);

        $this->assertInstanceOf('Phake_IMock', $this->shortNameMock1);
    }

    /**
     * @depends testInitialize
     */
    public function testNamespaceAliasOnMock()
    {
        $this->initializer->initialize($this);

        $this->assertInstanceOf('Phake_IMock', $this->shortNameMock2);
    }
}

