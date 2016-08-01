<?php
/**
 * @category    Magento
 * @package     Magento_CatalogInventory
 * @subpackage  unit_tests
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogInventory\Test\Unit\Model\Indexer\Stock\Action;

class FullTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteWithAdapterErrorThrowsException()
    {
        $indexerFactoryMock = $this->getMock(
            \Magento\CatalogInventory\Model\ResourceModel\Indexer\StockFactory::class,
            [],
            [],
            '',
            false
        );
        $resourceMock = $this->getMock(\Magento\Framework\App\ResourceConnection::class, [], [], '', false);
        $productTypeMock = $this->getMock(\Magento\Catalog\Model\Product\Type::class, [], [], '', false);
        $connectionMock = $this->getMock(\Magento\Framework\DB\Adapter\AdapterInterface::class);

        $exceptionMessage = 'exception message';

        $connectionMock->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \Exception($exceptionMessage)));

        $resourceMock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($connectionMock));

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $model = $objectManager->getObject(
            \Magento\CatalogInventory\Model\Indexer\Stock\Action\Full::class,
            [
               'resource' => $resourceMock,
               'indexerFactory' => $indexerFactoryMock,
               'catalogProductType' => $productTypeMock,
            ]
        );

        $this->setExpectedException(\Magento\Framework\Exception\LocalizedException::class, $exceptionMessage);

        $model->execute();
    }
}
