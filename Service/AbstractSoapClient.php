<?php

namespace EXS\CampaignerBundle\Service;

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
            'trace' => true, /* So we can have response's headers. */
            'exceptions' => false, /* Bad calls won't throw an exception but returns a SoapFault object. */
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
            (string)$methodName,
            [
                (string)$methodName => array_merge(
                    $this->authenticationNode,
                    $parameters
                ),
            ],
            null,
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

        var_dump($responseHeaders);

        /* Transform a \stdClass object to an associative array. */
        $responseHeaders = json_decode(json_encode($responseHeaders), true);

        if (
            (true === isset($responseHeaders['ResponseHeader']))
            && (true === isset($responseHeaders['ResponseHeader']['ErrorFlag']))
        ) {
            return !(bool)$responseHeaders['ResponseHeader']['ErrorFlag'];
        }

        return null;
    }
}
