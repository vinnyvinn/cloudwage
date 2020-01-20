<?php
/**
 * @category PHP
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

namespace Payroll\Handlers;

class CoinageCalculator
{
    protected $coinDivisions = [];

    protected $step = 0;

    protected $coinage = [];

    protected $totalCoinage;

    protected $amount = 0;

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function setDivisions($divisions = [])
    {
        $this->coinDivisions = $divisions;
        $this->setTotalCoinage();

        return $this;
    }

    private function setTotalCoinage()
    {
        foreach ($this->coinDivisions as $coinDivision) {
            $this->totalCoinage[$coinDivision] = 0;
        }
    }

    public function get()
    {
        $this->step = 0;
        $this->getCoinage();

        return $this->coinage;
    }

    private function getCoinage()
    {
        $coinDivision = $this->getDivision();
        $result = explode('.', $this->amount / $coinDivision);
        $this->amount = $this->amount - ($coinDivision * $result[0]);
        $this->coinage [$coinDivision] = $result[0];
        $this->totalCoinage[$coinDivision] += $result[0];

        $this->step++;

        if ($this->step < count($this->coinDivisions)) {
            $this->getCoinage();
        }
    }

    public function getTotalCoinage()
    {
        return $this->totalCoinage;
    }

    private function getDivision()
    {
        return $this->coinDivisions[$this->step];
    }

    public function __call($name, $args)
    {
        if (starts_with($name, ['with'])) {
            $method = 'set' . substr($name, 4);
            return $this->$method($args[0]);
        }

        return $this;
    }
}
