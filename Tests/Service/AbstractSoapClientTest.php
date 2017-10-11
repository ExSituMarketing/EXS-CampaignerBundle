<?php

namespace EXS\CampaignerBundle\Tests\Service;

use EXS\CampaignerBundle\Service\AbstractSoapClient;
use EXS\CampaignerBundle\Tests\CreateMockTrait;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class EmptyClient extends AbstractSoapClient { }

class AbstractSoapClientTest extends \PHPUnit_Framework_TestCase
{
    use CreateMockTrait;

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

    public function testCreateReportResultFromXml()
    {
        $xmlResult = <<<XML
<ReportResult xmlns="https://ws.campaigner.com/2013/01" ContactId="9991682668" ContactUniqueIdentifier="bguy@test.tld">
    <Attribute Id="999031" Type="Text">1111111111111111111111</Attribute>
</ReportResult>
XML;

        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $result = $client->createReportResultFromXml($xmlResult);

        $this->assertEquals('bguy@test.tld', $result->ContactUniqueIdentifier);
        $this->assertCount(1, $result->attributes);
    }

    public function testCallMethod()
    {
        $responseHeaders = [];

        $soapResult = new \stdClass();
        $soapResult->test = 123;

        $clientMock = $this->doCreateMock(\SoapClient::class, [
            ['method' => '__soapCall', 'parameters' => [
                'SomeMethod',
                [
                    'SomeMethod' => [
                        'authentication' => [
                            'Username' => 'user',
                            'Password' => 'pass',
                        ],
                        'foo' => 123,
                        'bar' => 'baz',
                    ]
                ],
                [],
                null,
                $responseHeaders
            ], 'result' => $soapResult],
            ['method' => '__soapCall', 'parameters' => [
                'SomeMethod',
                [
                    'SomeMethod' => [
                        'authentication' => [
                            'Username' => 'user',
                            'Password' => 'pass',
                        ],
                        'foo' => 123,
                        'bar' => 'baz',
                    ]
                ],
                [],
                null,
                $responseHeaders
            ], 'result' => new \SoapFault('Receiver', 'It is all broken!')],
            ['method' => '__soapCall', 'parameters' => [
                'SomeMethod',
                [
                    'SomeMethod' => [
                        'authentication' => [
                            'Username' => 'user',
                            'Password' => 'pass',
                        ],
                        'foo' => 123,
                        'bar' => 'baz',
                    ]
                ],
                [],
                null,
                $responseHeaders
            ], 'result' => function () use (&$responseHeaders) {
                $responseHeaders['ResponseHeader']['ErrorFlag'] = false;

                return [];
            }],
        ]);

        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $reflection = new \ReflectionClass($client);

        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($client, $clientMock);

        $method = $reflection->getMethod('callMethod');
        $method->setAccessible(true);

        $result = $method->invokeArgs($client, ['SomeMethod', ['foo' => 123, 'bar' => 'baz']]);
        $this->assertEquals(['test' => 123], $result);

        $result = $method->invokeArgs($client, ['SomeMethod', ['foo' => 123, 'bar' => 'baz']]);
        $this->assertNull($result);

        $result = $method->invokeArgs($client, ['SomeMethod', ['foo' => 123, 'bar' => 'baz']]);
        $this->assertNull($result);
    }

    public function testSetXsdPath()
    {
        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $client->setXsdPath('/some/hypothetical/path/to-file.ext');

        $reflector = new \ReflectionObject($client);
        $property = $reflector->getProperty('xsdPath');
        $property->setAccessible(true);
        $path = $property->getValue($client);

        $this->assertEquals('/some/hypothetical/path/to-file.ext', $path);
    }

    public function testIsValidXmlContactQuery()
    {
        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $reflector = new \ReflectionObject($client);

        $property = $reflector->getProperty('xsdPath');
        $property->setAccessible(true);
        $property->setValue($client, __DIR__ . '/../../Resources/xsd/ContactsSearchCriteria2.xsd');

        $method = $reflector->getMethod('isValidXmlContactQuery');
        $method->setAccessible(true);

        $query = <<<XML
<contactssearchcriteria>
    <version major="2" minor="0" build="0" revision="0" />
    <set>Partial</set>
    <evaluatedefault>True</evaluatedefault>
    <group>
        <filter>
        <filtertype>SearchAttributeValue</filtertype>
            <staticattributeid>1</staticattributeid>
            <action>
                <type>Text</type>
                <operator>Containing</operator>
                <value>alex</value>
            </action>
        </filter>
        <filter>
            <relation>And</relation>
            <filtertype>SearchAttributeValue</filtertype>
            <staticattributeid>3</staticattributeid>
            <action>
                <type>Text</type>
                <operator>Containing</operator>
                <value>@campaigner.com</value>
            </action>
        </filter>
    </group>
</contactssearchcriteria>
XML;

        $result = $method->invoke($client, $query);
        $this->assertTrue($result);

        $result = $method->invoke($client, 'This is not XML.');
        $this->assertFalse($result);
    }

    public function testValidateFormat()
    {
        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $reflector = new \ReflectionObject($client);

        $method = $reflector->getMethod('validateFormat');
        $method->setAccessible(true);

        $result = $method->invoke($client, 'HTML');
        $this->assertEquals('HTML', $result);

        $this->setExpectedException(InvalidConfigurationException::class, 'Invalid Format "Foo".');
        $method->invoke($client, 'Foo');
    }

    public function testGetUtcDatetime()
    {
        $client = new EmptyClient('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');

        $reflector = new \ReflectionObject($client);

        $method = $reflector->getMethod('getUtcDatetime');
        $method->setAccessible(true);

        $newYear = new \DateTime('2018-01-01 00:00', new \DateTimeZone('UTC'));
        $this->assertEquals('2018-01-01T00:00:00.000Z', $method->invoke($client, $newYear));

        $newYear = new \DateTime('2017-12-31 19:00', new \DateTimeZone('America/Montreal'));
        $this->assertEquals('2018-01-01T00:00:00.000Z', $method->invoke($client, $newYear));
    }
}
