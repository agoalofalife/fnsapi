<?php
declare(strict_types=1);

namespace Fns;

class Ticket
{
    /**
     * Сумма чека в копейках
     *
     * @var int $sum
     */
    private $sum = null;

    /**
     * Дата и время операции в формате yyyy-MM-dd'T'HH':'mm':'ss
     * example 2019-12-20T00:28:39
     *
     * @var string $date
     */
    private $date = null;

    /**
     * Номер ФН
     *
     * @var string $fn
     */
    private $fn = null;

    /**
     * Тип операции (Приход, Возврат прихода, Расход, Возврат расхода)
     *
     * @var int $typeOperation
     */
    private $typeOperation = null;

    /**
     * Порядковый номер ФД
     *
     * @var int $fiscalDocumentId
     */
    private $fiscalDocumentId = null;

    /**
     * Фискальный признак документа
     *
     * @var string $fiscalSign
     */
    private $fiscalSign = null;

    /**
     * Gets as sum
     *
     * Сумма чека в копейках
     *
     * @return int
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма чека в копейках
     *
     * @param int $sum
     * @return self
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата и время операции в формате yyyy-MM-dd'T'HH':'mm':'ss
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата и время операции в формате yyyy-MM-dd'T'HH':'mm':'ss
     *
     * @param string $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as fn
     *
     * Номер ФН
     *
     * @return string
     */
    public function getFn()
    {
        return $this->fn;
    }

    /**
     * Sets a new fn
     *
     * Номер ФН
     *
     * @param string $fn
     * @return self
     */
    public function setFn($fn)
    {
        $this->fn = $fn;
        return $this;
    }

    /**
     * Gets as typeOperation
     *
     * Тип операции (Приход, Возврат прихода, Расход, Возврат расхода)
     *
     * @return int
     */
    public function getTypeOperation()
    {
        return $this->typeOperation;
    }

    /**
     * Sets a new typeOperation
     *
     * Тип операции (Приход, Возврат прихода, Расход, Возврат расхода)
     *
     * @param int $typeOperation
     * @return self
     */
    public function setTypeOperation($typeOperation)
    {
        $this->typeOperation = $typeOperation;
        return $this;
    }

    /**
     * Gets as fiscalDocumentId
     *
     * Порядковый номер ФД
     *
     * @return int
     */
    public function getFiscalDocumentId()
    {
        return $this->fiscalDocumentId;
    }

    /**
     * Sets a new fiscalDocumentId
     *
     * Порядковый номер ФД
     *
     * @param int $fiscalDocumentId
     * @return self
     */
    public function setFiscalDocumentId($fiscalDocumentId)
    {
        $this->fiscalDocumentId = $fiscalDocumentId;
        return $this;
    }

    /**
     * Gets as fiscalSign
     *
     * Фискальный признак документа
     *
     * @return string
     */
    public function getFiscalSign()
    {
        return $this->fiscalSign;
    }

    /**
     * Sets a new fiscalSign
     *
     * Фискальный признак документа
     *
     * @param string $fiscalSign
     * @return self
     */
    public function setFiscalSign($fiscalSign)
    {
        $this->fiscalSign = $fiscalSign;
        return $this;
    }
}