<?php

namespace EXS\CampaignerBundle\Tests\Service;

use EXS\CampaignerBundle\Service\AbstractSoapClient;

class EmptyClient extends AbstractSoapClient { }

class AbstractSoapClientTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $reflection = new \ReflectionClass($client);

        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientValue = $clientProperty->getValue($client);

        $this->assertInstanceOf('SoapClient', $clientValue);

        $authenticationNodeProperty = $reflection->getProperty('authenticationNode');
        $authenticationNodeProperty->setAccessible(true);
        $authenticationNodeValue = $authenticationNodeProperty->getValue($client);

        $expected = [
            'authentication' => [
                'Username' => 'user',
                'Password' => 'pass',
            ],
        ];

        $this->assertEquals($expected, $authenticationNodeValue);
    }

    public function testCallMethod()
    {
//        $responseHeaders = [];
//
//        $clientMock = $this->prophesize(\SoapClient::class);
//        $clientMock->__soapCall(
//            'SomeMethod',
//            [
//                'SomeMethod' => [
//                    'authentication' => [
//                        'Username' => 'user',
//                        'Password' => 'pass',
//                    ],
//                    'foo' => 123,
//                    'bar' => 'baz',
//                ]
//            ],
//            null,
//            null,
//            $responseHeaders
//        )->willReturn(['result' => 'test'])->shouldBeCalledTimes(1);

        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $reflection = new \ReflectionClass($client);

//        $property = $reflection->getProperty('client');
//        $property->setAccessible(true);
//        $property->setValue($client, $clientMock);

        $method = $reflection->getMethod('callMethod');
        $method->setAccessible(true);

        $result = $method->invokeArgs($client, ['SomeMethod', ['foo' => 123, 'bar' => 'baz']]);

        $this->assertNull($result);
    }
}