<?php

namespace Oro\Bundle\CustomerBundle\Tests\Behat\Context;

use Behat\Gherkin\Node\TableNode;

use Oro\Bundle\CustomerBundle\Tests\Behat\Element\FrontendGridColumnManager;
use Oro\Bundle\DataGridBundle\Tests\Behat\Element\Grid;
use Oro\Bundle\TestFrameworkBundle\Behat\Context\OroFeatureContext;
use Oro\Bundle\TestFrameworkBundle\Behat\Element\OroPageObjectAware;
use Oro\Bundle\TestFrameworkBundle\Tests\Behat\Context\PageObjectDictionary;

class GridContext extends OroFeatureContext implements OroPageObjectAware
{
    use PageObjectDictionary;

    /**
     * Example: I set "Test" as grid view name for "TestGrid" grid on frontend
     *
     * @Given /^(?:|I )set "(?P<name>([\w\s]+))" as grid view name for "(?P<gridName>([\w\s]+))" grid on frontend$/
     *
     * @param string $name
     * @param string $gridName
     */
    public function setGridViewName($name, $gridName)
    {
        $grid = $this->getGrid($gridName);
        $grid->fillField('frontend-grid-view-name', $name);
    }

    /**
     * Example: I mark Set as Default on grid view for "TestGrid" grid on frontend
     *
     * @Given /^(?:|I )mark Set as Default on grid view for "(?P<gridName>([\w\s]+))" grid on frontend$/
     *
     * @param string $gridName
     */
    public function setGridViewAsDefault($gridName)
    {
        $grid = $this->getGrid($gridName);
        $grid->checkField('is_default');
    }

    /**
     * @param string|null $grid
     * @return Grid
     */
    protected function getGrid($grid = 'Grid')
    {
        return $this->elementFactory->createElement($grid);
    }

    //@codingStandardsIgnoreStart
    /**
     * @When /^(?:|I )show column "(?P<columnName>(?:[^"]|\\")*)" in "(?P<datagridName>(?:[^"]|\\")*)" frontend grid$/
     * @When /^(?:|I )mark as visible column "(?P<columnName>(?:[^"]|\\")*)" in "(?P<datagridName>(?:[^"]|\\")*)" frontend grid$/
     *
     * @param string $columnName
     * @param string $datagridName
     */
    //@codingStandardsIgnoreEnd
    public function checkColumnOptionFrontendDatagrid($columnName, $datagridName)
    {
        $grid = $this->getGrid($datagridName);

        /** @var FrontendGridColumnManager $columnManager */
        $columnManager = $grid->getElement('FrontendGridColumnManager');
        $columnManager->open();
        $columnManager->checkColumnVisibility($columnName);
        $columnManager->close();
    }

    /**
     * @When /^(?:|I )go to next page in grid$/
     * @When /^(?:|I )go to next page in "(?P<gridName>[\w\s]+)"$/
     */
    public function iGoToNextPageInGrid($gridName = null)
    {
        $grid = $this->getGrid($gridName);

        $grid->getElement('FrontendGridNextPageButton')->click();
    }

    /**
     * @When /^(?:|I )go to prev page in grid$/
     * @When /^(?:|I )go to prev page in "(?P<gridName>[\w\s]+)"$/
     */
    public function iGoToPrevPageInGrid($gridName = null)
    {
        $grid = $this->getGrid($gridName);

        $grid->getElement('FrontendGridPrevPageButton')->click();
    }

    /**
     * Example: I should see following elements in "Frontend Grid" grid:
     *            | Action System Button  |
     *            | Action Default Button |
     *            | Filter Button         |
     * @When /^(?:|I )should see following elements in grid:$/
     * @When /^(?:|I )should see following elements in "(?P<gridName>[\w\s]+)":$/
     * @When /^(?:|I )should see following elements in "(?P<toolbar>[\w\s]+)" for grid:$/
     * @When /^(?:|I )should see following elements in "(?P<toolbar>[\w\s]+)" for "(?P<gridName>[\w\s]+)":$/
     */
    public function iShouldSeeElementsInGrid(TableNode $table, $toolbar = null, $gridName = null)
    {
        $context = $this->getGrid($gridName);

        if (!is_null($toolbar)) {
            $context = $context->getElement($toolbar);
        }

        foreach ($table as $item) {
            self::assertTrue(
                $context->getElement(reset($item))->isIsset(),
                sprintf('Element "%s" not found on the page', reset($item))
            );
        }
    }

    /**
     * Example: I should not see following elements in "Frontend Grid" grid:
     *            | Action System Button  |
     *            | Action Default Button |
     *            | Filter Button         |
     * @When /^(?:|I )should not see following elements in grid:$/
     * @When /^(?:|I )should not see following elements in "(?P<gridName>[\w\s]+)":$/
     * @When /^(?:|I )should not see following elements in "(?P<toolbar>[\w\s]+)" for grid:$/
     * @When /^(?:|I )should not see following elements in "(?P<toolbar>[\w\s]+)" for "(?P<gridName>[\w\s]+)":$/
     */
    public function iShouldNotSeeElementsInGrid(TableNode $table, $toolbar = null, $gridName = null)
    {
        $context = $this->getGrid($gridName);

        if (!is_null($toolbar)) {
            $context = $context->getElement($toolbar);
        }

        foreach ($table as $item) {
            self::assertFalse(
                $context->getElement(reset($item))->isIsset(),
                sprintf('Element "%s" not found on the page', reset($item))
            );
        }
    }
}
