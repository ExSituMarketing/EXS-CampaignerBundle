<?php

namespace EXS\CampaignerBundle\Service;

use EXS\CampaignerBundle\Model\ReportResult;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class AbstractSoapClient
 *
 * @package EXS\CampaignerBundle\Service
 */
abstract class AbstractSoapClient
{
    /**
     * @var \SoapClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $authenticationNode;

    /**
     * @var string
     */
    protected $xsdPath;

    /**
     * AbstractSoapClient constructor.
     *
     * @param string $wsdlUrl
     * @param string $username
     * @param string $password
     * @param array  $clientOptions
     */
    public function __construct($wsdlUrl, $username, $password, array $clientOptions = [])
    {
        $requiredOptions = [
            'encoding' => 'UTF-8',
            'exceptions' => false, /* Bad calls won't throw an exception but returns a SoapFault object. */
            'soap_version' => SOAP_1_1,
            'trace' => true, /* So we can have response's headers. */
            'classmap' => [
                'ReportResult' => ReportResult::class,
            ],
            'typemap' => [
                [
                    'type_ns' => 'https://ws.campaigner.com/2013/01',
                    'type_name' => 'ReportResult',
                    'from_xml' => [$this, 'createReportResultFromXml'],
                ],
            ],
        ];

        $this->client = new \SoapClient($wsdlUrl, array_merge($clientOptions, $requiredOptions));

        $this->authenticationNode = [
            'authentication' => [
                'Username' => $username,
                'Password' => $password,
            ],
        ];
    }

    /**
     * @param string $xml
     *
     * @return ReportResult
     */
    public function createReportResultFromXml($xml)
    {
        $xmlElement = new \SimpleXMLElement($xml);

        $reportResultAttributes = (array)$xmlElement->attributes();
        $reportResultAttributes = current($reportResultAttributes);

        foreach ($xmlElement->getDocNamespaces() as $namespace) {
            $xmlElement->registerXPathNamespace('c', $namespace);
        }

        $attributeValues = [];
        $attributeTags = $xmlElement->xpath('//c:Attribute');

        foreach ($attributeTags as $attributeTag) {
            $attributeAttributes = (array)$attributeTag->attributes();
            $attributeAttributes = current($attributeAttributes);

            $attributeValues[$attributeAttributes['Id']] = (string)$attributeTag;
        }

        return new ReportResult($reportResultAttributes, $attributeValues);
    }

    /**
     * Proxy method to SoapClient::__soapCall() that format the request body has expected by the web service.
     * Will return :
     *  - an associative array of the result
     *  - true if result is empty but there were no error
     *  - false if result is empty but there an error
     *  - null if an error occurred or the result as an unknown form
     *
     * @param string $methodName
     * @param array  $parameters
     *
     * @return array|bool|null
     */
    protected function callMethod($methodName, array $parameters = [])
    {
        $responseHeaders = [];

        $result = $this->client->__soapCall(
            $methodName,
            [
                $methodName => array_merge(
                    $this->authenticationNode,
                    $parameters
                ),
            ],
            [],
            null,
            $responseHeaders
        );

        if ($result instanceof \SoapFault) {
            return null;
        }

        /* Transform a \stdClass object to an associative array. */
        $result = json_decode(json_encode($result), true);

        if (false === empty($result)) {
            return $result;
        }

        /* Transform a \stdClass object to an associative array. */
        $responseHeaders = json_decode(json_encode($responseHeaders), true);

        if (
            (true === isset($responseHeaders['ResponseHeader']))
            && (true === isset($responseHeaders['ResponseHeader']['ErrorFlag']))
        ) {
            // @codeCoverageIgnoreStart
            return !(bool)$responseHeaders['ResponseHeader']['ErrorFlag'];
            // @codeCoverageIgnoreEnd
        }

        return null;
    }

    /**
     * Set xsd file's path.
     *
     * @param $xsdPath
     */
    public function setXsdPath($xsdPath)
    {
        $this->xsdPath = $xsdPath;
    }

    /**
     * Validate query against the xsd.
     *
     * @param string $query
     *
     * @return bool
     */
    protected function isValidXmlContactQuery($query)
    {
        $queryXml = new \DOMDocument();

        try {
            $queryXml->loadXML($query);

            return $queryXml->schemaValidate($this->xsdPath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validates mail format value.
     *
     * @param string $format
     *
     * @return string
     */
    protected function validateFormat($format)
    {
        if (false === in_array($format, ['Text', 'HTML', 'Both'])) {
            throw new InvalidConfigurationException(sprintf('Invalid Format "%s".', $format));
        }

        return $format;
    }

    /**
     * Validates report type value.
     *
     * @param string $reportType
     *
     * @return string
     */
    protected function validateReportType($reportType)
    {
        $reportTypes = [
            'rpt_Detailed_Contact_Results_by_Campaign',
            'rpt_Summary_Contact_Results_by_Campaign',
            'rpt_Summary_Campaign_Results',
            'rpt_Summary_Campaign_Results_by_Domain',
            'rpt_Contact_Attributes',
            'rpt_Contact_Details',
            'rpt_Contact_Group_Membership',
            'rpt_Groups',
            'rpt_Tracked_Links',
        ];

        if (false === in_array($reportType, $reportTypes)) {
            throw new InvalidConfigurationException(sprintf('Invalid reportType value "%s".', $reportType));
        }

        return $reportType;
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
    protected function getUtcDatetime(\DateTime $date)
    {
        if ('UTC' !== $date->getTimezone()->getName()) {
            $date->setTimezone(new \DateTimeZone('UTC'));
        }

        return $date->format('Y-m-d\TH:i:s.v\Z');
    }
}
