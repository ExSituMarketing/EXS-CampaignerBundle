<?php

namespace EXS\CampaignerBundle\Service;

use Symfony\Component\Config\FileLocator;

/**
 * Class XsdPathConfigurator
 *
 * @package EXS\CampaignerBundle\Service
 */
class XsdPathConfigurator
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var string
     */
    private $xsdReference;

    /**
     * XsdPathConfigurator constructor.
     *
     * @param FileLocator $fileLocator
     * @param             $xsdReference
     */
    public function __construct(FileLocator $fileLocator, $xsdReference)
    {
        $this->fileLocator = $fileLocator;
        $this->xsdReference = $xsdReference;
    }

    /**
     * @param AbstractSoapClient $client
     */
    public function configure(AbstractSoapClient $client)
    {
        $xsdPath = $this->fileLocator->locate($this->xsdReference);

        $client->setXsdPath($xsdPath);
    }
}
