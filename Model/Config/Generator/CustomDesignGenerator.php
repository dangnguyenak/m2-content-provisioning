<?php
declare(strict_types=1);

namespace Firegento\ContentProvisioning\Model\Config\Generator;

use Firegento\ContentProvisioning\Api\Data\BlockEntryInterface;
use Firegento\ContentProvisioning\Api\Data\EntryInterface;
use Firegento\ContentProvisioning\Api\Data\PageEntryInterface;
use Firegento\ContentProvisioning\Model\Config\Generator\Query\GetNodeByKey;
use SimpleXMLElement;

class CustomDesignGenerator implements GeneratorInterface
{
    /**
     * @var GetNodeByKey
     */
    private $getNodeByKey;

    /**
     * @param GetNodeByKey $getNodeByKey
     */
    public function __construct(
        GetNodeByKey $getNodeByKey
    ) {
        $this->getNodeByKey = $getNodeByKey;
    }

    /**
     * @param EntryInterface|PageEntryInterface|BlockEntryInterface $entry
     * @param SimpleXMLElement $xml
     */
    public function execute(EntryInterface $entry, SimpleXMLElement $xml): void
    {
        $entryNode = $this->getNodeByKey->execute($xml, $entry->getKey());
        if (!$entryNode) {
            return;
        }

        $customThemeFrom = $entry->getCustomThemeFrom();
        $customThemeTo   = $entry->getCustomThemeTo();
        $customTheme     = $entry->getCustomTheme();
        $customNewLayout = $entry->getCustomRootTemplate();

        if (!$customThemeFrom && !$customThemeTo && !$customTheme && !$customNewLayout) {
            return;
        }

        $nodeCustomDesign = $entryNode->addChild('custom_design');

        if ($customThemeFrom) {
            $nodeCustomDesign->addChild('from', $customThemeFrom);
        }
        if ($customThemeTo) {
            $nodeCustomDesign->addChild('to', $customThemeTo);
        }
        if ($customNewLayout) {
            $nodeCustomDesign->addChild('layout', $customNewLayout);
        }
        if ($customTheme) {
            $nodeCustomDesign->addChild('theme_id', (string) $customTheme);
        }
    }
}