<?php

namespace EXS\CampaignerBundle\Tests\Service;

use EXS\CampaignerBundle\Service\ContactManager;
use EXS\CampaignerBundle\Tests\CreateMockTrait;

class ContactManagerTest extends \PHPUnit_Framework_TestCase
{
    use CreateMockTrait;

    /**
     * @var ContactManager
     */
    private $manager;

    public function setUp()
    {
        $this->manager = new ContactManager('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL', 'user', 'pass');
    }

    private function setMockCalls(array $calls)
    {
        $clientMock = $this->doCreateMock(\SoapClient::class, $calls);

        $reflection = new \ReflectionClass($this->manager);

        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->manager, $clientMock);
    }

    public function testCreateUpdateAttribute()
    {
        $soapResult = new \stdClass();
        $soapResult->AttributeId = 123456;

        $this->setMockCalls([
            ['method' => '__soapCall', 'parameters' => [
                'CreateUpdateAttribute',
                [
                    'CreateUpdateAttribute' => [
                        'authentication' => [
                            'Username' => 'user',
                            'Password' => 'pass',
                        ],
                        'attributeId' => null,
                        'attributeName' => 'Foo',
                        'attributeType' => 'String',
                        'clearDefault' => false,
                        'defaultValue' => 'Default',
                    ]
                ],
                [],
                null,
                []
            ], 'result' => $soapResult],
        ]);

        $result = $this->manager->CreateUpdateAttribute(
            null,
            'Foo',
            'String',
            'Default',
            false
        );

        $this->assertArrayHasKey('AttributeId', $result);
        $this->assertEquals(123456, $result['AttributeId']);
    }
}
